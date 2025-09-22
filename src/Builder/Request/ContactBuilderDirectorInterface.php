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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use Sylius\Component\Core\Model\CustomerInterface;

interface ContactBuilderDirectorInterface
{
    public function build(ContactAwareInterface $customer): Contact;
}
