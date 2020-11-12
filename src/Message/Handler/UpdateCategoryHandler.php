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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCategory;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateCategoryHandler implements MessageHandlerInterface
{
    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    /** @var CategoryFactoryInterface */
    private $categoryFactory;

    public function __construct(
        OmnisendClient $omnisendClient,
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

        if (null !== $taxon) {
            $this->omnisendClient->putCategory($this->categoryFactory->create($taxon), $message->getChannelCode());
        }
    }
}
