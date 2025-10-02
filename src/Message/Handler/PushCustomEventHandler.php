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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Builder\Request\EventBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Exception\SystemEventNotFoundException;
use NFQ\SyliusOmnisendPlugin\Message\Command\PushCustomEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\CustomEventFields;
use Psr\Log\LoggerAwareTrait;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function sprintf;

class PushCustomEventHandler
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly RepositoryInterface $eventRepository,
        private readonly EventBuilderDirectorInterface $eventBuilderDirector,
        private readonly OmnisendClientInterface $omnisendClient,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(PushCustomEvent $message): void
    {
        $violations = $this->validator->validate($message, new CustomEventFields());
        if ($violations->count() > 0) {
            $this->logger?->error(
                'Omnisend custom event {systemName} cannot be pushed.',
                ['errors' => $violations, 'systemName' => $message->getSystemName()],
            );

            throw new UnrecoverableMessageHandlingException(
                sprintf('Custom event %s validation failed', $message->getSystemName()),
            );
        }
        /** @var BaseEvent|null $event */
        $event = $this->eventRepository->findOneBy(['systemName' => $message->getSystemName()]);

        if ($event === null) {
            throw new SystemEventNotFoundException(
                sprintf('Event with systemName %s not found', $message->getSystemName()),
            );
        }

        $this->omnisendClient->postEvent(
            $this->eventBuilderDirector->buildWithFormattedFields(
                $message->getEmail(),
                $message->getSystemName(),
                $message->getFields()
            ),
            $message->getChannelCode()
        );
    }
}
