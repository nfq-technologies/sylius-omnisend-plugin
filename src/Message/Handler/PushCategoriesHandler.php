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
use NFQ\SyliusOmnisendPlugin\Message\Command\PushCategories;
use Sylius\Component\Taxonomy\Factory\TaxonFactoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PushCategoriesHandler implements MessageHandlerInterface
{
    /** @var OmnisendClient */
    private $omnisendClient;

    /** @var TaxonFactoryInterface */
    private $factory;

    /** @var TaxonRepositoryInterface */
    private $repository;

    public function __construct(OmnisendClient $omnisendClient, TaxonFactoryInterface $factory, TaxonRepositoryInterface $repository)
    {
        $this->omnisendClient = $omnisendClient;
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function __invoke(PushCategories $message): void
    {
        $this->omnisendClient->deleteCategory($message->getTaxonCode(), $message->getChannelCode());
    }
}
