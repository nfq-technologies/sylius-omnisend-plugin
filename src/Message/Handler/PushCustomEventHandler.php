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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Builder\Request\EventBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\PushCustomEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\CustomEventFields;
use Psr\Log\LoggerAwareTrait;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PushCustomEventHandler
{
    use LoggerAwareTrait;

    /** @var RepositoryInterface */
    private $eventRepository;

    /** @var EventBuilderDirectorInterface */
    private $eventBuilderDirector;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(
        RepositoryInterface $eventRepository,
        EventBuilderDirectorInterface $eventBuilderDirector,
        OmnisendClientInterface $omnisendClient,
        ValidatorInterface $validator
    ) {
        $this->eventRepository = $eventRepository;
        $this->eventBuilderDirector = $eventBuilderDirector;
        $this->omnisendClient = $omnisendClient;
        $this->validator = $validator;
    }

    public function __invoke(PushCustomEvent $message): void
    {
        $violations = $this->validator->validate($message, new CustomEventFields());
        if ($violations->count() > 0) {
            if (null !== $this->logger) {
                $this->logger->error('Omnisend custom event cannot be pushed.', ['errors' => $violations]);
            }
            return;
        }
        /** @var BaseEvent|null $event */
        $event = $this->eventRepository->findOneBy(['systemName' => $message->getSystemName()]);

        if (null === $event) {
            return;
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
