@tool @tool_richardnz @javascript
Feature: Add a task feature
    In order to add a task
    As a teacher
    I need to add the task via the course menu
    @javascript
    Scenario: Add a task to the list
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on

    When I navigate to "Richard NZ tool" in current page administration
    And I click on "Add new task"
    And I set the following fields to these values:
        | Name | New task description |
    And I press "Save changes"
    Then the following should exist in the "tool_richardnz" table:
    | name |
    | New task description |