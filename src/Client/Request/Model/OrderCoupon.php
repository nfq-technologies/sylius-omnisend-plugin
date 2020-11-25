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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

class OrderCoupon
{
    /** @var string|null */
    private $code;

    /** @var string|null */
    private $type;

    /** @var int|null */
    private $value;

    public function __construct(?string $code, ?string $type, ?int $value)
    {
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}
