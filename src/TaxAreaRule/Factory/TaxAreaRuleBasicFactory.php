<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\TaxAreaRule\Factory;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Factory\Factory;
use Shopware\Search\QueryBuilder;
use Shopware\Search\QuerySelection;
use Shopware\TaxAreaRule\Extension\TaxAreaRuleExtension;
use Shopware\TaxAreaRule\Struct\TaxAreaRuleBasicStruct;

class TaxAreaRuleBasicFactory extends Factory
{
    const ROOT_NAME = 'tax_area_rule';

    const FIELDS = [
       'uuid' => 'uuid',
       'area_uuid' => 'area_uuid',
       'area_country_uuid' => 'area_country_uuid',
       'area_country_state_uuid' => 'area_country_state_uuid',
       'tax_uuid' => 'tax_uuid',
       'customer_group_uuid' => 'customer_group_uuid',
       'tax_rate' => 'tax_rate',
       'active' => 'active',
       'name' => 'translation.name',
    ];

    /**
     * @var TaxAreaRuleExtension[]
     */
    protected $extensions = [];

    public function hydrate(
        array $data,
        TaxAreaRuleBasicStruct $taxAreaRule,
        QuerySelection $selection,
        TranslationContext $context
    ): TaxAreaRuleBasicStruct {
        $taxAreaRule->setUuid((string) $data[$selection->getField('uuid')]);
        $taxAreaRule->setAreaUuid(isset($data[$selection->getField('area_uuid')]) ? (string) $data[$selection->getField('area_uuid')] : null);
        $taxAreaRule->setAreaCountryUuid(isset($data[$selection->getField('area_country_uuid')]) ? (string) $data[$selection->getField('area_country_uuid')] : null);
        $taxAreaRule->setAreaCountryStateUuid(isset($data[$selection->getField('area_country_state_uuid')]) ? (string) $data[$selection->getField('area_country_state_uuid')] : null);
        $taxAreaRule->setTaxUuid((string) $data[$selection->getField('tax_uuid')]);
        $taxAreaRule->setCustomerGroupUuid((string) $data[$selection->getField('customer_group_uuid')]);
        $taxAreaRule->setTaxRate((float) $data[$selection->getField('tax_rate')]);
        $taxAreaRule->setActive((bool) $data[$selection->getField('active')]);
        $taxAreaRule->setName((string) $data[$selection->getField('name')]);

        foreach ($this->extensions as $extension) {
            $extension->hydrate($taxAreaRule, $data, $selection, $context);
        }

        return $taxAreaRule;
    }

    public function getFields(): array
    {
        $fields = array_merge(self::FIELDS, parent::getFields());

        return $fields;
    }

    public function joinDependencies(QuerySelection $selection, QueryBuilder $query, TranslationContext $context): void
    {
        if ($translation = $selection->filter('translation')) {
            $query->leftJoin(
                $selection->getRootEscaped(),
                'tax_area_rule_translation',
                $translation->getRootEscaped(),
                sprintf(
                    '%s.tax_area_rule_uuid = %s.uuid AND %s.language_uuid = :languageUuid',
                    $translation->getRootEscaped(),
                    $selection->getRootEscaped(),
                    $translation->getRootEscaped()
                )
            );
            $query->setParameter('languageUuid', $context->getShopUuid());
        }

        $this->joinExtensionDependencies($selection, $query, $context);
    }

    public function getAllFields(): array
    {
        $fields = array_merge(self::FIELDS, $this->getExtensionFields());

        return $fields;
    }

    protected function getRootName(): string
    {
        return self::ROOT_NAME;
    }
}
