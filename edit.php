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
 * Task editing page
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://moodledev.moodle.school/mod/page/view.php?id=50
 */
use \tool_richardnz\local\debugging;
use \tool_richardnz\local\task_form;
use \tool_richardnz\local\table_data;
use \core\output\notification;

require_once(__DIR__ . '/../../../config.php');
global $DB;

// Course id.
$id = required_param('id', PARAM_INT);
$itemid = optional_param('itemid', 0, PARAM_INT);
// If itemid is non zero, we came from an edit link.
if ($itemid != 0) {
    // Get the data for this task and load to the form.
    $data = table_data::get_task($itemid);
    $id = $data->courseid;
    $title = get_string('edit_title', 'tool_richardnz');
} else {
    $title = get_string('add_title', 'tool_richardnz');
}
$url = new moodle_url('/admin/tool/richardnz/edit.php',
            ['id' => $id, 'itemid' => $itemid]);

// Setup the page.
$context = context_course::instance($id);
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($title);
$PAGE->set_heading(get_string('edit_header', 'tool_richardnz'));
$return_index = new moodle_url('/admin/tool/richardnz/index.php',
        ['id' => $id]);
require_login();

$mform = new task_form(null, ['id' => $id, 'itemid' => $itemid]);

if ($itemid != 0) {
    $mform->set_data($data);
}

// Check for cancel button.
if ($mform->is_cancelled()) {
    redirect($return_index, get_string('cancelled'), 2);
}

if ($data = $mform->get_data()) {
    // We have data add/update the task.
    $success = table_data::save_table_data($id, $itemid, $data);
    if ($success == -1) {
        redirect($return_index, get_string('taskduplicate', 'tool_richardnz'), 2,
                notification::NOTIFY_ERROR);
    } else {
        if ($itemid == 0) {
            redirect($return_index, get_string('taskadded', 'tool_richardnz'), 2,
                    notification::NOTIFY_SUCCESS);
        } else {
            redirect($return_index, get_string('taskupdated', 'tool_richardnz'),
                    2, notification::NOTIFY_SUCCESS);
        }
    }
}

// Verify user has capability to view.
if (has_capability('tool/richardnz:edit', $context)) {

    // Start output to browser.
    echo $OUTPUT->header();
    echo $OUTPUT->heading($title, 2);
    $mform->display();

} else {
    echo get_string('nopermission', 'tool_richardnz');
}
// End the page properly: IMPORTANT!
echo $OUTPUT->footer();