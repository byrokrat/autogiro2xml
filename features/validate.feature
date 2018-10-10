Feature: Validating autogiro files
  In order to use autogiro2xml
  As a user
  I need to be able to validate the contents of autogiro files

  Scenario: I validate an autogiro file
    Given a fresh installation
    And a valid autogiro file named "ag.txt"
    When I run "autogiro2xml ag.txt --format validate"
    Then there is no error
    And the output contains "1" lines like "/1 files? passed/"

  Scenario: I validate a broken autogiro file
    Given a fresh installation
    And a broken autogiro file named "broken.txt"
    When I run "autogiro2xml broken.txt --format validate"
    Then I get an error
    And the output contains "1" lines like "/1 failed/"

  Scenario: I validate one valid and one broken autogiro files
    Given a fresh installation
    And a valid autogiro file named "ag.txt"
    And a broken autogiro file named "broken.txt"
    When I run "autogiro2xml . --format validate"
    Then I get an error
    And the output contains "1" lines like "/1 files? passed. 1 failed/"

  Scenario: I stop on failure
    Given a fresh installation
    And a broken autogiro file named "broken1.txt"
    And a broken autogiro file named "broken2.txt"
    When I run "autogiro2xml . --format validate --stop-on-failure"
    Then I get an error
    And the output contains "1" lines like "/1 failed/"
