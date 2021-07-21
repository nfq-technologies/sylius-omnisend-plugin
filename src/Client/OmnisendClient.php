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

namespace NFQ\SyliusOmnisendPlugin\Client;

use Http\Client\Exception\HttpException;
use JMS\Serializer\SerializerInterface;
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
use NFQ\SyliusOmnisendPlugin\HttpClient\ClientFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Model\Event;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Throwable;

class OmnisendClient implements LoggerAwareInterface, OmnisendClientInterface
{
    use LoggerAwareTrait;

    private const API_VERSION = 'v3';

    private const URL_PATH_CONTACTS = '/contacts';

    private const URL_PATH_CATEGORIES = '/categories';

    private const URL_PATH_CARTS = '/carts';

    private const URL_PATH_ORDERS = '/orders';

    private const URL_PATH_BATCHES = '/batches';

    private const URL_PATH_PRODUCTS = '/products';

    private const URL_PATH_EVENTS = '/events';

    /** @var ClientFactoryInterface */
    private $clientFactory;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ClientFactoryInterface $httpClient,
        SerializerInterface $serializer,
        MessageFactory $messageFactory
    ) {
        $this->messageFactory = $messageFactory;
        $this->serializer = $serializer;
        $this->clientFactory = $httpClient;
    }

    public function postContact(Contact $contact, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_CONTACTS,
                $contact
            ),
            $channelCode
        );

        return $this->parseResponse($response, ContactSuccess::class);
    }

    public function postCategory(Category $category, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_CATEGORIES,
                $category
            ),
            $channelCode
        );

        return $this->parseResponse($response, CategorySuccess::class);
    }

    public function getCategory(?string $categoryId, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'GET',
                self::API_VERSION . self::URL_PATH_CATEGORIES . '/' . $categoryId
            ),
            $channelCode
        );

        return $this->parseResponse($response, CategorySuccess::class);
    }

    public function postCart(Cart $cart, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_CARTS,
                $cart
            ),
            $channelCode
        );

        return $this->parseResponse($response, CartSuccess::class);
    }

    public function patchCart(Cart $cart, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'PATCH',
                self::API_VERSION . self::URL_PATH_CARTS . '/' . $cart->getCartID(),
                $cart
            ),
            $channelCode
        );

        return $this->parseResponse($response, CartSuccess::class);
    }

    public function deleteCart(?string $cartId, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'DELETE',
                self::API_VERSION . self::URL_PATH_CARTS . '/' . $cartId
            ),
            $channelCode
        );

        return $this->parseResponse($response, CartSuccess::class);
    }

    public function postOrder(Order $order, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_ORDERS,
                $order
            ),
            $channelCode
        );

        return $this->parseResponse($response, OrderSuccess::class);
    }

    public function patchOrder(Order $order, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'PATCH',
                self::API_VERSION . self::URL_PATH_ORDERS . '/' . $order->getOrderID(),
                $order
            ),
            $channelCode
        );

        return $this->parseResponse($response, OrderSuccess::class);
    }

    public function putOrder(Order $order, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'PUT',
                self::API_VERSION . self::URL_PATH_ORDERS . '/' . $order->getOrderID(),
                $order
            ),
            $channelCode
        );

        return $this->parseResponse($response, OrderSuccess::class);
    }

    public function deleteOrder(string $orderId, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'DELETE',
                self::API_VERSION . self::URL_PATH_ORDERS . '/' . $orderId
            ),
            $channelCode
        );

        return $this->parseResponse($response, OrderSuccess::class);
    }

    public function putCategory(Category $category, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'PUT',
                self::API_VERSION . self::URL_PATH_CATEGORIES . '/' . $category->getCategoryID(),
                $category
            ),
            $channelCode
        );

        return $this->parseResponse($response, CategorySuccess::class);
    }

    public function deleteCategory(string $categoryId, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'DELETE',
                self::API_VERSION . self::URL_PATH_CATEGORIES . '/' . $categoryId,
                null
            ),
            $channelCode
        );

        return $this->parseResponse($response, CategorySuccess::class);
    }

    public function getProduct(?string $productId, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'GET',
                self::API_VERSION . self::URL_PATH_PRODUCTS . '/' . $productId
            ),
            $channelCode
        );

        return $this->parseResponse($response, ProductSuccess::class);
    }

    public function postProduct(Product $product, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_PRODUCTS,
                $product
            ),
            $channelCode
        );

        return $this->parseResponse($response, ProductSuccess::class);
    }

    public function putProduct(Product $product, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'PUT',
                self::API_VERSION . self::URL_PATH_PRODUCTS . '/' . $product->getProductID(),
                $product
            ),
            $channelCode
        );

        return $this->parseResponse($response, ProductSuccess::class);
    }

    public function deleteProduct(string $productId, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'DELETE',
                self::API_VERSION . self::URL_PATH_PRODUCTS . '/' . $productId,
                null
            ),
            $channelCode
        );

        return $this->parseResponse($response, ProductSuccess::class);
    }

    public function postBatch(Batch $batch, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_BATCHES,
                $batch
            ),
            $channelCode
        );

        return $this->parseResponse($response, BatchSuccess::class);
    }

    public function postEvent(CreateEvent $event, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_EVENTS,
                $event
            ),
            $channelCode
        );

        return $this->parseResponse($response, EventSuccess::class);
    }

    public function getEvents(?string $channelCode): ?array
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'GET',
                self::API_VERSION . self::URL_PATH_EVENTS,
            ),
            $channelCode
        );

        return $this->parseResponse($response, Event::class . '[]');
    }

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'PATCH',
                self::API_VERSION . self::URL_PATH_CONTACTS . '/' . $contactId,
                $contact
            ),
            $channelCode
        );

        return $this->parseResponse($response, ContactSuccess::class);
    }

    public function getContactByEmail(?string $email, ?string $channelCode): ?object
    {
        $response = $this->sendRequest(
            $this->messageFactory->create(
                'GET',
                self::API_VERSION . self::URL_PATH_CONTACTS . '?email=' . $email
            ),
            $channelCode
        );

        return $this->parseResponse($response, ContactSuccessList::class);
    }

    private function sendRequest(RequestInterface $request, ?string $channelCode): ?ResponseInterface
    {
        try {
            return $this->clientFactory->create($channelCode)->sendRequest($request);
        } catch (HttpException $requestException) {
            $response = [
                'status' => $requestException->getResponse()->getStatusCode(),
                'headers' => $requestException->getResponse()->getHeaders(),
                'partial_body' => $requestException->getResponse()->getBody()->read(255),
            ];

            if ($this->logger !== null) {
                $this->logger->critical(
                    'Request to Omnisend API failed.',
                    [
                        'request_data' => $request->getBody()->getContents(),
                        'response' => $response,
                    ]
                );
            }

            return null;
        } catch (Throwable $exception) {
            if ($this->logger !== null) {
                $this->logger->critical(
                    'Request to Omnisend API failed.',
                    [
                        'error' => $exception->getMessage(),
                    ]
                );
            }
        }

        return null;
    }

    private function parseResponse(?ResponseInterface $response, ?string $type = null)
    {
        if ($response !== null && $response->getStatusCode() === 200 && $type !== null) {
            try {
                return $this->serializer->deserialize(
                    $response->getBody()->getContents(),
                    $type,
                    'json'
                );
            } catch (Throwable $e) {
                if ($this->logger !== null) {
                    $this->logger->critical(
                        'Failed to parse omnisend response',
                        [
                            'error' => $e->getMessage(),
                        ]
                    );
                }

                return null;
            }
        }

        return null;
    }
}
