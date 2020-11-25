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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Context\Ui\Shop;

use Behat\MinkExtension\Context\MinkContext;

class OmnisendUserContext extends MinkContext
{
    /** @Then Browser has Omnisend contact cookie */
    public function setOmnisendClientCookie()
    {
        $this->getSession()->setCookie('omnisendContactID', '11111111');
    }
}
