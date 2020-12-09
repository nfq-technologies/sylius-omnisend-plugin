@omnisend_modifying_products
Feature: Created product data should be sent to Omnisend

  Background:
    Given the store operates on a single channel in "United States"
    And the store has "Standard" shipping category
    And I am logged in as an administrator

  @ui
  Scenario: Adding a new simple product with discounted price
    Given I want to create a new simple product
    When I specify its code as "BOARD_DICE_BREWING"
    And I name it "Dice Brewing" in "English (United States)"
    And I set its slug to "dice-brewing" in "English (United States)"
    And I set its price to "$10.00" for "United States" channel
    And I set its original price to "$20.00" for "United States" channel
    And I add it
    Then I should be notified that it has been successfully created
    And the product "Dice Brewing" should appear in the store
    And Product with a code "BOARD_DICE_BREWING" should be marked as pushed to Omnisend
    And Omnisend request should contain data:
      | key       | value              |
      | productID | BOARD_DICE_BREWING |
      | title     | Dice Brewing       |
      | price     | 1000               |
      | oldPrice  | 2000               |
      | status    | inStock            |
      | currency  | USD                |
