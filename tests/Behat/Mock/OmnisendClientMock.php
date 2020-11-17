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

use NFQ\SyliusOmnisendPlugin\Client\OmnisendClientInterface;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Batch;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Cart;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Category;
use NFQ\SyliusOmnisendPlugin\Client\Request\Model\Contact;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\BatchSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CartSuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\CategorySuccess;
use NFQ\SyliusOmnisendPlugin\Client\Response\Model\ContactSuccess;

class OmnisendClientMock implements OmnisendClientInterface
{
    public function postContact(Contact $contact, ?string $channelCode): ?object
    {
        return (new ContactSuccess())
            ->setContactID('testId');
    }

    public function patchContact(string $contactId, Contact $contact, ?string $channelCode): void
    {
    }

    public function postCategory(Category $category, ?string $channelCode): ?object
    {
        return new CategorySuccess();
    }

    public function putCategory(Category $category, ?string $channelCode): ?object
    {
        return new CategorySuccess();
    }

    public function deleteCategory(string $categoryId, ?string $channelCode): ?object
    {
        return new CategorySuccess();
    }

    public function postBatch(Batch $batch, ?string $channelCode): ?object
    {
        return new BatchSuccess();
    }

    public function postCart(Cart $cart, ?string $channelCode): ?object
    {
        return new CartSuccess();
    }

    public function patchCart(Cart $cart, ?string $channelCode): ?object
    {
        return new CartSuccess();
    }

    public function deleteCart(string $cartId, ?string $channelCode): ?object
    {
        return new CartSuccess();
    }


}
