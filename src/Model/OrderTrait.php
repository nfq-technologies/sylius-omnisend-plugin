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

namespace NFQ\SyliusOmnisendPlugin\Model;

use DateTimeInterface;

trait OrderTrait
{
    use OmnisendCartAwareTrait;

    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", name="cancelled_at", nullable=true)
     */
    private $cancelledAt;

    public function getCancelledAt(): ?DateTimeInterface
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?DateTimeInterface $dateTime): void
    {
        $this->cancelledAt = $dateTime;
    }
}
