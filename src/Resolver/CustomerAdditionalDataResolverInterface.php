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

namespace NFQ\SyliusOmnisendPlugin\Resolver;

use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;

interface CustomerAdditionalDataResolverInterface
{
    public function geCustomProperties(ContactAwareInterface $customer): ?array;

    public function getTags(ContactAwareInterface $customer): ?array;
}
