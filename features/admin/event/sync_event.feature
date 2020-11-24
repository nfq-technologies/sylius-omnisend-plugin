@omnisend_managing_events
Feature: Sync events from Omnisend
  In order to see current Omnisend events
  As an Administrator
  I want to sync all events from Omnisend

  Background:
    Given the store is available in "English (United States)"
    And the store operates on a single channel in the "United States" named default
    And the channel default has omnisend api key with a value "test"
    And I am logged in as an administrator

  @ui @javascript
  Scenario: Start Omnisend sync
    Given I view custom Omnisend event list
    When I initialize sync process
    Then Request type "GET" to Omnisend endpoint "events" should be sent
    And I should see one item in the list
