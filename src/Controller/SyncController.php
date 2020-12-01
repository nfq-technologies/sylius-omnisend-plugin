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

namespace NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Message\Command\CreateBatch;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SyncController extends AbstractController
{
    public const AVAILABLE_TYPES = [
        'products',
        'categories',
    ];

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var MessageBusInterface */
    private $commandBus;

    public function __construct(ChannelContextInterface $channelContext, MessageBusInterface $commandBus)
    {
        $this->channelContext = $channelContext;
        $this->commandBus = $commandBus;
    }

    public function index(): Response
    {
        return $this->render('@NFQSyliusOmnisendPlugin/Sync/index.html.twig', ['types' => self::AVAILABLE_TYPES]);
    }

    public function sync(Request $request): RedirectResponse
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $channelCode = $channel->getCode();
        /** @var LocaleInterface $locale */
        $locale = $channel->getDefaultLocale();
        $localeCode = $locale->getCode();
        $type = $request->get('type');
        $batchSize = CreateBatch::DEFAULT_BATCH_SIZE;

        if (!in_array($type, self::AVAILABLE_TYPES, true)) {
            throw new NotFoundHttpException('Provided type is invalid');
        }

        $this->commandBus->dispatch(
            new Envelope(
                new CreateBatch(
                    $type,
                    (string) $channelCode,
                    (string) $localeCode,
                    $batchSize
                )
            )
        );
        $this->addFlash('success', 'Batch import started');

        return $this->redirectToRoute('nfq_sylius_omnisend_sync_batch_index');
    }
}
