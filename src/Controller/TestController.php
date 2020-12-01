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
                    'datetime' => "2010-12-12T12:12:12+00:00",
                    'date' => "2019-02-10",
                    'string' => "asdfasdfasd",
                    'float' => 10.2,
                    'email' => "email@email.lt",
                    'url' => "http://asdasd.lt",
                ]
            )
        );

        return new Response('Omnisend');
    }
}
