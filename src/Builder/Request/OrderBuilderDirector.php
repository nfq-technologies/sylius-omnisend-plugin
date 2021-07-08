<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;

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
        $this->builder->addTotals($order);
        $this->builder->addProducts($order);
        $this->builder->addTrackingData($order);
        $this->builder->addCancelData($order);
        $this->builder->addStates($order);
        $this->builder->addAddresses($order);
        $this->builder->addCouponData($order);
        $this->builder->addUpdateAt($order);
        $this->builder->addCustomFields($order);

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
