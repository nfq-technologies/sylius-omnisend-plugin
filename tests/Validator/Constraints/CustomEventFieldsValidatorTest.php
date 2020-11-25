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

namespace Tests\NFQ\SyliusOmnisendPlugin\Validator\Constraints;

use NFQ\SyliusOmnisendPlugin\Message\Command\PushCustomEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\CustomEventFields;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\CustomEventFieldsValidator;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomEventFieldsValidatorTest extends WebTestCase
{
    use ValidatorTestTrait {
        setUp as setUpValidatorMock;
    }

    /** @var CustomEventFieldsValidator */
    private $validator;

    /** @var RepositoryInterface */
    private $repository;

    /** @var Event */
    private $event;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->setUpValidatorMock();
        $this->repository = $this->createMock(RepositoryInterface::class);
        $this->validator = new CustomEventFieldsValidator($this->repository);
        $event = new Event();
        $event->setSystemName('testName');
        $eventField1 = new EventField();
        $eventField1->setType(EventField::TYPE_INT);
        $eventField1->setSystemName('int');
        $eventField2 = new EventField();
        $eventField2->setType(EventField::TYPE_DATETIME);
        $eventField2->setSystemName('dateTime');
        $eventField3 = new EventField();
        $eventField3->setType(EventField::TYPE_DATE);
        $eventField3->setSystemName('date');
        $eventField4 = new EventField();
        $eventField4->setType(EventField::TYPE_URL);
        $eventField4->setSystemName('url');
        $eventField5 = new EventField();
        $eventField5->setType(EventField::TYPE_EMAIL);
        $eventField5->setSystemName('email');
        $eventField6 = new EventField();
        $eventField6->setType(EventField::TYPE_BOOL);
        $eventField6->setSystemName('bool');
        $eventField7 = new EventField();
        $eventField7->setType(EventField::TYPE_STRING);
        $eventField7->setSystemName('string');
        $eventField8 = new EventField();
        $eventField8->setType(EventField::TYPE_FLOAT);
        $eventField8->setSystemName('float');
        $event->addField($eventField1);
        $event->addField($eventField2);
        $event->addField($eventField3);
        $event->addField($eventField4);
        $event->addField($eventField5);
        $event->addField($eventField6);
        $event->addField($eventField7);
        $event->addField($eventField8);
        $this->event = $event;
    }

    public function testIfValidatesFieldWhichDoesNotExist()
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->event);

        $this->executionContextMock
            ->expects($this->any())
            ->method('buildViolation')
            ->with(CustomEventFields::INVALID_FIELD_NAME)
            ->willReturn($this->constraintViolationBuilderMock);
        $this->constraintViolationBuilderMock
            ->expects($this->any())
            ->method('setParameter')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->validator->initialize($this->executionContextMock);
        $data = new PushCustomEvent(
            'email@email.lt',
            'testName',
            [
                'field' => 'value',
            ]
            , 'en'
        );
        $this->validator->validate($data, new CustomEventFields());
    }

    public function testIfValidatesIfEventDoesNotExist()
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->executionContextMock
            ->expects($this->any())
            ->method('buildViolation')
            ->with(CustomEventFields::INVALID_SYSTEM_NAME)
            ->willReturn($this->constraintViolationBuilderMock);
        $this->constraintViolationBuilderMock
            ->expects($this->any())
            ->method('setParameter')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->validator->initialize($this->executionContextMock);
        $data = new PushCustomEvent(
            'email@email.lt',
            'testName',
            [
                'field' => 'value',
            ]
            , 'en'
        );
        $this->validator->validate($data, new CustomEventFields());
    }

    /** @dataProvider invalidFieldValues */
    public function testIfValidatesInvalidFieldValue(array $fields, bool $valid)
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->event);

        $this->executionContextMock
            ->expects($this->any())
            ->method('getValidator')
            ->willReturn(self::$container->get('validator'));
        if (!$valid) {
            $this->executionContextMock
                ->expects($this->atLeastOnce())
                ->method('buildViolation')
                ->with(CustomEventFields::INVALID_FIELD_VALUE)
                ->willReturn($this->constraintViolationBuilderMock);
            $this->constraintViolationBuilderMock
                ->expects($this->any())
                ->method('setParameter')
                ->willReturn($this->constraintViolationBuilderMock);
        } else {
            $this->executionContextMock
                ->expects($this->never())
                ->method('buildViolation')
                ->with(CustomEventFields::INVALID_FIELD_VALUE)
                ->willReturn($this->constraintViolationBuilderMock);
        }

        $this->validator->initialize($this->executionContextMock);
        $data = new PushCustomEvent(
            'email@email.lt',
            'testName',
            $fields,
            'en'
        );
        $this->validator->validate($data, new CustomEventFields());
    }

    public function invalidFieldValues()
    {
        return [
            'correctFields' =>
                [
                    [
                        'int' => 1,
                        'bool' => true,
                        'dateTime' => "2010-12-12T12:12:12+00:00",
                        'date' => "2019-02-10",
                        'string' => "asdfasdfasd",
                        'float' => 10.2,
                        'email' => "email@email.lt",
                        'url' => "http://asdasd.lt",
                    ],
                    true
                ],
            'incorrect' =>
                [
                    [
                        'int' => "1",
                    ],
                    false,
                ],
            'incorrect bool' =>
                [
                    [
                        'bool' => 'sss',
                    ],
                    false,
                ],
            'incorrect dateTime' =>
                [
                    [
                        'dateTime' => 'sss',
                    ],
                    false,
                ],
            'incorrect date' =>
                [
                    [
                        'date' => 'sss',
                    ],
                    false,
                ],
            'incorrect string' =>
                [
                    [
                        'string' => 1,
                    ],
                    false,
                ],
            'incorrect float' =>
                [
                    [
                        'float' => 1,
                    ],
                    false,
                ],
            'incorrect email' =>
                [
                    [
                        'email' => 'email',
                    ],
                    false,
                ],
            'incorrect url' =>
                [
                    [
                        'url' => 'url',
                    ],
                    false,
                ],
        ];
    }
}
