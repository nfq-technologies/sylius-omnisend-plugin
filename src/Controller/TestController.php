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

namespace NFQ\SyliusOmnisendPlugin\Controller;

use NFQ\SyliusOmnisendPlugin\Event\CustomEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher
    ) {
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(): Response
    {
        $this->dispatcher->dispatch(
            new CustomEvent(
                'email@nfq.lt',
                'testName',
                'FASHION_WEB',
                [
                    'integer' => 1,
                    'bool' => true,
                    'datetime' => '2010-12-12T12:12:12+00:00',
                    'date' => '2019-02-10',
                    'string' => 'asdfasdfasd',
                    'float' => 10.2,
                    'email' => 'email@email.lt',
                    'url' => 'http://asdasd.lt',
                ]
            )
        );

        return new Response('Omnisend');
    }
}
