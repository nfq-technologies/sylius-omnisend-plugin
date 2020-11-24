@omnisend_managing_taxons
Feature: Push edited taxon to Omnisend
    In order to get changed taxon in Omnisend
    As an Administrator
    I want to be able to edit a taxon and see synced taxon in Omnisend

    Background:
        Given the store is available in "English (United States)"
        And the store classifies its products as "shirts" and "Accessories"
        And the store operates on a single channel in the "United States" named default
        And I am logged in as an administrator

    @ui
    Scenario: Renaming a taxon
        Given I want to modify the "shirts" taxon
        When I rename it to "Stickers" in "English (United States)"
        And I save my changes
        And Taxon with a code "shirts" should be pushed to Omnisend
        Then I should be notified that it has been successfully edited
        And this taxon name should be "Stickers"
