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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class Batch
{
    public const ENDPOINTS = [
        'contacts', 'orders', 'products', 'events', 'categories',
    ];

    public const METHODS = [
        'POST', 'PUT'
    ];

    /** @var string */
    private $method;

    /** @var string */
    private $endpoint;

    /** @var string|null */
    private $eventID;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getEventID(): ?string
    {
        return $this->eventID;
    }

    public function setEventID(?string $eventID): self
    {
        $this->eventID = $eventID;

        return $this;
    }
}
