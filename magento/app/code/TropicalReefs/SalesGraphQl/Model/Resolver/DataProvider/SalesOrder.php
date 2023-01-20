<?php
declare(strict_types=1);

namespace TropicalReefs\SalesGraphQl\Model\Resolver\DataProvider;

use Magento\Sales\Model\Order;

class SalesOrder
{
    public function __construct(
        protected Order $order
    )
    {
    }

    public function getSalesOrder(string $orderNumber): Order
    {
        return $this->order->loadByIncrementId($orderNumber);
    }
}

