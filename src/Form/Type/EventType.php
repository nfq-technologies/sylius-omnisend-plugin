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
use Symfony\Component\Validator\Constraints\Count;
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
                    'required' => false,
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.name',
                    'disabled' => true,
                    'required' => false,
                ]
            )->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'sylius.ui.enable',
                    'disabled' => true,
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
                        new Count(
                            [
                                'min' => 1,
                                'groups' => 'sylius'
                            ]
                        )
                    ],
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event): void {
                $object = $event->getData();
                $form = $event->getForm();

                if (null === $object || null === $object->getId()) {
                    $form->remove('eventID');
                    $form->remove('name');
                    $form->remove('enabled');
                } else {
                    $form->add(
                        'systemName',
                        TextType::class,
                        [
                            'disabled' => true,
                            'label' => 'nfq_sylius_omnisend_plugin.ui.event.system_name',
                        ]
                    )->add(
                        'channel',
                        ChannelChoiceType::class,
                        [
                            'disabled' => true,
                            'label' => 'nfq_sylius_omnisend_plugin.ui.event.channel',
                        ]
                    );
                }
            }
        );
    }
}
