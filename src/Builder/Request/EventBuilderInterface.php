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
