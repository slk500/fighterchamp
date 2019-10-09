Feature: Signup

  Scenario:
    Given there is a tournament "Fight Tournament 1"
    And there is a user "user"
    And "user" have signup for "Fight Tournament 1"
    When I send a "DELETE" request to "api/signups/1"
    Then the response should contain "deletedAt" field which is not empty

  Scenario:
    Given there is a tournament "Fight Tournament 1"
    And there is a user "user"
    And "user" have signup for "Fight Tournament 1"
    When I send a "GET" request to "api/tournaments/1/signups"
    Then the response should be
    """
  {
  "data": [{
    "id": 1,
    "formula": "boks",
    "weight": "100",
    "staz": null,
    "youtubeId": "",
    "musicArtistAndTitle": "",
    "isPaid": false,
    "deletedAtByAdmin": null,
    "weighted": null,
    "trainingTime": null,
    "isLicence": false,
    "discipline": null,
    "deletedAt": null,
    "user": {
      "href": "\/ludzie\/1",
      "name": "user",
      "surname": "DefaultSurname",
      "male": true,
      "birthDay": {
        "date": "1986-01-08 00:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
      },
      "record": {
        "win": 0,
        "draw": 0,
        "lose": 0
      },
      "club": null
    }
  }
  ]
}
    """




