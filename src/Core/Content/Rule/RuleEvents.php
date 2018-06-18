<?php declare(strict_types=1);

namespace Shopware\Core\Content\Rule;

class RuleEvents
{
    /**
     * @Event("Shopware\Core\Framework\ORM\Event\EntityWrittenEvent")
     */
    public const RULE_WRITTEN_EVENT = 'rule.written';

    /**
     * @Event("Shopware\Core\Framework\ORM\Event\EntityDeletedEvent")
     */
    public const RULE_DELETED_EVENT = 'rule.deleted';

    /**
     * @Event("Shopware\Core\Framework\ORM\Event\EntityLoadedEvent")
     */
    public const RULE_LOADED_EVENT = 'rule.loaded';

    /**
     * @Event("Shopware\Core\Framework\ORM\Event\EntitySearchResultLoadedEvent")
     */
    public const RULE_SEARCH_RESULT_LOADED_EVENT = 'rule.search.result.loaded';

    /**
     * @Event("Shopware\Core\Framework\ORM\Event\EntityAggregationResultLoadedEvent")
     */
    public const RULE_AGGREGATION_LOADED_EVENT = 'rule.aggregation.result.loaded';

    /**
     * @Event("Shopware\Core\Framework\ORM\Event\EntityIdSearchResultLoadedEvent")
     */
    public const RULE_ID_SEARCH_RESULT_LOADED_EVENT = 'rule.id.search.result.loaded';
}