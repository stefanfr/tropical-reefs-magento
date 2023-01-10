<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace TropicalReefs\CustomGraphQl\Model\Resolver;

use TropicalReefs\CustomGraphQl\Model\Resolver\Products\Query\Filter as ProductFilter;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Product implements ResolverInterface
{
    protected Uid $uidEncoder;
    protected ProductFilter $productFilter;

    public function __construct(
        Uid           $uidEncoder,
        ProductFilter $productFilter
    )
    {
        $this->uidEncoder = $uidEncoder;
        $this->productFilter = $productFilter;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (array_key_exists('uid', $args['filter'])) {
            $args['filter']['entity_id']['eq'] = $this->uidEncoder->decode($args['filter']['uid']['eq']);
            unset($args['filter']['uid']['eq']);
        }

        $searchResult = $this->productFilter->getResult($args, $info, $context);

        if ($searchResult->getCurrentPage() > $searchResult->getTotalPages() && $searchResult->getTotalCount() > 0) {
            throw new GraphQlInputException(
                __(
                    'currentPage value %1 specified is greater than the %2 page(s) available.',
                    [$searchResult->getCurrentPage(), $searchResult->getTotalPages()]
                )
            );
        }

        return [
            'product' => current($searchResult->getProductsSearchResult()),
        ];
    }
}
