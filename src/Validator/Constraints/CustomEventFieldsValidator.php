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

    /** @var PushCustomEvent $value */
    public function validate($value, Constraint $constraint): void
    {
        /** @var Event $baseEvent */
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
                        ->setParameter('%type%', $field->getType())
                        ->addViolation();
                }
            } else {
                var_export($field->getSystemName());
                var_export($field->getType());
                $this->context
                    ->buildViolation(CustomEventFields::INVALID_FIELD_TYPE)
                    ->setParameter('%field%', $fieldName)
                    ->setParameter('%type%', $field->getType())
                    ->addViolation();
            }
        }
    }

    private function getFieldConstraint(?EventField $field): ?Constraint
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
