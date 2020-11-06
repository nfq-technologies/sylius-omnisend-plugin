@test
Feature: Enabled plugin
    In order to know if plugin is active
    As a Store Owner
    I want to see enabled plugin name in test page

    Scenario: Go to plugin test page
        When I visit a test plugin page
        Then I should see plugin name
