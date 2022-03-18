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

namespace NFQ\SyliusOmnisendPlugin\Exception;

use RuntimeException;
use Symfony\Component\Messenger\Exception\RecoverableExceptionInterface;
use Throwable;

use function interface_exists;

if (interface_exists(RecoverableExceptionInterface::class)) {
    class RetryableClientException extends RuntimeException implements RecoverableExceptionInterface
    {
        public function __construct(Throwable $previous = null)
        {
            parent::__construct(
                $previous ? $previous->getMessage() : 'Received retryable client error',
                0,
                $previous
            );
        }
    }
} else {
    class RetryableClientException extends RuntimeException
    {
        public function __construct(Throwable $previous = null)
        {
            parent::__construct(
                $previous ? $previous->getMessage() : 'Received retryable client error',
                0,
                $previous
            );
        }
    }
}
