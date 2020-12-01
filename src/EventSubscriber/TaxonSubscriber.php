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

namespace NFQ\SyliusOmnisendPlugin\EventSubscriber;

use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCategory;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCategory;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class TaxonSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var ChannelContextInterface */
    private $channelContext;

    public function __construct(
        MessageBusInterface $messageBus,
        ChannelContextInterface $channelContext
    ) {
        $this->channelContext = $channelContext;
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.taxon.post_create' => 'onUpdate',
            'sylius.taxon.post_update' => 'onUpdate',
            'sylius.taxon.pre_delete' => 'onDelete',
        ];
    }

    public function onUpdate(ResourceControllerEvent $event): void
    {
        /** @var TaxonInterface $taxon */
        $taxon = $event->getSubject();

        $this->messageBus->dispatch(
            new Envelope(
                new UpdateCategory(
                    $taxon->getCode(),
                    $this->channelContext->getChannel()->getCode()
                )
            )
        );
    }

    public function onDelete(ResourceControllerEvent $event): void
    {
        /** @var TaxonInterface $taxon */
        $taxon = $event->getSubject();

        $this->messageBus->dispatch(
            new Envelope(
                new DeleteCategory(
                    $taxon->getCode(),
                    $this->channelContext->getChannel()->getCode()
                )
            )
        );
    }
}
