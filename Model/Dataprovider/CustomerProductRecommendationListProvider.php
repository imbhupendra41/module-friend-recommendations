<?php

namespace SwiftOtter\FriendRecommendations\Model\Dataprovider;

use Magento\Framework\Api\SearchCriteriaBuilder;
use SwiftOtter\FriendRecommendations\Api\Data\RecommendationListInterface;
use SwiftOtter\FriendRecommendations\Model\RecommendationListRepository;

class CustomerProductRecommendationListProvider
{
    private RecommendationListRepository $recommendationListRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        RecommendationListRepository $recommendationListRepository,
        SearchCriteriaBuilder        $searchCriteriaBuilder
    ) {
        $this->recommendationListRepository = $recommendationListRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function getLists($email): array
    {
        $this->searchCriteriaBuilder->addFilter('email', $email);
        $items = $this->recommendationListRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        $listsData = [];
        foreach ($items as $list) {
            $listsData [] = $this->formatData($list);
        }
        return $listsData;
    }

    private function formatData(RecommendationListInterface $list)
    {
        return [
            'list_id' => $list->getId(),
            'friendName' => $list->getFriendName(),
            'title' => $list->getTitle(),
            'note' => $list->getNote(),
            'products' => []
        ];
    }
}
