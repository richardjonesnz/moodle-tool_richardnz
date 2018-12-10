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
 * Utility functions
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
namespace tool_richardnz\local;
defined('MOODLE_INTERNAL') || die;
use \tool_richardnz\local\debugging;
class utilities {
    /**
     * Returns options for editor field.
     *
     * @param stdClass $context
     * @return array of file options
     */
    public static function get_editor_options($context) {
        global $CFG;
        return ['subdirs' => true,
                'maxbytes' => $CFG->maxbytes,
                'maxfiles' => -1,
                'context' => $context,
                'trusttext' => true];
    }
}