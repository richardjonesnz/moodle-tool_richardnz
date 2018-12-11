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
 * Callback functions for tool_richardnz.
 *
 * @package tool_richardnz
 * @copyright 2018 Richard Jones <richardnz@outlook.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://moodledev.moodle.school/mod/page/view.php?id=50
 */

defined('MOODLE_INTERNAL') || die();

function tool_richardnz_extend_navigation_course($navigation, $course,
        $context) {
    // Check permissions to view the link in the course nav.
    // This is using the course context.
    if (has_capability('tool/richardnz:view', $context)) {
        // Go ahead and add the link to the course navigation.
        $navigation->add( get_string('pluginname', 'tool_richardnz'),
                new moodle_url('/admin/tool/richardnz/index.php',
                ['id' => $course->id]),
        navigation_node::TYPE_SETTING,
        get_string('pluginname', 'tool_richardnz'), 'richardnz',
        new pix_icon('icon', '', 'tool_richardnz'));
    }
}
function tool_richardnz_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}
/**
 * Returns the lists of all browsable file areas.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function tool_richardnz_get_file_areas($course, $cm, $context) {
    return ['description' => 'for task description',
            'attachment' => 'for a task attachment'];
}

/**
 * Serves the files from the description file area
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the course context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function tool_richardnz_pluginfile($course, $cm, $context, $filearea,
        array $args, $forcedownload, array $options = array()) {
    global $DB, $CFG;

    require_login($course, true, $cm);

    $fs = get_file_storage();
    $relativepath = implode('/', $args);
    $fullpath = "/$context->id/tool_richardnz/$filearea/$relativepath";
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }
    // Finally send the file.
    send_stored_file($file);
}