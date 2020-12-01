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

namespace NFQ\SyliusOmnisendPlugin\Validator\Constraints;

use NFQ\SyliusOmnisendPlugin\Event\CustomEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomEventFields extends Constraint
{
    public const INVALID_FIELD_VALUE = 'Invalid field %field% value %value%, value type should by %type%';
    public const INVALID_SYSTEM_NAME = 'Provided system name %systemName% does not exist. Please create a new custom event in admin area.';
    public const INVALID_FIELD_NAME = 'Provided field name %systemName% does not exist. Please add a new field in admin area.';
    public const INVALID_FIELD_TYPE = 'Provided field %field% type %type% is not defined.';
}
