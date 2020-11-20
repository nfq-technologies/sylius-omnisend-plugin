@omnisend_modifying_products
Feature: Updated product data should be sent to Omnisend

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Dice Brewing"
    And I am logged in as an administrator

  @ui
  Scenario: Renaming a simple product
    Given I want to modify the "Dice Brewing" product
    When I rename it to "7 Wonders" in "English (United States)"
    And I change its price to $15.00 for "United States" channel
    And I change its original price to "$25.00" for "United States" channel
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this product name should be "7 Wonders"
    And Product with a code "DICE_BREWING" should be marked as pushed to Omnisend
    And Omnisend request should contains data:
      | key       | value        |
      | productID | DICE_BREWING |
      | title     | 7 Wonders    |
      | price     | 1500         |
      | oldPrice  | 2500         |
      | status    | inStock      |
      | currency  | USD          |
