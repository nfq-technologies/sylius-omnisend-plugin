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

namespace NFQ\SyliusOmnisendPlugin\Client\Response\Model;

class ProductSuccess
{
    /** @var string */
    private $productID;

    public function getProductID(): string
    {
        return $this->productID;
    }

    public function setProductID(string $productID): self
    {
        $this->productID = $productID;

        return $this;
    }
}
