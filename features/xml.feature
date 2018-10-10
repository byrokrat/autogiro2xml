Feature: Convering to XML
  In order to use autogiro2xml
  As a user
  I need to be able to convert autogiro files to xml

  Scenario: I convert an autogiro file to xml
    Given a fresh installation
    And a valid autogiro file named "ag.txt"
    When I run "autogiro2xml ag.txt"
    Then the output contains "1" lines like "/<?xml/"

  Scenario: I convert an autogiro file to xml using to format option
    Given a fresh installation
    And a valid autogiro file named "ag.txt"
    When I run "autogiro2xml ag.txt --format=xml"
    Then the output contains "1" lines like "/<?xml/"

  Scenario: I convert an autogiro file from stdin
    Given a fresh installation
    And a valid autogiro file named "ag.txt"
    When I run "cat ag.txt | autogiro2xml"
    Then the output contains "1" lines like "/<?xml/"

  Scenario: I convert two autogiro files to xml
    Given a fresh installation
    And a valid autogiro file named "ag1.txt"
    And a valid autogiro file named "ag2.txt"
    When I run "autogiro2xml ag1.txt ag2.txt"
    Then the output contains "2" lines like "/<?xml/"

  Scenario: I convert files in a directory
    Given a fresh installation
    And a valid autogiro file named "ag1.txt"
    And a valid autogiro file named "ag2.txt"
    When I run "autogiro2xml ."
    Then the output contains "2" lines like "/<?xml/"

  Scenario: I try to convert a broken autogiro file
    Given a fresh installation
    And a broken autogiro file named "broken.txt"
    When I run "autogiro2xml broken.txt"
    Then I get an error

  Scenario: I try to convert an autogiro file with invalid ids
    Given a fresh installation
    And an autogiro file with invalid ids named "ids.txt"
    When I run "autogiro2xml ids.txt"
    Then I get an error

  Scenario: I convert an autogiro file with invalid ids
    Given a fresh installation
    And an autogiro file with invalid ids named "ids.txt"
    When I run "autogiro2xml ids.txt --ignore-objects"
    Then there is no error
