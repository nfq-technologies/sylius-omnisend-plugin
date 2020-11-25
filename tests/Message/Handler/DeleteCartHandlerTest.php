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

namespace Tests\NFQ\SyliusOmnisendPlugin\Message\Handler;

use FOS\RestBundle\Controller\Annotations\Delete;
use NFQ\SyliusOmnisendPlugin\Builder\Request\CartBuilderDirectorInterface;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClient;
use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Doctrine\ORM\TaxonRepositoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\BatchFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Factory\Request\CategoryFactoryInterface;
use NFQ\SyliusOmnisendPlugin\Message\Command\DeleteCart;
use NFQ\SyliusOmnisendPlugin\Message\Command\UpdateCart;
use NFQ\SyliusOmnisendPlugin\Message\Handler\DeleteCartHandler;
use NFQ\SyliusOmnisendPlugin\Message\Handler\PushCategoriesHandler;
use NFQ\SyliusOmnisendPlugin\Message\Handler\UpdateCartHandler;
use NFQ\SyliusOmnisendPlugin\Model\OrderInterface;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Tests\NFQ\SyliusOmnisendPlugin\Mock\OrderMock;
use function Clue\StreamFilter\fun;

class DeleteCartHandlerTest extends TestCase
{
    /** @var DeleteCartHandler */
    private $handler;

    /** @var OmnisendClientInterface */
    private $omnisendClient;

    protected function setUp(): void
    {
        $this->omnisendClient = $this->createMock(OmnisendClientInterface::class);

        $this->handler = new DeleteCartHandler(
            $this->omnisendClient
        );
    }

    public function testIfDeletesIfOrderExists()
    {
        $order = new OrderMock();
        $order->getOmnisendOrderDetails()->setCartId('444');

        $this->omnisendClient
            ->expects($this->once())
            ->method('deleteCart')
            ->willReturn((new CartSuccess())->setCartID('444'));

        $this->handler->__invoke(
            new DeleteCart("1", 'a')
        );
    }
}
