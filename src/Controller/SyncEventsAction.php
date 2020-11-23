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

namespace NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Message\Command\SyncEvents;
use NFQ\SyliusOmnisendPlugin\Model\ChannelOmnisendTrackingAwareInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SyncEventsAction extends AbstractController
{
    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var MessageBusInterface */
    private $messageBus;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        ChannelContextInterface $channelContext,
        MessageBusInterface $messageBus,
        SessionInterface $session
    ) {
        $this->session = $session;
        $this->channelContext = $channelContext;
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        /** @var ChannelOmnisendTrackingAwareInterface $channel */
        $channel = $this->channelContext->getChannel();

        if ($channel->getOmnisendApiKey()) {
            $this->messageBus->dispatch(
                new Envelope(
                    new SyncEvents($channel->getCode())
                )
            );
            $this->session->getFlashBag()->add(
                'success',
                'nfq_sylius_omnisend_plugin.ui.syncs_started'
            );
        } else {
            $this->session->getFlashBag()->add(
                'success',
                'nfq_sylius_omnisend_plugin.ui.syncs_failed'
            );
        }

        return $this->redirectToRoute('nfq_sylius_omnisend_plugin_admin_event_index');
    }
}
