<?php

/*
 * This file is part of the NFQ package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace NFQ\SyliusOmnisendPlugin\Client;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Product;

interface OmnisendClientInterface
{
    public function postContact(Contact $contact, ?string $channelCode): ?object;

    public function getContactByEmail(?string $email, ?string $channelCode): ?object;

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): ?object;

    public function postCategory(Category $category, ?string $channelCode): ?object;

    public function getCategory(?string $category, ?string $channelCode): ?object;

    public function putCategory(Category $category, ?string $channelCode): ?object;

    public function deleteCategory(string $categoryId, ?string $channelCode): ?object;

    public function postCart(Cart $cart, ?string $channelCode): ?object;

    public function patchCart(Cart $cart, ?string $channelCode): ?object;

    public function deleteCart(?string $cartId, ?string $channelCode): ?object;

    public function postOrder(Order $order, ?string $channelCode): ?object;

    public function patchOrder(Order $order, ?string $channelCode): ?object;

    public function putOrder(Order $order, ?string $channelCode): ?object;

    public function getProduct(?string $productId, ?string $channelCode): ?object;

    public function postProduct(Product $product, ?string $channelCode): ?object;

    public function putProduct(Product $product, ?string $channelCode): ?object;

    public function deleteProduct(string $productId, ?string $channelCode): ?object;

    public function deleteOrder(string $orderId, ?string $channelCode): ?object;

    public function postBatch(Batch $batch, ?string $channelCode): ?object;

    public function postEvent(CreateEvent $event, ?string $channelCode): ?object;

    public function getEvents(?string $channelCode): ?array;
}
