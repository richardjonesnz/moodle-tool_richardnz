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
 * Prepares table data for a Mustache template.
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */


namespace tool_richardnz\local;
defined('MOODLE_INTERNAL') || die;

class table_data {

    /**
     * Return data for the table header row
     *
     * @return array $headerdata - table column headers
     */
    public static function get_table_headers() {
        $headerdata = array();
        $headerdata[] = get_string('id', 'tool_richardnz');
        $headerdata[] = get_string('courseid', 'tool_richardnz');
        $headerdata[] = get_string('name', 'tool_richardnz');
        $headerdata[] = get_string('priority', 'tool_richardnz');
        $headerdata[] = get_string('completed', 'tool_richardnz');
        $headerdata[] = get_string('timecreated', 'tool_richardnz');
        $headerdata[] = get_string('timemodified', 'tool_richardnz');

        return $headerdata;
    }
    /**
     * Return data for the table rows
     *
     * @return object - data for the mustache renderer
     */
    public static function get_table_data() {
        global $DB;

        $records = $DB->get_records('tool_richardnz', [], null, 'id, courseid, name, completed, priority, timecreated, timemodified');

        $table = new \stdClass();
        $table->class = 'tool_richardnz_table';
        $table->caption = get_string('tasks', 'tool_richardnz');
        $table->tableheaders = self::get_table_headers();
        $table->tabledata = array();

        foreach($records as $record) {
            $data = array();
            $data['id'] = $record->id;
            $data['courseid'] = $record->courseid;
            $data['name'] = $record->name;
            $data['priority'] = $record->priority;
            $data['completed'] =
                    $record->completed == 1 ? 'yes' : 'no';
            $data['timecreated'] = $record->timecreated;
            $data['timemodified'] = $record->timemodified;

            $table->tabledata[] = $data;
        }

        return $table;
    }
}