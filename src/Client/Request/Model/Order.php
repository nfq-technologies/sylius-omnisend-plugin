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

namespace NFQ\SyliusOmnisendPlugin\Client\Request\Model;

use stdClass;

class Order
{
    /** @var string|null */
    private $orderID;

    /** @var string|null */
    private $cartID;

    /** @var string|null */
    private $email;

    /** @var string|null */
    private $phone;

    /** @var string|null */
    private $contactID;

    /** @var int|null */
    private $orderNumber;

    /** @var string|null */
    private $shippingMethod;

    /** @var string|null */
    private $trackingCode;

    /** @var string|null */
    private $courierTitle;

    /** @var string|null */
    private $courierUrl;

    /** @var string|null */
    private $orderUrl;

    /** @var string|null */
    private $source;

    /** @var array|null */
    private $tags;

    /**  @var string|null */
    private $discountCode;

    /** @var int|null */
    private $discountValue;

    /** @var string|null */
    private $discountType;

    /** @var string|null */
    private $currency;

    /** @var int|null */
    private $orderSum;

    /** @var int|null */
    private $subTotalSum;

    /** @var int|null */
    private $discountSum;

    /** @var int|null */
    private $taxSum;

    /** @var int|null */
    private $shippingSum;

    /** @var string|null */
    private $createdAt;

    /** @var string|null */
    private $updatedAt;

    /** @var string|null */
    private $canceledDate;

    /** @var string|null */
    private $cancelReason;

    /** @var string|null */
    private $paymentMethod;

    /** @var string|null */
    private $paymentStatus;

    /** @var string|null */
    private $fulfillmentStatus;

    /** @var string|null */
    private $contactNote;

    /** @var OrderAddress|null */
    private $billingAddress;

    /** @var OrderAddress|null */
    private $shippingAddress;

    /** @var OrderProduct[]|array|null */
    private $products;

    /** @var stdClass|null */
    private $customTags;

    public function getOrderID(): ?string
    {
        return $this->orderID;
    }

    public function setOrderID(string $orderID): self
    {
        $this->orderID = $orderID;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getContactID(): ?string
    {
        return $this->contactID;
    }

    public function setContactID(?string $contactID): self
    {
        $this->contactID = $contactID;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(?string $shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public function setTrackingCode(?string $trackingCode): self
    {
        $this->trackingCode = $trackingCode;

        return $this;
    }

    public function getCourierTitle(): ?string
    {
        return $this->courierTitle;
    }

    public function setCourierTitle(?string $courierTitle): self
    {
        $this->courierTitle = $courierTitle;

        return $this;
    }

    public function getCourierUrl(): ?string
    {
        return $this->courierUrl;
    }

    public function setCourierUrl(?string $courierUrl): self
    {
        $this->courierUrl = $courierUrl;

        return $this;
    }

    public function getOrderUrl(): ?string
    {
        return $this->orderUrl;
    }

    public function setOrderUrl(?string $orderUrl): self
    {
        $this->orderUrl = $orderUrl;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getDiscountCode(): ?string
    {
        return $this->discountCode;
    }

    public function setDiscountCode(?string $discountCode): self
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    public function getDiscountValue(): ?int
    {
        return $this->discountValue;
    }

    public function setDiscountValue(?int $discountValue): self
    {
        $this->discountValue = $discountValue;

        return $this;
    }

    public function getDiscountType(): ?string
    {
        return $this->discountType;
    }

    public function setDiscountType(?string $discountType): self
    {
        $this->discountType = $discountType;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOrderSum(): ?int
    {
        return $this->orderSum;
    }

    public function setOrderSum(int $orderSum): self
    {
        $this->orderSum = $orderSum;

        return $this;
    }

    public function getSubTotalSum(): ?int
    {
        return $this->subTotalSum;
    }

    public function setSubTotalSum(?int $subTotalSum): self
    {
        $this->subTotalSum = $subTotalSum;

        return $this;
    }

    public function getDiscountSum(): ?int
    {
        return $this->discountSum;
    }

    public function setDiscountSum(?int $discountSum): self
    {
        $this->discountSum = $discountSum;

        return $this;
    }

    public function getTaxSum(): ?int
    {
        return $this->taxSum;
    }

    public function setTaxSum(?int $taxSum): self
    {
        $this->taxSum = $taxSum;

        return $this;
    }

    public function getShippingSum(): ?int
    {
        return $this->shippingSum;
    }

    public function setShippingSum(?int $shippingSum): self
    {
        $this->shippingSum = $shippingSum;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCanceledDate(): ?string
    {
        return $this->canceledDate;
    }

    public function setCanceledDate(?string $canceledDate): self
    {
        $this->canceledDate = $canceledDate;

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): self
    {
        $this->cancelReason = $cancelReason;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(?string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getFulfillmentStatus(): ?string
    {
        return $this->fulfillmentStatus;
    }

    public function setFulfillmentStatus(?string $fulfillmentStatus): self
    {
        $this->fulfillmentStatus = $fulfillmentStatus;

        return $this;
    }

    public function getContactNote(): ?string
    {
        return $this->contactNote;
    }

    public function setContactNote(?string $contactNote): self
    {
        $this->contactNote = $contactNote;

        return $this;
    }

    public function getBillingAddress(): ?OrderAddress
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?OrderAddress $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getShippingAddress(): ?OrderAddress
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?OrderAddress $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(?array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(OrderProduct $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function getCustomTags(): ?stdClass
    {
        return $this->customTags;
    }

    public function setCustomTags(?stdClass $customTags): self
    {
        $this->customTags = $customTags;
        
        return $this;
    }

    public function getCartID(): ?string
    {
        return $this->cartID;
    }

    public function setCartID(?string $cartID): self
    {
        $this->cartID = $cartID;

        return $this;
    }
}
