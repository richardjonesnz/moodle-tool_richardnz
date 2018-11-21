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
 * Class that prints a table of tasks
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_richardnz\local;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

class taskstable extends \table_sql {

    public function __construct($url, $context) {

        $this->context = $context;
        // switch to get_string when working
        $cols = array('id' => 'id',
                      'courseid' => 'course id',
                      'name' => 'task',
                      'completed' => 'completed?',
                      'priority' => 'Priority');
        $this->define_columns(array_keys($cols));
        $this->define_headers(array_values($cols));
        $this->define_baseurl($url);
        $this->collapsible(false);
        $this->sortable(true, 'id', SORT_ASC);
        $this->pagesize = 20;
    }

    public function query_db($pagesize, $useinitialsbar = false) {
        global $DB;

        $this->rawdata = $DB->get_records('tool_richardnz', [], null, 'id, courseid, name, completed, priority');
    }

    public function out($pagesize, $useinitialsbar = false,
            $downloadhelpbutton = '' ) {

        $this->setup();
        $this->query_db($pagesize);
        $this->build_table();
        $this->close_recordset();
        $this->finish_output();
    }
}