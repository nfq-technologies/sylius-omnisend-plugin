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

namespace NFQ\SyliusOmnisendPlugin\Client;

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\BatchSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CategorySuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;
use NFQ\SyliusOmnisendPlugin\HttpClient\ClientFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;
use Http\Client\Exception\HttpException;

class OmnisendClient implements LoggerAwareInterface, OmnisendClientInterface
{
    use LoggerAwareTrait;

    private const API_VERSION = 'v3';
    private const URL_PATH_CONTACTS = '/contacts';
    private const URL_PATH_CATEGORIES = '/categories';
    private const URL_PATH_CARTS = '/carts';
    private const URL_PATH_BATCHES = '/batches';

    /** @var ClientFactory */
    private $clientFactory;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(
        ClientFactory $httpClient,
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

    public function deleteCart(string $cartId, ?string $channelCode): ?object
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

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): void
    {
        $this->sendRequest(
            $this->messageFactory->create(
                'PATCH',
                self::API_VERSION . self::URL_PATH_CONTACTS . '/' . $contactId,
                $contact
            ),
            $channelCode
        );
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
        }
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

