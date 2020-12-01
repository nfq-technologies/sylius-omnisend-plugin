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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;

interface EventBuilderInterface
{
    public function createEvent(): void;

    public function addMainData(BaseEvent $event): void;

    public function addFields(BaseEvent $event): void;

    public function addEmail(string $email): void;

    public function addFormattedFields(array $fields): void;

    public function addSystemName(string $name): void;

    public function getEvent(): CreateEvent;
}
