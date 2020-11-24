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

namespace Tests\NFQ\SyliusOmnisendPlugin\Factory\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifier;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannel;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\ContactIdentifierChannelValue;
use NFQ\SyliusOmnisendPlugin\Factory\Request\ContactIdentifierFactory;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeHelper;
use NFQ\SyliusOmnisendPlugin\Utils\DatetimeProvider;
use PHPUnit\Framework\TestCase;
use DateTime;

class ContactIdentifierFactoryTest extends TestCase
{
    /** @var ContactIdentifierFactory */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new ContactIdentifierFactory(
            [
                'email' => true,
                'phone' => false
            ]
        );
    }

    /** @dataProvider data */
    public function testIfBuildsWell(string $type, string $id, string $status, ContactIdentifier $result)
    {
        $identifier = $this->factory->create($type, $id, $status);

        $this->assertEquals($identifier, $result);
    }

    public function data()
    {
        $date = new DateTime('2012-12-12 12:12:12');
        DatetimeProvider::setDateTime($date);
        $emailContactIdentifier = new ContactIdentifier();
        $emailContactIdentifier->setType('email');
        $emailContactIdentifier->setId('test@nfq.lt');
        $emailContactIdentifier->setSendWelcomeMessage(true);
        $emailContactIdentifierChannel = new ContactIdentifierChannel();
        $emailContactIdentifierChannel->setEmail(
            (new ContactIdentifierChannelValue())
                ->setStatus('subscribed')
                ->setStatusDate(DatetimeHelper::format($date))
        );
        $emailContactIdentifier->setChannels($emailContactIdentifierChannel);

        $smsContactIdentifier = new ContactIdentifier();
        $smsContactIdentifier->setType('phone');
        $smsContactIdentifier->setId('868686868');
        $smsContactIdentifier->setSendWelcomeMessage(false);
        $smsContactIdentifierChannel = new ContactIdentifierChannel();
        $smsContactIdentifierChannel->setSms(
            (new ContactIdentifierChannelValue())
                ->setStatus('subscribed')
                ->setStatusDate(DatetimeHelper::format($date))
        );
        $smsContactIdentifier->setChannels($smsContactIdentifierChannel);

        return [
            'email' => [
                'email',
                'test@nfq.lt',
                'subscribed',
                $emailContactIdentifier
            ],
            'phone' => [
                'phone',
                '868686868',
                'subscribed',
                $smsContactIdentifier
            ],
        ];
    }
}
