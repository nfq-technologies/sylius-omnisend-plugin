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

use NFQ\SyliusOmnisendPlugin\Model\ContactAwareInterface;
use NFQ\SyliusOmnisendPlugin\Model\ContactAwareTrait;
use Sylius\Component\Core\Model\Customer;

class CustomerMock extends Customer implements ContactAwareInterface
{
    use ContactAwareTrait;
}
