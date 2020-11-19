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

namespace Tests\NFQ\SyliusOmnisendPlugin\Mock;

use Sylius\Component\Core\Model\Product;
use NFQ\SyliusOmnisendPlugin\Model\ProductAdditionalDataAwareInterface;

class ProductAdditionalDataAwareMock extends Product implements ProductAdditionalDataAwareInterface
{
    public function getOmnisendTags(): ?array
    {
        return [
            'test1',
            'test2',
        ];
    }

    public function getOmnisendVendor(): ?string
    {
        return 'vendor';
    }

    public function getOmnisendType(): ?string
    {
        return 'type';
    }
}
