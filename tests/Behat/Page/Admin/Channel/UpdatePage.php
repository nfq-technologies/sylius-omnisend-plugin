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
