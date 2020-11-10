@shop_tracking_scripts
Feature: Display Omnisend product picker script in product view
  In order to track products
  As a Shop owner
  I want to be able to see Omnisend product picker script in product view

  Background:
    Given the store operates on a single channel in the "United States" named "default"
    And the store has a product "Test product" priced at "$20"

  @ui
  Scenario: I see omnisend product picker script in product view
    When I view product "Test product"
    Then I see omnisend product picker script with values:
      | key      | value        |
      | productID| TEST_PRODUCT |
      | variantID| TEST_PRODUCT |
      | title    | Test product |
      | price    | 2000         |
      | currency | USD          |
