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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use NFQ\SyliusOmnisendPlugin\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;

class ManagingProductContext implements Context
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /** @Then Product with a code :code should be marked as pushed to Omnisend */
    public function customerHasOmnisendClientId(string $code): void
    {
        /** @var ProductInterface $product */
        $product = $this->productRepository->findOneBy(['code' => $code]);
        $this->entityManager->refresh($product);

        Assert::isInstanceOf($product, \Sylius\Component\Core\Model\ProductInterface::class);
        Assert::true($product->isPushedToOmnisend());
    }
}
