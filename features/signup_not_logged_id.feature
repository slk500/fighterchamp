Feature: Signup

  Background:
    Given there is a tournament "Fight Tournament 1"
    And I am on "/turnieje"
    And I follow "Fight Tournament 1"
    And I follow "Zapisy"

  Scenario: On signup for a torunament page, not logged in
    Then I should see "Aby edytować / skasować swoje zgłoszenie na turniej musisz być zalogowany!"
