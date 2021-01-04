@omnisend_managing_events
Feature: Changed custom event data should by sent to Omnisend
  In order to see added new event field in Omnisend
  As an Administrator
  I want that added new event field should be synced with Omnisend

  Background:
    Given the store is available in "English (United States)"
    And the store operates on a single channel in the "United States" named default
    And the store has custom event "TestSystemName" with fields:
      | key         | value  |
      | stringField | string |
      | intField    | int    |
    And I am logged in as an administrator

  @ui @javascript
  Scenario: Adding a custom event
    Given I want to modify the "TestSystemName" event
    And I add new custom field
    And I fill last added custom field system name with value "fieldName"
    And I select last added field type "email"
    And I update it
    Then Request type "POST" to Omnisend endpoint "events" should be sent
    And Omnisend request should contain data:
      | key         | value              |
      | systemName  | TestSystemName     |
      | stringField | sylius             |
      | intField    | 1                  |
      | email       | sylius@example.com |
