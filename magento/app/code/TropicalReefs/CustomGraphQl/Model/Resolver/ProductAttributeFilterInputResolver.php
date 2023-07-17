<?php

namespace TropicalReefs\CustomGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

class ProductAttributeFilterInputResolver implements FieldEntityAttributesInterface
{
    public function getEntityAttributes(): array
    {
        dd('t');
        return ['uid'];
    }
}
