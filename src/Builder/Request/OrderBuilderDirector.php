<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;

class OrderBuilderDirector implements OrderBuilderDirectorInterface
{
    /** @var OrderBuilderInterface */
    private $builder;

    public function __construct(OrderBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function build(OrderInterface $order): Order
    {
        $this->builder->createOrder();
        $this->builder->addCartData($order);
        $this->builder->addOrderData($order);
        $this->builder->addProducts($order);
        $this->builder->addTrackingData($order);
        $this->builder->addCancelData($order);
        $this->builder->addStates($order);
        $this->builder->addAddresses($order);
        $this->builder->addCouponData($order);
        $this->builder->addUpdateAt($order);

        return $this->builder->getOrder();
    }

    public function buildUpdatedStatesData(OrderInterface $order): Order
    {
        $this->builder->createOrder();
        $this->builder->addCartData($order);
        $this->builder->addStates($order);
        $this->builder->addTrackingData($order);
        $this->builder->addCancelData($order);

        return $this->builder->getOrder();
    }
}
