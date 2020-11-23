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

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use NFQ\SyliusOmnisendPlugin\Model\CustomTagsAwareInterface;
use NFQ\SyliusOmnisendPlugin\Model\CustomTagsAwareTrait;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_variant")
 */
class ProductVariant extends BaseProductVariant implements CustomTagsAwareInterface
{
    use CustomTagsAwareTrait;
}
