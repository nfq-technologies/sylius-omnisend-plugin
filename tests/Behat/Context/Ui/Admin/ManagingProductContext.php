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
