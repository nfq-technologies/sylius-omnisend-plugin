@omnisend_cart
Feature: Adding a simple product to the cart
  In order to select products for purchase
  As a Visitor
  I want to be able to add simple products to cart

  Background:
    Given the store operates on a single channel in "United States"

  @ui
  Scenario: Adding a simple product to the cart
    Given the store has a product "T-shirt banana" priced at "$12.54"
    When I add this product to the cart
    Then I should be on my cart summary page
    And I should be notified that the product has been successfully added
    And there should be one item in my cart
    And this item should have name "T-shirt banana"
    And Cart should not be synced with Omnisend

  @ui
  Scenario: Adding a simple product to the cart with omnisend contact id
    Given the store has a product "T-shirt banana" priced at "$12.54"
    And Browser has Omnisend contact cookie
    When I add this product to the cart
    Then I should be on my cart summary page
    And I should be notified that the product has been successfully added
    And there should be one item in my cart
    And this item should have name "T-shirt banana"
    And Cart should be synced with Omnisend

  @ui
  Scenario: Adding a product to the cart as a logged in customer
    Given I am a logged in customer
    And the store has a product "Oathkeeper" priced at "$99.99"
    When I add this product to the cart
    Then I should be on my cart summary page
    And I should be notified that the product has been successfully added
    And there should be one item in my cart
    And this item should have name "Oathkeeper"
    And Cart should be synced with Omnisend
    When I remove product "Oathkeeper" from the cart
    Then my cart should be empty
    And Request type "DELETE" to Omnisend endpoint "carts" should be sent
    When I add product "Oathkeeper" to the cart
    And Request type "POST" to Omnisend endpoint "carts" should be sent
