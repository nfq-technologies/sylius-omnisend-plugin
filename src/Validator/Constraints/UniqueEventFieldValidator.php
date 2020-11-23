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

use Doctrine\Common\Collections\Collection;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class UniqueEventFieldValidator extends ConstraintValidator
{
    /** @var Collection $value */
    public function validate($value, Constraint $constraint): void
    {
        /** @var UniqueEventField $constraint */
        Assert::isInstanceOf($constraint, UniqueEventField::class);

        $codes = [];
        $iteration = 0;

        /** @var EventField $field */
        foreach ($value as $field) {
            $code = $field->getSystemName();

            if (isset($codes[$code])) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath("[{$iteration}].code")
                    ->addViolation();
            } else {
                $codes[$code] = $field;
            }
            $iteration++;
        }
    }
}
