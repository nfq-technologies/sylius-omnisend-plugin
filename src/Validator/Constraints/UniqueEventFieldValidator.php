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

use Doctrine\Common\Collections\Collection;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class UniqueEventFieldValidator extends ConstraintValidator
{
    /** @param Collection $value */
    public function validate(mixed $value, Constraint $constraint): void
    {
        Assert::isInstanceOf($constraint, UniqueEventField::class);

        $codes = [];
        $iteration = 0;

        /** @var EventField $field */
        foreach ($value as $field) {
            $code = $field->getSystemName();

            if (isset($codes[$code])) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath("[{$iteration}].systemName")
                    ->addViolation();
            } else {
                $codes[$code] = $field;
            }
            ++$iteration;
        }
    }
}
