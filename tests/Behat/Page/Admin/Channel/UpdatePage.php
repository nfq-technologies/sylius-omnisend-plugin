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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Page\Admin\Channel;

use Sylius\Behat\Page\Admin\Channel\UpdatePage as BaseUpdatePage;

class UpdatePage extends BaseUpdatePage
{
    public function setOmnisendTrackingKey(string $key)
    {
        $this->getElement('omnisend_tracking_key')->setValue($key);
    }

    public function getOmnisendTrackingKey(): string
    {
        return $this->getElement('omnisend_tracking_key')->getValue();
    }

    public function setOmnisendApiKey(string $key)
    {
        $this->getElement('omnisend_api_key')->setValue($key);
    }

    public function getOmnisendApiKey(): string
    {
        return $this->getElement('omnisend_api_key')->getValue();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            parent::getDefinedElements(),
            [
                'omnisend_tracking_key' => '#sylius_channel_omnisendTrackingKey',
                'omnisend_api_key' => '#sylius_channel_omnisendApiKey',
            ]
        );
    }
}
