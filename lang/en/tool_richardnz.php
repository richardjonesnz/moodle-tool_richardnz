<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * English strings for tool Richardnz
 *
 * @package    mod_multipage
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

// General module strings
$string['pluginname'] = 'Richard NZ tool';
$string['greeting'] = 'Hello World!';
$string['param'] = '<p>The parameter is <b>{$a}</b></p>';
$string['index_header'] = 'Main page';
$string['add_link'] = 'Add new task';
$string['editlink'] = 'edit';
$string['action'] = 'Action';
$string['taskadded'] = 'Task added';
$string['taskupdated'] = 'Task updated';

// Strings for data table.
$string['tasks'] = 'Tasks';
$string['id'] = 'Task id';
$string['courseid'] = 'Course id';
$string['name'] = 'Description';
$string['priority'] = 'Priority';
$string['completed'] = 'Completed';
$string['timecreated'] = 'Time created';
$string['timemodified'] = 'Last modified';

// Capabilities.
$string['richardnz:view'] = 'View tasks list';
$string['richardnz:edit'] = 'Edit tasks list';

// Errors/warnings
$string['nopermission'] = 'Sorry you do not have permission to view that page';
$string['taskduplicate'] = 'No change: a task with that name already exists.';
// edit page.
$string['edit_header'] = 'Add or edit tasks';
$string['edit_title'] = 'Edit a task';
$string['add_title'] = 'Add a new task';