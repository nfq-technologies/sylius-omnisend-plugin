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

use NFQ\SyliusOmnisendPlugin\Message\Command\PushCustomEvent;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use NFQ\SyliusOmnisendPlugin\Model\EventField;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintValidator;

class CustomEventFieldsValidator extends ConstraintValidator
{
    /** @var RepositoryInterface */
    private $eventRepository;

    public function __construct(RepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /** @var PushCustomEvent */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Event|null $baseEvent */
        $baseEvent = $this->eventRepository->findOneBy(['systemName' => $value->getSystemName()]);

        if (null === $baseEvent) {
            $this->context
                ->buildViolation(CustomEventFields::INVALID_SYSTEM_NAME)
                ->setParameter('%systemName%', $value->getSystemName())
                ->addViolation();

            return;
        }

        foreach ($value->getFields() as $fieldName => $fieldValue) {
            $field = $baseEvent->getFieldBySystemName($fieldName);

            if (null === $field) {
                $this->context
                    ->buildViolation(CustomEventFields::INVALID_FIELD_NAME)
                    ->setParameter('%systemName%', $fieldName)
                    ->addViolation();

                continue;
            }

            $fieldConstraint = $this->getFieldConstraint($field);
            if (null !== $fieldConstraint) {
                $validations = $this->context->getValidator()->validate($fieldValue, $fieldConstraint);
                if ($validations->count() > 0) {
                    $this->context->buildViolation(CustomEventFields::INVALID_FIELD_VALUE)
                        ->setParameter('%field%', $fieldName)
                        ->setParameter('%value%', $fieldValue)
                        ->setParameter('%type%', (string) $field->getType())
                        ->addViolation();
                }
            } else {
                $this->context
                    ->buildViolation(CustomEventFields::INVALID_FIELD_TYPE)
                    ->setParameter('%field%', $fieldName)
                    ->setParameter('%type%', (string) $field->getType())
                    ->addViolation();
            }
        }
    }

    private function getFieldConstraint(EventField $field): ?Constraint
    {
        $fieldConstraint = null;

        switch ($field->getType()) {
            case EventField::TYPE_BOOL:
                $fieldConstraint = new Type(['type' => 'bool']);

                break;
            case EventField::TYPE_INT:
                $fieldConstraint = new Type(['type' => 'integer']);

                break;
            case EventField::TYPE_FLOAT:
                $fieldConstraint = new Type(['type' => 'float']);

                break;
            case EventField::TYPE_STRING:
                $fieldConstraint = new Type(['type' => 'string']);

                break;
            case EventField::TYPE_EMAIL:
                $fieldConstraint = new Email();

                break;
            case EventField::TYPE_URL:
                $fieldConstraint = new Url();

                break;
            case EventField::TYPE_DATE:
                $fieldConstraint = new Date();

                break;
            case EventField::TYPE_DATETIME:
                $fieldConstraint = new DateTime(['format' => \DateTime::ISO8601]);

                break;
        }

        return $fieldConstraint;
    }
}
