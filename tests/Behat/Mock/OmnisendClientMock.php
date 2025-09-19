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

namespace Tests\NFQ\SyliusOmnisendPlugin\Behat\Mock;

use Doctrine\Common\Collections\ArrayCollection;
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
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccessList;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\EventSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\OrderSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ProductSuccess;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use NFQ\SyliusOmnisendPlugin\Model\EventField;

class OmnisendClientMock implements OmnisendClientInterface
{
    /** @var OmnisendClient */
    private $client;

    public function __construct(OmnisendClient $client)
    {
        $this->client = $client;
    }

    public function postContact(Contact $contact, ?string $channelCode): ?ContactSuccess
    {
        $this->client->postContact($contact, $channelCode);

        return (new ContactSuccess())->setContactID('testId');
    }

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): ?ContactSuccess
    {
        return $this->client->patchContact($contactId, $contact, $channelCode);
    }

    public function getCategory(?string $category, ?string $channelCode): ?object
    {
        $this->client->getCategory($category, $channelCode);

        return null;
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

    public function deleteCart(?string $cartId, ?string $channelCode): ?object
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

    public function getProduct(?string $productId, ?string $channelCode): ?object
    {
        $this->client->getProduct($productId, $channelCode);

        return null;
    }

    public function postEvent(CreateEvent $event, ?string $channelCode): ?object
    {
        $this->client->postEvent($event, $channelCode);
        return new EventSuccess();
    }

    public function getContactByPhone(string $phone, ?string $channelCode): ?ContactSuccessList
    {
        $this->client->getContactByPhone($phone, $channelCode);

        return null;
    }
    
    public function getContactByEmail(string $email, ?string $channelCode): ?ContactSuccessList
    {
        $this->client->getContactByEmail($email, $channelCode);

        return null;
    }

    public function getEvents(?string $channelCode): ?array
    {
        $this->client->getEvents($channelCode);
        $event = new Event();
        $eventField = new EventField();
        $eventField->setSystemName('int');
        $eventField->setType('int');
        $event->setSystemName('testName');
        $event->setEnabled(true);
        $event->setName('test Name');
        $event->setFields(new ArrayCollection([$eventField]));

        return [
            $event
        ];
    }

    public function getBatch(string $batchId, ?string $channelCode): ?object
    {
        $this->client->getBatch($batchId, $channelCode);
        $batch = new BatchSuccess();
        $batch->setBatchID($batchId);

        return $batch;
    }
}
