@omnisend_modifying_address
Feature: Changed order state data should be sent to Omnisend

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "PHP T-Shirt"
    And the store ships everywhere for free
    And the store allows paying with "Cash on Delivery"
    And there is a customer "john.doe@gmail.com" that placed an order "#00000022"
    And the customer bought a single "PHP T-Shirt"
    And the customer chose "Free" shipping method to "United States" with "Cash on Delivery" payment
    And I am logged in as an administrator

  @ui @api
  Scenario: Cancelling an order
    When I view the summary of the order "#00000022"
    And I cancel this order
    Then I should be notified that it has been successfully updated
    And its state should be "Cancelled"
    And it should have shipment in state "Cancelled"
    And it should have payment state "Cancelled"
    And Request type "PUT" to Omnisend endpoint "orders" was sent
    And Omnisend request should contains data:
      | key               | value       |
      | paymentStatus     | voided      |
      | fulfillmentStatus | unfulfilled |
    And cancelled order date should be set
