<?php

namespace SwiftOtter\FriendRecommendations\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use SwiftOtter\FriendRecommendations\Model\Resolver\Service\CreateRecommendationListService;

class CreateRecommendationListMutation implements ResolverInterface
{
    private CreateRecommendationListService $createRecommendationListService;

    /**
     * @param CreateRecommendationListService $createRecommendationListService
     */
    public function __construct(
        CreateRecommendationListService $createRecommendationListService
    ) {
        $this->createRecommendationListService = $createRecommendationListService;
    }
    /**
     * {@inheritdoc}
     * @param ContextInterface $context
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($args['email'])) {
            throw new GraphQlInputException(__('Email address field is required'));
        }
        if (!isset($args['friendName'])) {
            throw new GraphQlInputException(__('Friend Name field is required'));
        }
        if (!isset($args['productSkus']) ||empty($args['productSkus'])) {
            throw new GraphQlInputException(__('Product SKUs can not be null'));
        }

        return $this->createRecommendationListService->execute($args);
    }
}
