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

namespace NFQ\SyliusOmnisendPlugin\Client\Response\Model;

class CategorySuccess
{
    /** @var string */
    private $categoryID;

    public function getCategoryID(): string
    {
        return $this->categoryID;
    }

    public function setCategoryID(string $categoryID): void
    {
        $this->categoryID = $categoryID;
    }
}
