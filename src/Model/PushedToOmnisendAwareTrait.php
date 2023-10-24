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

namespace NFQ\SyliusOmnisendPlugin\Model;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait PushedToOmnisendAwareTrait
{
    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(name="pushed_to_omnisend", type="datetime", nullable=true)
     */
    #[ORM\Column(name: 'pushed_to_omnisend', type: 'datetime', nullable: true)]
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
