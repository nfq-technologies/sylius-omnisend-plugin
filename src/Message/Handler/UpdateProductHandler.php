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

namespace NFQ\SyliusOmnisendPlugin\Message\Handler;

use DateTime;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ProductBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateProduct;
use NFQ\SyliusOmnisendPlugin\Model\ProductInterface;
use Psr\Log\LoggerAwareTrait;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

class UpdateProductHandler
{
    use LoggerAwareTrait;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    /** @var ProductBuilderDirectorInterface */
    private $productBuilderDirector;

    public function __construct(
        OmnisendClientInterface $omnisendClient,
        ProductRepositoryInterface $productRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductBuilderDirectorInterface $productBuilderDirector
    ) {
        $this->omnisendClient = $omnisendClient;
        $this->productRepository = $productRepository;
        $this->channelRepository = $channelRepository;
        $this->productBuilderDirector = $productBuilderDirector;
    }

    public function __invoke(UpdateProduct $message): void
    {
        if (null === $message->getProductId()) {
            return;
        }

        /** @var ProductInterface|null $product */
        $product = $this->productRepository->find($message->getProductId());

        if (null !== $product) {
            /** @var ChannelInterface $channel */
            $channel = $this->channelRepository->findOneBy(['code' => $message->getChannelCode()]);

            try {
                if (
                    $product->isPushedToOmnisend()
                    || null !== $this->omnisendClient->getProduct($product->getCode(), $message->getChannelCode())
                ) {
                    $response = $this->omnisendClient->putProduct(
                        $this->productBuilderDirector->build($product, $channel, $message->getLocaleCode()),
                        $message->getChannelCode()
                    );
                } else {
                    $response = $this->omnisendClient->postProduct(
                        $this->productBuilderDirector->build($product, $channel, $message->getLocaleCode()),
                        $message->getChannelCode()
                    );
                }

                if (null !== $response) {
                    $product->setPushedToOmnisend(new DateTime());
                    $this->productRepository->add($product);
                }
            } catch (\Throwable $e) {
                if (null !== $this->logger) {
                    $this->logger->error('Error occurred while pushing product to Omnisend.', ['error' => $e->getMessage()]);
                }
            }
        }
    }
}
