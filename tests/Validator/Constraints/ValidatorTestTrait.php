<?php

/**
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

namespace Tests\NFQ\SyliusOmnisendPlugin\Validator\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

trait ValidatorTestTrait
{
    /** @var ExecutionContextInterface|MockObject */
    protected $executionContextMock;

    /** @var ConstraintViolationBuilderInterface|MockObject */
    protected $constraintViolationBuilderMock;

    protected function setUp(): void
    {
        $this->executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $this->constraintViolationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);
    }
}
