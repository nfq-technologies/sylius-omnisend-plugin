@omnisend_managing_taxons
Feature: Adding a new taxon to Omnisend
    In order to see created category in Omnisend
    As an Administrator
    I want that created taxon should be synced with Omnisend account

    Background:
        Given the store is available in "English (United States)"
        And the store operates on a single channel in the "United States" named default
        And I am logged in as an administrator

    @ui
    Scenario: Adding a new taxon
        Given I want to create a new taxon
        When I specify its code as "t-shirts"
        And I name it "T-Shirts" in "English (United States)"
        And I set its slug to "t-shirts" in "English (United States)"
        And I add it
        Then I should be notified that it has been successfully created
        And Taxon with a code "t-shirts" should be pushed to Omnisend

