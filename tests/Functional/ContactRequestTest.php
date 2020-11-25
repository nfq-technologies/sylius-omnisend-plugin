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

namespace Tests\NFQ\SyliusOmnisendPlugin\Functional;

use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use NFQ\SyliusOmnisendPlugin\Builder\Request\ContactBuilderDirectorInterface;
use Sylius\Component\Core\Model\Address;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Application\Entity\Customer;

class ContactRequestTest extends WebTestCase
{
    use PHPMatcherAssertions;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ContactBuilderDirectorInterface */
    private $director;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->serializer = self::$container->get('serializer');
        $this->director = self::$container->get('nfq_sylius_omnisend_plugin.builder.request.contact_director');
    }

    /** @dataProvider data */
    public function testIfFormatValidRequest(Customer $data, string $result)
    {
        $this->assertMatchesPattern(
            $result,
            $this->serializer->serialize(
                $this->director->build($data),
                'json',
                [
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true
                ]
            )
        );
    }

    public function data(): array
    {
        $customer1 = new Customer();
        $customer1->setEmail('test@nfq.lt');
        $customer2 = new Customer();
        $customer2->setPhoneNumber('231345');

        $customer3 = new Customer();
        $customer3->setPhoneNumber('231345');
        $customer3->setEmail('email@nfq.lt');
        $customer3->setFirstName('test');
        $customer3->setLastName('test');
        $customer3->setBirthday(new \DateTime());
        $customer3->setCreatedAt(new \DateTime());
        $customer3->setSubscribedToNewsletter(true);
        $customer3->setGender('m');
        $address = new Address();
        $address->setCity('city');
        $address->setStreet('street');
        $address->setPostcode('LT-22223');
        $address->setProvinceName('Province');
        $address->setCountryCode('UK');
        $customer3->setDefaultAddress($address);

        return [
            'onlyEmail' => [
                $customer1,
                <<<JSON
                {
                  "identifiers": [
                    {
                      "type": "email",
                      "id": "@string@",
                      "channels": {
                        "email": {
                          "status": "@string@",
                          "statusDate": "@string@"
                        }
                      },
                      "sendWelcomeMessage": "@boolean@"
                    }
                  ],
                  "createdAt": "@string@"
                }
            JSON,
            ],
            'onlyPhone' => [
                $customer2,
                <<<JSON
                {
                  "identifiers": [
                    {
                      "type": "phone",
                      "id": "@string@",
                      "channels": {
                        "sms": {
                          "status": "@string@",
                          "statusDate": "@string@"
                        }
                      },
                      "sendWelcomeMessage": "@boolean@"
                    }
                  ],
                  "createdAt": "@string@"
                }
            JSON
            ],
            'full' => [
                $customer3,
                <<<JSON
                {
                   "identifiers": [
                    {
                      "type": "@string@",
                      "id": "@string@",
                      "channels": {
                        "email": {
                          "status": "@string@",
                          "statusDate": "@string@"
                        }
                      },
                      "sendWelcomeMessage": "@boolean@"
                    },
                    {
                      "type": "@string@",
                      "id": "@string@",
                      "channels": {
                        "sms": {
                          "status": "@string@",
                          "statusDate": "@string@"
                        }
                      },
                      "sendWelcomeMessage": "@boolean@"
                    }
                  ],
                  "firstName": "@string@",
                  "lastName": "@string@",
                  "countryCode": "@string@",
                  "state": "@string@",
                  "city": "@string@",
                  "address": "@string@",
                  "postalCode": "@string@",
                  "gender": "@string@",
                  "birthday": "@string@",
                  "createdAt": "@string@"
                }
            JSON
            ],
        ];
    }
}
