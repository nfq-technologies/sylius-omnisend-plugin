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
use DateTime;

trait PushedToOmnisendAwareTrait
{
    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(name="pushed_to_omnisend", type="datetime", nullable=true)
     */
    protected $pushedToOmnisend;

    public function isPushedToOmnisend(): bool
    {
        return null !== $this->pushedToOmnisend;
    }

    public function getPushedToOmnisend(): ?DateTimeInterface
    {
        return $this->pushedToOmnisend;
    }

    public function setPushedToOmnisend(DateTimeInterface $dateTime = null): void
    {
        if (null === $dateTime) {
            $dateTime = new DateTime();
        }

        $this->pushedToOmnisend = $dateTime;
    }
}
