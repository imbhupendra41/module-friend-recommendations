<?php

namespace SwiftOtter\FriendRecommendations\Model\Resolver\Service;

use Magento\Framework\Exception\CouldNotSaveException;
use SwiftOtter\FriendRecommendations\Api\Data\RecommendationListInterface;
use SwiftOtter\FriendRecommendations\Api\Data\RecommendationListInterfaceFactory;
use SwiftOtter\FriendRecommendations\Api\Data\RecommendationListProductInterfaceFactory;
use SwiftOtter\FriendRecommendations\Api\RecommendationListRepositoryInterface;
use SwiftOtter\FriendRecommendations\Model\RecommendationListProductRepository;

class CreateRecommendationListService
{
    private RecommendationListInterfaceFactory $recommendationList;
    private RecommendationListRepositoryInterface $recommendationListRepository;
    private RecommendationListProductInterfaceFactory $recommendationListProductInterfaceFactory;
    private RecommendationListProductRepository $recommendationListProductRepository;

    /**
     * @param RecommendationListInterfaceFactory $recommendationList
     * @param RecommendationListRepositoryInterface $recommendationListRepository
     * @param RecommendationListProductInterfaceFactory $recommendationListProductInterfaceFactory
     * @param RecommendationListProductRepository $recommendationListProductRepository
     */
    public function __construct(
        RecommendationListInterfaceFactory $recommendationList,
        RecommendationListRepositoryInterface $recommendationListRepository,
        RecommendationListProductInterfaceFactory $recommendationListProductInterfaceFactory,
        RecommendationListProductRepository $recommendationListProductRepository
    ) {
        $this->recommendationList = $recommendationList;
        $this->recommendationListRepository = $recommendationListRepository;
        $this->recommendationListProductInterfaceFactory = $recommendationListProductInterfaceFactory;
        $this->recommendationListProductRepository = $recommendationListProductRepository;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function execute($params): array
    {
        $recommendationList = $this->createRecommendationList($params);
        $this->createProductList($recommendationList->getId(), $params['productSkus']);
        return $this->formatData($recommendationList);
    }

    /**
     * @throws CouldNotSaveException
     */
    private function createRecommendationList($params)
    {
        $recommendationList = $this->recommendationList->create();
        $recommendationList->setEmail($params['email'])
                ->setTitle($params['title'])
                ->setFriendName('friendName')
                ->setNote('This is options Note');
        return  $this->recommendationListRepository->save($recommendationList);
    }

    /**
     * @throws CouldNotSaveException
     */
    private function createProductList($listId, $productSkus)
    {
        foreach ($productSkus as $sku) {
            $listProduct = $this->recommendationListProductInterfaceFactory->create()
               ->setListId($listId)
               ->setSku($sku);
            $this->recommendationListProductRepository->save($listProduct);
        }
    }

    private function formatData(RecommendationListInterface $recommendationList): array
    {
        return [
            'email' => $recommendationList->getEmail(),
            'friendName' => $recommendationList->getFriendName(),
            'title' => $recommendationList->getTitle(),
            'note' => $recommendationList->getNote()
        ];
    }
}
