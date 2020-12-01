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

namespace Tests\NFQ\SyliusOmnisendPlugin\Validator\Constraints;

use NFQ\SyliusOmnisendPlugin\Model\EventField;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\UniqueEventField;
use NFQ\SyliusOmnisendPlugin\Validator\Constraints\UniqueEventFieldValidator;
use PHPUnit\Framework\TestCase;

class UniqueEventFieldValidatorTest extends TestCase
{
    use ValidatorTestTrait {
        setUp as setUpValidatorMock;
    }

    /** @var UniqueEventFieldValidator */
    private $validator;

    protected function setUp(): void
    {
        $this->setUpValidatorMock();
        $this->validator = new UniqueEventFieldValidator();
    }

    /** @dataProvider data */
    public function testIfValidatesUniqueParams(array $field, array $errorPath)
    {
        if (count($errorPath)) {
            $this->executionContextMock
                ->expects($this->exactly(count($errorPath)))
                ->method('buildViolation')
                ->with('Duplicated system name field')
                ->willReturn($this->constraintViolationBuilderMock);

            $this->constraintViolationBuilderMock
                ->expects($this->exactly(count($errorPath)))
                ->method('atPath')
                ->with($this->logicalOr(...$errorPath))
                ->willReturn($this->constraintViolationBuilderMock);
        } else {
            $this->executionContextMock
                ->expects($this->never())
                ->method('buildViolation')
                ->willReturn($this->constraintViolationBuilderMock);
        }

        $this->validator->initialize($this->executionContextMock);
        $this->validator->validate($field, new UniqueEventField());
    }

    public function data(): array
    {
        $productAttribute1 = new EventField();
        $productAttribute2 = new EventField();
        $productAttribute1->setSystemName('test1');
        $productAttribute2->setSystemName('test2');

        return [
            'empty list' => [
                [],
                [],
            ],
            'one attribute' => [
                [$productAttribute1],
                [],
            ],
            'duplicated item at [1]' => [
                [
                    $productAttribute1,
                    $productAttribute1,
                ],
                ['[1].systemName'],
            ],
            'different attributes' => [
                [
                    $productAttribute1,
                    $productAttribute2,
                ],
                [],
            ],
            'same 3 attributes displays 2 duplicated errors' => [
                [
                    $productAttribute2,
                    $productAttribute2,
                    $productAttribute2,
                ],
                [
                    '[1].systemName',
                    '[2].systemName',
                ],
            ],
            'different groups display different duplicated errors' => [
                [
                    $productAttribute2,
                    $productAttribute2,
                    $productAttribute2,
                    $productAttribute1,
                    $productAttribute1,
                ],
                [
                    '[1].systemName',
                    '[2].systemName',
                    '[4].systemName',
                ],
            ],
        ];
    }
}
