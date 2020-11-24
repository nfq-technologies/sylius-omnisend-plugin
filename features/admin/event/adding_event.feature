@omnisend_managing_events
Feature: Adding a new custom event to Omnisend
  In order to see new event in Omnisend
  As an Administrator
  I want that created custom event should be synced with Omnisend account

  Background:
    Given the store is available in "English (United States)"
    And the store operates on a single channel in the "United States" named default
    And I am logged in as an administrator

  @ui @javascript
  Scenario: Adding a custom event
    Given I want to create a new custom event
    When I specify its system name as "Test System name"
    And I add new custom field
    And I fill last added custom field system name with value "fieldName"
    And I select last added field type "string"
    And I add it
    Then I should be notified that it has been successfully created
    And Request type "POST" to Omnisend endpoint "events" should be sent
    And Omnisend request should contain data:
      | key        | value            |
      | systemName | Test System name |
      | fieldName  | sylius           |

  @ui @javascript
  Scenario: Adding a custom event with same field
    Given I want to create a new custom event
    When I specify its system name as "Test System name"
    And I add new custom field
    And I fill last added custom field system name with value "fieldName"
    And I select last added field type "string"
    And I add new custom field
    And I fill last added custom field system name with value "fieldName"
    And I select last added field type "string"
    And I add it
    Then I should be notified that form contains errors

