@omnisend_modifying_products
Feature: Deleted product data should be sent to Omnisend

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "Toyota GT86 model"
        And this product has "1:43" variant priced at "$15.00"
        And this product has one review from customer "john@doe.com"
        And I am logged in as an administrator

    @ui
    Scenario: Deleted product disappears from the product catalog
        When I delete the "Toyota GT86 model" product
        Then I should be notified that it has been successfully deleted
        And this product should not exist in the product catalog
        And Request type "DELETE" to Omnisend endpoint "products" should be sent
