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
 * Observer callbacks for course events
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die;
use \tool_richardnz\local\debugging;

class tool_richardnz_observer {
    /**
     * Remove all the task entries for this course.
     * @param \core\event\base $event
     */
    public static function course_content_deleted(
            \core\event\course_content_deleted $event) {
        global $DB;
        // Delete all tasks of given course.
        $DB->delete_records('tool_richardnz',
                ['courseid' => $event->courseid]);
    }
}