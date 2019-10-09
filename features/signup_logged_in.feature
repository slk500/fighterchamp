Feature: Signup
  User (Fighter or Coach) can sign up for a tournament, edit or delete signup

  Background:
    Given there is a tournament "Fight Tournament 1"
    And I am logged in as fighter
    And I am on "/turnieje"
    And I follow "Fight Tournament 1"

  @javascript
  Scenario: Signup for a tournament
    And I follow "Zapisy"
    And I select "Boks" from "Dyscyplina"
    And I select "A" from "Klasa"
    And I select "100" from "Kategoria wagowa"
    And I press "Wyślij moje zgłoszenie"
    Then I should see "Liczba zapisanych: 1 / 10"

  @javascript
  Scenario: Delete signup for a tournament
    And I am signed up for a tournament
    And I follow "Zapisy"
    Then I press "Skasuj moje zgłoszenie"
    Then I wait for result
    Then I should not see "Twoje zgłoszenie na turniej zostało zarejestrowane"
    Then I should not see "DefaultSurname DefaultName"

  @javascript
  Scenario: Edit signup for a tournament
    And I am signed up for a tournament
    And I follow "Zapisy"
    And I press "Edytuj"
    And I wait for result
    And I select "Karate" from "Dyscyplina"
    And I press "Wyślij moje zgłoszenie"
    And I wait for result
    Then I should see "Karate"