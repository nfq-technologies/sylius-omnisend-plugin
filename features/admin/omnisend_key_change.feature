@omnisend_key
Feature: Set Omnisend tracking key in channel edit form
  In order to set omnisend tracking key
  As an Administrator
  I want to be able to set it in channel edit form

  Background:
    Given the store operates on a single channel in the "United States" named default
    And I am logged in as an administrator

  @ui
  Scenario: Set omnisend tracking key for channel
    When I am modifying a channel "default"
    And I set omnisend tracking key "test_key"
    And I save my changes
    Then I should be notified that it has been successfully edited
    And channel "default" should have omnisend update key with value "test_key"
