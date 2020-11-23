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

use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteProduct;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateProduct;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    /** @var MessageBusInterface */
    private $messageBus;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var LocaleContextInterface */
    private $localeContext;

    public function __construct(
        MessageBusInterface $messageBus,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext
    ) {
        $this->messageBus = $messageBus;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.post_create' => 'onUpdate',
            'sylius.product.post_update' => 'onUpdate',
            'sylius.product.pre_delete' => 'onDelete',
        ];
    }

    public function onUpdate(ResourceControllerEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getSubject();
        /** @var Channel $channel */
        $channel = $this->channelContext->getChannel();

        $this->messageBus->dispatch(
            new Envelope(
                new UpdateProduct(
                    $product->getId(),
                    $channel->getCode(),
                    $this->localeContext->getLocaleCode()
                )
            )
        );
    }

    public function onDelete(ResourceControllerEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getSubject();
        /** @var Channel $channel */
        $channel = $this->channelContext->getChannel();

        $this->messageBus->dispatch(
            new Envelope(
                new DeleteProduct(
                    $product->getCode(),
                    $channel->getCode(),
                )
            )
        );
    }
}
