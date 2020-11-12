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

use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
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
    )
    {
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

