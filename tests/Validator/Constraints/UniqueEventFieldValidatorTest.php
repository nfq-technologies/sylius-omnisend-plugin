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

    /**
     * @var UniqueEventFieldValidator
     */
    private $validator;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->setUpValidatorMock();
        $this->validator = new UniqueEventFieldValidator();
    }

    /**
     * @dataProvider data
     *
     * @param array $field
     * @param array $errorPath
     *
     * @return void
     */
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

    /**
     * @return array
     */
    public function data(): array
    {
        $productAttribute1 = new EventField();
        $productAttribute2 = new EventField();
        $productAttribute1->setCode('test1');
        $productAttribute2->setCode('test2');

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
                ['[1].code'],
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
                    '[1].code',
                    '[2].code',
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
                    '[1].code',
                    '[2].code',
                    '[4].code',
                ],
            ],
        ];
    }
}
