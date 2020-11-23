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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock;

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\CreateEvent;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Order;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Product;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\BatchSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CategorySuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\OrderSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ProductSuccess;

class OmnisendClientMock implements OmnisendClientInterface
{
    /** @var OmnisendClient */
    private $client;

    public function __construct(OmnisendClient $client)
    {
        $this->client = $client;
    }

    public function postContact(Contact $contact, ?string $channelCode): ?object
    {
        $this->client->postContact($contact, $channelCode);

        return (new ContactSuccess())->setContactID('testId');
    }

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): void
    {
        $this->client->patchContact($contactId, $contact, $channelCode);
    }

    public function postCategory(Category $category, ?string $channelCode): ?object
    {
        $this->client->postCategory($category, $channelCode);

        return new CategorySuccess();
    }

    public function putCategory(Category $category, ?string $channelCode): ?object
    {
        $this->client->putCategory($category, $channelCode);

        return new CategorySuccess();
    }

    public function deleteCategory(string $categoryId, ?string $channelCode): ?object
    {
        $this->client->deleteCategory($categoryId, $channelCode);

        return new CategorySuccess();
    }

    public function postBatch(Batch $batch, ?string $channelCode): ?object
    {
        $this->client->postBatch($batch, $channelCode);

        return new BatchSuccess();
    }

    public function postCart(Cart $cart, ?string $channelCode): ?object
    {
        $this->client->postCart($cart, $channelCode);

        return (new CartSuccess())->setCartID('1111');
    }

    public function patchCart(Cart $cart, ?string $channelCode): ?object
    {
        $this->client->patchCart($cart, $channelCode);

        return (new CartSuccess())->setCartID('1111');
    }

    public function deleteCart(string $cartId, ?string $channelCode): ?object
    {
        $this->client->deleteCart($cartId, $channelCode);

        return (new CartSuccess())->setCartID('1111');
    }

    public function postOrder(Order $order, ?string $channelCode): ?object
    {
        $this->client->postOrder($order, $channelCode);

        return (new OrderSuccess())->setOrderID('1111');
    }

    public function patchOrder(Order $order, ?string $channelCode): ?object
    {
        $this->client->patchOrder($order, $channelCode);

        return (new OrderSuccess())->setOrderID('1111');
    }

    public function putOrder(Order $order, ?string $channelCode): ?object
    {
        $this->client->putOrder($order, $channelCode);

        return (new OrderSuccess())->setOrderID('1111');
    }

    public function deleteOrder(string $orderId, ?string $channelCode): ?object
    {
        $this->client->deleteOrder($orderId, $channelCode);

        return (new OrderSuccess())->setOrderID('1111');
    }

    public function postProduct(Product $order, ?string $channelCode): ?object
    {
        $this->client->postProduct($order, $channelCode);

        return (new ProductSuccess())->setProductID('1111');
    }

    public function putProduct(Product $order, ?string $channelCode): ?object
    {
        $this->client->putProduct($order, $channelCode);

        return (new ProductSuccess())->setProductID('1111');
    }

    public function deleteProduct(string $orderId, ?string $channelCode): ?object
    {
        $this->client->deleteProduct($orderId, $channelCode);

        return (new ProductSuccess())->setProductID('1111');
    }

    public function postEvent(CreateEvent $event, ?string $channelCode): ?object
    {
    }

    public function getEvents(?string $channelCode): ?array
    {
    }
}
