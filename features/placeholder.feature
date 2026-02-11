Feature: Placeholder transformation
  Scenario: Replace placeholders in scalar and multiline step arguments
    Given set a placeholder "%name%" with value "Behat"
    Then the transformed value "%name%" should equal "Behat"
    And the transformed multiline value should equal "Hello Behat":
      """
      Hello %name%
      """

  Scenario: Replace placeholders in Behat 4 doc strings
    Then the placeholder bag should be empty
    Given set a placeholder "%name%" with value "Behat 4"
    Then the transformed doc string should equal "Hello Behat 4":
      """
      Hello %name%
      """

  Scenario Outline: Clear placeholders between scenario outline examples
    Then the placeholder bag should be empty
    Given set a placeholder "%name%" with value "<name>"
    Then the transformed value "%name%" should equal "<name>"

    Examples:
      | name  |
      | first |
      | last  |

  Scenario: Clear placeholders after a scenario outline
    Then the placeholder bag should be empty
