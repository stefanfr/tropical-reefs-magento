<?php
declare(strict_types=1);

namespace TropicalReefs\SalesGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\Data\OrderAddressInterface;

class SalesOrder implements ResolverInterface
{
    public function __construct(
        protected DataProvider\SalesOrder $salesOrderDataProvider
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if ( ! isset($args['orderNumber'])) {
            throw new GraphQlInputException(__('"sales id should be specified'));
        }

        $order = $this->salesOrderDataProvider->getSalesOrder($args['orderNumber']);
        $orderData = $order->getData();

        return array_merge(
            $orderData,
            [
                'model' => $order,
                'items' => [],
                'payment_methods' => $order->getAllPayments(),
                'shipping_address' => $this->resolveAddress($order->getShippingAddress()),
                'billing_address' => $this->resolveAddress($order->getBillingAddress()),
                'shipments' => $order->getShipmentsCollection(),
                'shipping_method' => $order->getShippingMethod(),
                'comments' => $order->getAllStatusHistory(),
            ]
        );
    }

    protected function resolveAddress(?OrderAddressInterface $address)
    {
        $returnAddress = $address?->getData();
        $returnAddress['street'] = $address?->getStreet();
        $returnAddress['country_code'] = $address?->getCountryId();

        return $returnAddress;
    }
}

