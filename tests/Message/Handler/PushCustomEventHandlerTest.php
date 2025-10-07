<?php

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use NFQ\SyliusOmnisendPlugin\Builder\Request\EventBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\EventSuccess;
use NFQ\SyliusOmnisendPlugin\Exception\SystemEventNotFoundException;
use NFQ\SyliusOmnisendPlugin\Message\Command\PushCustomEvent;
use NFQ\SyliusOmnisendPlugin\Message\Handler\PushCustomEventHandler;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\CustomEventFields;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function call_user_func;

class PushCustomEventHandlerTest extends TestCase
{
    const SYSTEM_NAME = 'LoyaltyCardNotification';
    const SMS_CHANNEL_CODE = 'sms';
    const EMAIL = 'test@example.com';
    const FIELDS = ['type' => 'physical'];

    private PushCustomEventHandler $handler;

    private RepositoryInterface&MockObject $eventRepository;

    private EventBuilderDirectorInterface&MockObject $eventBuilderDirector;

    private OmnisendClientInterface&MockObject $omnisendClient;

    private ValidatorInterface&MockObject $validator;

    protected function setUp(): void
    {
        $this->eventRepository = $this->createMock(RepositoryInterface::class);
        $this->eventBuilderDirector = $this->createMock(EventBuilderDirectorInterface::class);
        $this->omnisendClient = $this->createMock(OmnisendClientInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->handler = new PushCustomEventHandler(
            $this->eventRepository,
            $this->eventBuilderDirector,
            $this->omnisendClient,
            $this->validator,
        );
    }

    public function testValidationFailureStopsRetry(): void
    {
        $message = $this->createCustomEvent();
        $this->givenValidationFails($message);

        $this->expectException(UnrecoverableMessageHandlingException::class);
        call_user_func($this->handler, $message);
    }

    private function createCustomEvent(): PushCustomEvent
    {
        return new PushCustomEvent(self::EMAIL, self::SYSTEM_NAME, self::FIELDS, self::SMS_CHANNEL_CODE);
    }

    private function givenValidationFails(PushCustomEvent $message): void
    {
        $this->validator->expects(self::once())
            ->method('validate')
            ->with($message, $this->isInstanceOf(CustomEventFields::class))
            ->willReturn($this->createConfiguredMock(ConstraintViolationListInterface::class, ['count' => 1]));
    }

    public function testNotFoundEventThrowsException(): void
    {
        $message = $this->createCustomEvent();
        $this->givenValidationSucceeds($message);
        $this->givenSystemEventIsNotFound();

        $this->expectException(SystemEventNotFoundException::class);
        call_user_func($this->handler, $message);
    }

    private function givenValidationSucceeds(PushCustomEvent $message): void
    {
        $this->validator->expects(self::once())
            ->method('validate')
            ->with($message, $this->isInstanceOf(CustomEventFields::class))
            ->willReturn($this->createConfiguredMock(ConstraintViolationListInterface::class, ['count' => 0]));
    }

    private function givenSystemEventIsNotFound(): void
    {
        $this->eventRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['systemName' => self::SYSTEM_NAME])
            ->willReturn(null);
    }

    public function testEventGetsPushed(): void
    {
        $message = $this->createCustomEvent();
        $this->givenValidationSucceeds($message);
        $this->givenSystemEventIsFound();

        $createEvent = $this->createMock(CreateEvent::class);
        $this->omnisendClient->expects(self::once())
            ->method('postEvent')
            ->with($createEvent, self::SMS_CHANNEL_CODE)
            ->willReturn($this->createMock(EventSuccess::class));

        $this->eventBuilderDirector->expects(self::once())
            ->method('buildWithFormattedFields')
            ->with(self::EMAIL, self::SYSTEM_NAME, self::FIELDS)
            ->willReturn($createEvent);

        call_user_func($this->handler, $message);
    }

    private function givenSystemEventIsFound(): void
    {
        $this->eventRepository->expects(self::once())
            ->method('findOneBy')
            ->with(['systemName' => self::SYSTEM_NAME])
            ->willReturn($this->createMock(BaseEvent::class));
    }
}
