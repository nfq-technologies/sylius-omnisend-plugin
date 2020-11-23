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

namespace NFQ\SyliusOmnisendPlugin\Form\Type;

use NFQ\SyliusOmnisendPlugin\Model\EventField;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EventFieldType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'required',
                CheckboxType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event_field.required',
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event_field.name',
                ]
            )->add(
                'code',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event_field.system_name',
                ]
            )->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => array_combine(EventField::TYPES, EventField::TYPES),
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event_field.type',
                ]
            );
    }
}
