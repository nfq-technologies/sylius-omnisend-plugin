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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;

interface EventBuilderDirectorInterface
{
    public function build(BaseEvent $event): CreateEvent;

    public function buildWithFormattedFields(string $email, string $systemName, array $fields): CreateEvent;
}
