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

namespace NFQ\SyliusOmnisendPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueEventField extends Constraint
{
    /** @var string */
    public $message = 'Duplicated system name field';

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
