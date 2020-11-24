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
    public const ENDPOINTS_CONTACT = 'contacts';
    public const ENDPOINTS_ORDER = 'orders';
    public const ENDPOINTS_PRODUCT = 'products';
    public const ENDPOINTS_EVENT = 'events';
    public const ENDPOINTS_CATEGORIES = 'categories';

    public const METHODS_POST = 'POST';
    public const METHODS_PUT = 'PUT';

    /** @var string */
    private $method;

    /** @var string */
    private $endpoint;

    /** @var string|null */
    private $eventID;

    /** @var array */
    private $items;

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

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }
}
