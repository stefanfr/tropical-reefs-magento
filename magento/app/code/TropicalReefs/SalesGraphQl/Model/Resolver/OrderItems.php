<?php

namespace TropicalReefs\SalesGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\Data\OrderInterface;
use TropicalReefs\SalesGraphQl\Model\Resolver\DataProvider\OrderItems as OrderItemProvider;

class OrderItems implements ResolverInterface
{
    public function __construct(
        protected ValueFactory      $valueFactory,
        protected OrderItemProvider $orderItemProvider,
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if ( ! (($value['model'] ?? null) instanceof OrderInterface)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }
        /** @var OrderInterface $parentOrder */
        $parentOrder = $value['model'];
        $orderItemIds = [];
        foreach ($parentOrder->getItems() as $item) {
            if ( ! $item->getParentItemId()) {
                $orderItemIds[] = (int)$item->getItemId();
            }
            $this->orderItemProvider->addOrderItemId((int)$item->getItemId());
        }

        $itemsList = [];
        foreach ($orderItemIds as $orderItemId) {
            $itemsList[] = $this->valueFactory->create(
                function () use ($orderItemId) {
                    return $this->orderItemProvider->getOrderItemById((int)$orderItemId);
                }
            );
        }

        return $itemsList;
    }

}
