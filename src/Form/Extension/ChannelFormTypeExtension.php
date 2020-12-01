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

namespace NFQ\SyliusOmnisendPlugin\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ChannelFormTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'omnisendTrackingKey',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.tracking_key',
                ]
            )->add(
                'omnisendApiKey',
                TextType::class,
                [
                    'label' => 'nfq_sylius_omnisend_plugin.ui.api_key',
                ]
            );
    }

    public function getExtendedTypes(): array
    {
        return [ChannelType::class];
    }
}
