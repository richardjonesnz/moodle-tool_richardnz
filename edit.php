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
use \tool_richardnz\local\utilities;
use \tool_richardnz\event\task_added;
use \tool_richardnz\event\task_edited;
use \tool_richardnz\event\task_deleted;
use \core\output\notification;

require_once(__DIR__ . '/../../../config.php');
global $DB;

// Course id.
$id = required_param('id', PARAM_INT);
$itemid = optional_param('itemid', 0, PARAM_INT);
// If itemid is non zero, we came from an edit link.
// If itemid is negative, we came from a deletelink.
// Otherwise we are adding a new task.
if ($itemid != 0) {
    // Get the data for this item.
    $data = table_data::get_task(abs($itemid));
    $id = $data->courseid;
    if ($itemid > 0) {
        $title = get_string('edit_title', 'tool_richardnz');
    } else {
        $title = get_string('delete_title', 'tool_richardnz');
    }
} else {
    $title = get_string('add_title', 'tool_richardnz');
}

$url = new moodle_url('/admin/tool/richardnz/edit.php',
            ['id' => $id, 'itemid' => $itemid]);

// Setup the page.
$context_course = context_course::instance($id);
$context_system = context_system::instance();
$PAGE->set_context($context_system);
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($title);
$PAGE->set_heading(get_string('edit_header', 'tool_richardnz'));

$options = utilities::get_editor_options($context_course);
$fileoptions = utilities::get_file_options();
$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);


$return_index = new moodle_url('/admin/tool/richardnz/index.php',
        ['id' => $id]);
require_login(get_course($id));
$mform = new task_form(null, ['id' => $id, 'itemid' => $itemid,
        'context' => $context_course]);

// We have existing data in the database.
if ($itemid > 0) {
    // Process the editor and attachment field data.
    $data = file_prepare_standard_editor( $data, 'description',
            $options, $context_course, 'tool_richardnz', 'description',
            $itemid);
    $data = file_prepare_standard_filemanager($data, 'attachment',
            $fileoptions, $context_course, 'tool_richardnz', 'attachment',
            $itemid);
    $mform->set_data($data);
}

// Check for cancel button.
if ($mform->is_cancelled()) {
    redirect($return_index, get_string('cancelled'), 2);
}

// Check for delete link.
if ($itemid < 0) {
    // Additional capability required to delete a task
    if (has_capability('tool/richardnz:delete', $context_course)) {
        require_sesskey();
        $DB->delete_records('tool_richardnz', ['id' => abs($itemid)]);
        // Log the deleted event.
            $event = task_deleted::create(array(
                    'objectid' => $id,
                    'context' => $context_course,
            ));
            $event->add_record_snapshot('course', $course);
            $event->trigger();

        redirect($return_index, get_string('taskdeleted', 'tool_richardnz'), 2,
                    notification::NOTIFY_SUCCESS);
    } else {
        echo get_string('nopermission', 'tool_richardnz');
    }
}
$success = 0;
if ($data = $mform->get_data()) {
    // We have data add/update the task.
    $data->id = null;
    $success = table_data::save_table_data($id, $itemid, $data,
            $context_course, $options, $fileoptions);
    if ($success == -1) {
        redirect($return_index,
                get_string('taskduplicate', 'tool_richardnz'), 2,
                notification::NOTIFY_ERROR);
    } else {
        if ($itemid == 0) {
            // Log the task added event.
            $event = task_added::create(array(
                    'objectid' => $id,
                    'context' => $context_course,
            ));
            $event->add_record_snapshot('course', $course);
            $event->trigger();
            redirect($return_index,
                    get_string('taskadded', 'tool_richardnz'), 2,
                    notification::NOTIFY_SUCCESS);
        } else {
            // Log the task edited event.
            $event = task_edited::create(array(
                    'objectid' => $id,
                    'context' => $context_course,
            ));
            $event->add_record_snapshot('course', $course);
            $event->trigger();

            redirect($return_index,
                    get_string('taskupdated', 'tool_richardnz'),
                    2, notification::NOTIFY_SUCCESS);
        }
    }
}
// Start output to browser.
echo $OUTPUT->header();
echo $OUTPUT->heading($title, 2);

// Verify user has capability to view the edit page.
if (has_capability('tool/richardnz:edit', $context_course)) {
    $mform->display();
} else {
    echo get_string('nopermission', 'tool_richardnz');
}
// End the page properly: IMPORTANT!
echo $OUTPUT->footer();