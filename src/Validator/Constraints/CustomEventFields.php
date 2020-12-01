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

namespace NFQ\SyliusOmnisendPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class CustomEventFields extends Constraint
{
    public const INVALID_FIELD_VALUE = 'Invalid field %field% value %value%, value type should by %type%';

    public const INVALID_SYSTEM_NAME = 'Provided system name %systemName% does not exist. Please create a new custom event in admin area.';

    public const INVALID_FIELD_NAME = 'Provided field name %systemName% does not exist. Please add a new field in admin area.';

    public const INVALID_FIELD_TYPE = 'Provided field %field% type %type% is not defined.';
}
