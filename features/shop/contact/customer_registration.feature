@contact_registration
Feature: Account registration
    In order to make future purchases with ease
    As a Visitor
    I need to be able to create an account in the store

    Background:
        Given the store operates on a single channel in the "United States" named "default"
        And the channel "default" has omnisend api key with a value "api_key"

    @ui @api
    Scenario: Registering a new account with minimum information
        When I want to register a new account
        And I specify the first name as "Saul"
        And I specify the last name as "Goodman"
        And I specify the email as "goodman@gmail.com"
        And I specify the password as "heisenberg"
        And I confirm this password
        And I register this account
        Then I should be notified that new account has been successfully created
        And Customer with "goodman@gmail.com" should have omnisend contact id
