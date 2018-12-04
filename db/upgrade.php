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
 * An upgrade script.
 *
 * @package tool_richardnz
 * @copyright 2018 Richard Jones <richardnz@outlook.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://moodledev.moodle.school/course/view.php?id=3&section=10
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_tool_richardnz_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2018112005) {

        // Define table tool_richardnz to be created.
        $table = new xmldb_table('tool_richardnz');

        // Adding fields to table tool_richardnz.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('completed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('priority', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table tool_richardnz.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for tool_richardnz.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Richardnz savepoint reached.
        upgrade_plugin_savepoint(true, 2018112005, 'tool', 'richardnz');
    }
    if ($oldversion < 2018120302) {

        // Define table tool_richardnz to be updated.
        $table = new xmldb_table('tool_richardnz');

        // Adding fields to table tool_richardnz.
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, 'name');
        $table->add_field('descriptionformat', XMLDB_TYPE_INTEGER, '10', null, null, null, 'description');

        // Richardnz savepoint reached.
        upgrade_plugin_savepoint(true, 2018120302, 'tool', 'richardnz');
    }

    return true;
}