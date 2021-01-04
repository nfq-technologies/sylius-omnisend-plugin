<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) Nfq Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
