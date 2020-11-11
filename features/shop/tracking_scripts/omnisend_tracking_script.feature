@shop_tracking_scripts
Feature: Display Omnisend tracking script in layout
  In order to track site page views
  As a Shop owner
  I want to be able to see Omnisend tracking script

  Background:
    Given the store operates on a single channel in the "United States" named "default"

  @ui
  Scenario: I do not see omnisend tracking script
    When I am browsing the "default" channel
    Then I do not see omnisend tracking script

  Scenario: I see omnisend tracking script if channel has omnisend tracking key
    Given the channel "default" has omnisend tracking key with a value "tracking_key"
    When I am browsing the "default" channel
    Then I see omnisend tracking script with a key "tracking_key"
