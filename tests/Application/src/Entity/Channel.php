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

namespace Tests\NFQ\SyliusOmnisendPlugin\Application\Entity;

use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements ChannelOmnisendTrackingAwareInterface
{
    use ChannelOmnisendTrackingAwareTrait;
}
