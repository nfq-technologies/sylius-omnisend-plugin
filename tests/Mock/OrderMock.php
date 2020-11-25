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

namespace Tests\NFQ\SyliusOmnisendPlugin\Mock;

use NFQ\SyliusOmnisendPlugin\Model\OmnisendOrderDetailsAwareTrait;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use Sylius\Component\Core\Model\Order;

class OrderMock extends Order implements OrderInterface
{
    use OmnisendOrderDetailsAwareTrait;

    use OmnisendOrderDetailsAwareTrait {
        OmnisendOrderDetailsAwareTrait::__construct as private omnisendOrderDetailsConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->omnisendOrderDetailsConstruct();
    }
}
