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
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.name',
                ]
            )->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'sylius.ui.enable',
                ]
            )->add(
                'code',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.system_name',
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
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label' => 'nfq_sylius_omnisend_plugin.ui.event.fields',
                    'constraints' => [
                        new UniqueEventField(['groups' => 'sylius']),
                    ],
                ]
            );
    }
}
