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

    public function __construct() {

        $headers = array('id', 'Course id', 'Task name', 'Completed', 'Priority');
        $columns = array('id', 'courseid', 'name', 'completed', 'priority');

        $this->define_headers($headers);
        $this->define_columns($columns);

        $from = '{tool_richardnz}';
        $this->set_sql($columns, $from, 1, array());
    }

}