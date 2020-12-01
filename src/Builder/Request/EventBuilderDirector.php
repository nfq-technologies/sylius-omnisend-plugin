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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;

class EventBuilderDirector implements EventBuilderDirectorInterface
{
    /** @var EventBuilderInterface */
    private $builder;

    /**
     * EventBuilder constructor.
     */
    public function __construct(EventBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function build(BaseEvent $event): CreateEvent
    {
        $this->builder->createEvent();
        $this->builder->addMainData($event);
        $this->builder->addFields($event);

        return $this->builder->getEvent();
    }

    public function buildWithFormattedFields(string $email, string $systemName, array $fields): CreateEvent
    {
        $this->builder->createEvent();
        $this->builder->addEmail($email);
        $this->builder->addSystemName($systemName);
        $this->builder->addFormattedFields($fields);

        return $this->builder->getEvent();
    }
}
