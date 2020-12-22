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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Webmozart\Assert\Assert;

class CartContext implements Context
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(OrderRepositoryInterface $orderRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
    }

    /** @Then Cart should be synced with Omnisend */
    public function cartWasSyncedWithOmnisend()
    {
        /** @var OrderInterface $cart */
        $cart = $this->getLatestCart();
        $this->entityManager->refresh($cart);
        Assert::notNull($cart->getOmnisendOrderDetails()->getCartId());
    }

    /** @Then Cart should not be synced with Omnisend */
    public function cartWasNotSyncedWithOmnisend()
    {
        /** @var OrderInterface $cart */
        $cart = $this->getLatestCart();
        $this->entityManager->refresh($cart);
        Assert::null($cart->getOmnisendOrderDetails()->getCartId());
    }

    private function getLatestCart()
    {
        return $this->orderRepository->findOneBy(['state' => OrderInterface::STATE_CART]);
    }
}
