<?php

namespace SwiftOtter\FriendRecommendations\Model\Resolver\RecommendationList;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductRepository;

class Products implements ResolverInterface
{
    private RecommendationListProductRepository $recommendationListProductRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private ProductRepositoryInterface $productRepository;

    /**
     * @param RecommendationListProductRepository $recommendationListProductRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        RecommendationListProductRepository $recommendationListProductRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductRepositoryInterface $productRepository
    ) {
        $this->recommendationListProductRepository = $recommendationListProductRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productRepository = $productRepository;
    }
    /**
     * @param ContextInterface $context
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        return $this->formatData($value['list_id']);
    }

    private function formatData($listId)
    {
        $this->searchCriteriaBuilder->addFilter(
            'recommendation_list_ids',
            $listId,
            'eq'
        );
        $products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();

        foreach ($products as $product) {
            $products[] = [
              'name' => $product->getName(),
              'sku' => $product->getSku(),
              'thumbnailUrl' => $product->getThumbnail()
            ];
        }
        return $products;
    }
}
