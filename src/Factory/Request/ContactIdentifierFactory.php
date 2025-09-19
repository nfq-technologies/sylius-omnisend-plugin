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

namespace NFQ\SyliusOmnisendPlugin\Factory\Request;

use function method_exists;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifier;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannel;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannelValue;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeProvider;
use function ucfirst;

class ContactIdentifierFactory implements ContactIdentifierFactoryInterface
{
    /** @var bool[] */
    private array $sendWelcomeMessageConfig;

    /** @var string[] */
    private array $typesMap = [
        ContactIdentifier::TYPE_EMAIL => 'email',
        ContactIdentifier::TYPE_PHONE => 'sms',
    ];

    /** @param bool[] $sendWelcomeMessageConfig */
    public function __construct(array $sendWelcomeMessageConfig)
    {
        $this->sendWelcomeMessageConfig = $sendWelcomeMessageConfig;
    }

    public function create(string $type, string $id, string $status): ContactIdentifier
    {
        $contactIdentifier = new ContactIdentifier();
        $contactIdentifier->setType($type);
        $contactIdentifier->setId($id);
        $contactIdentifier->setSendWelcomeMessage($this->sendWelcomeMessageConfig[$type]);

        $channel = new ContactIdentifierChannel();
        $channelValue = new ContactIdentifierChannelValue();
        $channelValue->setStatus($status);
        $channelValue->setStatusDate(DatetimeHelper::format(DatetimeProvider::currentDateTime()));
        $key = 'set' . ucfirst($this->typesMap[$type]);

        if (method_exists($channel, $key)) {
            $channel->$key($channelValue);
        }

        $contactIdentifier->setChannels($channel);

        return $contactIdentifier;
    }
}
