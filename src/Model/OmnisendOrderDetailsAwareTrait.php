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

use Doctrine\ORM\Mapping as ORM;

trait OmnisendOrderDetailsAwareTrait
{
    /**
     * @var OrderDetails
     * @ORM\OneToOne(targetEntity="NFQ\SyliusOmnisendPlugin\Model\OrderDetails", cascade={"all"})
     *
     * @ORM\JoinColumn(name="omnisend_order_details_id", onDelete="CASCADE")
     */
    protected $omnisendOrderDetails;

    public function __construct()
    {
        $this->omnisendOrderDetails = new OrderDetails();
    }

    public function getOmnisendOrderDetails(): OrderDetails
    {
        return $this->omnisendOrderDetails;
    }

    public function setOmnisendOrderDetails(OrderDetails $omnisendOrderDetails): void
    {
        $this->omnisendOrderDetails = $omnisendOrderDetails;
    }
}
