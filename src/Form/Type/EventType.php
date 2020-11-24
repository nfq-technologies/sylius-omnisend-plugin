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

use NFQ\SyliusOmnisendPlugin\Validator\Constraints\UniqueEventField;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'eventID',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.event_id',
                    'disabled' => true,
                    'attr' => [
                        'readonly' => 'readonly',
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.name',
                    'disabled' => true,
                    'attr' => [
                        'readonly' => 'readonly',
                    ],
                    'required' => false,
                ]
            )->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'sylius.ui.enable',
                    'disabled' => true,
                    'attr' => [
                        'readonly' => 'readonly',
                    ],
                    'required' => false,
                ]
            )->add(
                'systemName',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.system_name',
                    'constraints' => [new NotBlank(['groups' => ['sylius']])],
                ]
            )->add(
                'channel',
                ChannelChoiceType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.channel',
                ]
            )->add(
                'fields',
                CollectionType::class,
                [
                    'entry_type' => EventFieldType::class,
                    'allow_add' => true,
                    'allow_delete' => false,
                    'by_reference' => false,
                    'entry_options' => ['attr' => ['class' => 'ui attached segment']],
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.fields',
                    'constraints' => [
                        new UniqueEventField(['groups' => 'sylius']),
                    ],
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $object = $event->getData();
                $form = $event->getForm();

                if (!$object || null === $object->getId()) {
                    $form->remove('eventID');
                    $form->remove('name');
                    $form->remove('enabled');
                }
            }
        );

    }
}
