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

namespace NFQ\SyliusOmnisendPlugin\Builder\Request;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event as BaseEvent;

class EventBuilderDirector implements EventBuilderDirectorInterface
{
    /** @var EventBuilderInterface */
    private $builder;

    /**
     * EventBuilder constructor.
     * @param EventBuilderInterface $builder
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
}
