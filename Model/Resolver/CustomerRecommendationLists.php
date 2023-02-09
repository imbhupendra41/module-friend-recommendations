<?php

namespace SwiftOtter\FriendRecommendations\Model\Resolver;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use SwiftOtter\FriendRecommendations\Model\Dataprovider\CustomerProductRecommendationListProvider;

class CustomerRecommendationLists implements ResolverInterface
{
    private CustomerProductRecommendationListProvider $customerProductRecommendationListProvider;
    private CustomerRepositoryInterface $customerRepositoryInterface;

    /**
     * @param CustomerProductRecommendationListProvider $customerProductRecommendationListProvider
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     */
    public function __construct(
        CustomerProductRecommendationListProvider $customerProductRecommendationListProvider,
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->customerProductRecommendationListProvider = $customerProductRecommendationListProvider;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }
    /**
     * {@inheritdoc }
     * @param ContextInterface $context
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if ($context->getExtensionAttributes()->getIsCustomer() != null) {
            $email = $this->customerRepositoryInterface->getById($context->getUserId())->getEmail();
            return $this->customerProductRecommendationListProvider->getLists($email);
        }
    }
}
