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

namespace Shopware\Locale\Searcher;

use Doctrine\DBAL\Connection;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Locale\Factory\LocaleBasicFactory;
use Shopware\Locale\Loader\LocaleBasicLoader;
use Shopware\Search\Criteria;
use Shopware\Search\QueryBuilder;
use Shopware\Search\Searcher;
use Shopware\Search\SearchResultInterface;
use Shopware\Search\SqlParser\SqlParser;
use Shopware\Search\UuidSearchResult;

class LocaleSearcher extends Searcher
{
    /**
     * @var LocaleBasicFactory
     */
    private $factory;

    /**
     * @var LocaleBasicLoader
     */
    private $loader;

    public function __construct(Connection $connection, SqlParser $parser, LocaleBasicFactory $factory, LocaleBasicLoader $loader)
    {
        parent::__construct($connection, $parser);
        $this->factory = $factory;
        $this->loader = $loader;
    }

    protected function createQuery(Criteria $criteria, TranslationContext $context): QueryBuilder
    {
        return $this->factory->createSearchQuery($criteria, $context);
    }

    protected function load(UuidSearchResult $uuidResult, TranslationContext $context): SearchResultInterface
    {
        $collection = $this->loader->load($uuidResult->getUuids(), $context);

        $result = new LocaleSearchResult($collection->getElements());

        $result->setTotal($uuidResult->getTotal());

        return $result;
    }
}
