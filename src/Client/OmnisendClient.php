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
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

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
    ) {
        $this->messageFactory = $messageFactory;
        $this->serializer = $serializer;
        $this->clientFactory = $httpClient;
    }

    public function postContact(Contact $contact, string $channelCode)
    {
        $response = $this->clientFactory->create($channelCode)->sendRequest(
            $this->messageFactory->create(
                'POST',
                self::API_VERSION . self::URL_PATH_CONTACTS,
                $contact
            )
        );

        return $this->parseResponse($response, ContactSuccess::class);
    }

    public function patchContact(string $contactId, Contact $contact, string $channelCode)
    {
        $response = $this->clientFactory->create($channelCode)->sendRequest(
            $this->messageFactory->create(
                'PATCH',
                self::API_VERSION . self::URL_PATH_CONTACTS . '/' . $contactId,
                $contact
            )
        );

        return $this->parseResponse($response);
    }

    private function parseResponse(ResponseInterface $response, ?string $type = null) //TODO move to response handler
    {
        if ($response->getStatusCode() === 200) {
            try {
                return $this->serializer->deserialize(
                    $response->getBody()->getContents(),
                    $type,
                    'json'
                );
            } catch (Throwable $e) {
                $this->logger && $this->logger->critical(
                    'Failed to parse omnisend response',
                    [
                        'error' => $e->getMessage(),
                    ]
                );

                return null;
            }
        }

        return null;
    }
}

