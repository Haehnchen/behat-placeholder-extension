Feature: Placeholder transformation
  Scenario: Replace placeholders in scalar and multiline step arguments
    Given set a placeholder "%name%" with value "Behat"
    Then the transformed value "%name%" should equal "Behat"
    And the transformed multiline value should equal "Hello Behat":
      """
      Hello %name%
      """
