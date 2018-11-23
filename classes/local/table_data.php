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
use \tool_richardnz\local\debugging;
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
        $headerdata[] = get_string('action', 'tool_richardnz');

        return $headerdata;
    }
    /**
     * Return data for the table rows
     * @param integer $id - relevant course id.
     * @return object - data for the mustache renderer
     */
    public static function get_table_data($id) {
        global $DB;

        $records = $DB->get_records('tool_richardnz', ['courseid' => $id], null, 'id, courseid, name, completed, priority, timecreated, timemodified');

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
            // Add the edit link.
            $url = new \moodle_url('edit.php',
                    ['id' => $record->courseid, 'itemid' => $record->id]);
            $data['editlink'] = \html_writer::link($url,
                    get_string('editlink', 'tool_richardnz'));

            $table->tabledata[] = $data;
        }

        return $table;
    }

    /**
     * Save data for a task in the database
     * @param integer $id - relevant course id.
     * @param integer $itemid - relevant task id.
     * @param object data - data from the edit form.
     * @return integer - id of inserted record.
     */
    public static function save_table_data($id, $itemid, $data) {
        global $DB;
        if ($itemid == 0) {
            // A new task to add.
            if (!self::name_exists($id, $data->name)) {
                $data->courseid = $id;
                $data->timecreated = time();
                $data->timemodified = time();
                return $DB->insert_record('tool_richardnz', $data);
            }
            // Duplicate task name.
            return -1;
        } else {
            // A task to update.
            $data->id = $itemid;
            $data->timemodified = time();
            $DB->update_record('tool_richardnz', $data);
            return $itemid;
        }
    }
    /**
     * Check task name for duplicate
     * @param integer $id - relevant course id.
     * @param object $name - task name to check
     * @return boolean - true if name of task is already in database for this course.
     */
    public static function name_exists($id, $name) {
        global $DB;

        return $DB->record_exists('tool_richardnz',
                ['name' => $name, 'courseid' => $id]);
    }
    /**
     * Get the data for a task given its id
     * @param integer $id - relevant item id.
     * @return object - the data record for the task.
     */
    public static function get_task($id) {
        global $DB;
        return $DB->get_record('tool_richardnz', ['id' => $id], '*',
                MUST_EXIST);
    }
}