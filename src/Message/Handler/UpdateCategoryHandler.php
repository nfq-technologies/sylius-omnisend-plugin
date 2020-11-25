<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCategory;
use NFQ\SyliusOmnisendPlugin\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use DateTime;

class UpdateCategoryHandler implements MessageHandlerInterface
{
    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    /** @var CategoryFactoryInterface */
    private $categoryFactory;

    public function __construct(
        OmnisendClientInterface $omnisendClient,
        TaxonRepositoryInterface $customerRepository,
        CategoryFactoryInterface $categoryFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->omnisendClient = $omnisendClient;
        $this->taxonRepository = $customerRepository;
    }

    public function __invoke(UpdateCategory $message): void
    {
        /** @var TaxonInterface|null $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $message->getTaxonCode()]);

        if (null === $taxon) {
            return;
        }

        if (
            $taxon->isPushedToOmnisend()
            || null !== $this->omnisendClient->getCategory($taxon->getCode(), $message->getChannelCode())
        ) {
            $response = $this->omnisendClient->putCategory(
                $this->categoryFactory->create($taxon),
                $message->getChannelCode()
            );
        } else {
            $response = $this->omnisendClient->postCategory(
                $this->categoryFactory->create($taxon),
                $message->getChannelCode()
            );
        }

        if (null !== $response) {
            $taxon->setPushedToOmnisend(new DateTime());
            $this->taxonRepository->add($taxon);
        }
    }
}
