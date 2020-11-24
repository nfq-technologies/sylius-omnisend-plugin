@omnisend_checkout
Feature: Omnisend should be notified about created order in checkout

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "PHP T-Shirt" priced at "$19.99"
    And the store ships everywhere for free
    And the store allows paying offline
    And there is a customer account "john@example.com"

  @ui
  Scenario: Successfully placing an order
    Given I have product "PHP T-Shirt" in the cart
    When I complete addressing step with email "john@example.com" and "United States" based billing address
    And I select "Free" shipping method
    And I complete the shipping step
    And I choose "Offline" payment method
    And I confirm my order
    Then I should see the thank you page
    And Request type "POST" to Omnisend endpoint "orders" was sent
    And Omnisend request should contains data:
      | key               | value           |
      | orderSum          | 1999            |
      | paymentMethod     | Offline         |
      | paymentStatus     | awaitingPayment |
      | fulfillmentStatus | unfulfilled     |
