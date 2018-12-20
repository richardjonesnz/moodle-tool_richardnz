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
 * Defines the version and other meta-info about the plugin
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://moodledev.moodle.school/mod/page/view.php?id=50
 */
use \tool_richardnz\local\table_data;
use \tool_richardnz\local\debugging;
use \core\output\notification;
use \tool_richardnz\event\tasklist_viewed;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
global $DB;

// The course id.
$id = required_param('id', PARAM_INT);
$url = new moodle_url('/admin/tool/richardnz/index.php', ['id' => $id]);
$title = get_string('pluginname', 'tool_richardnz');

// Setup the page.
$context_course = context_course::instance($id);
$context_system = context_system::instance();
$PAGE->set_context($context_system);
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($title);
$PAGE->set_heading(get_string('index_header', 'tool_richardnz'));
$PAGE->requires->js_call_amd('tool_richardnz/alert', 'init', $PAGE->url);
$renderer = $PAGE->get_renderer('tool_richardnz');

require_login();

// Start output to browser.
echo $renderer->header();
echo $renderer->heading($title, 2);
echo get_string('greeting', 'tool_richardnz');
// Verify user has capability to view.
if (has_capability('tool/richardnz:view', $context_course)) {
    // Get some task data.
    $canedit = has_capability('tool/richardnz:edit', $context_course);
    $candelete = has_capability('tool/richardnz:delete', $context_course);
    $data = table_data::get_table_data($id, $canedit, $candelete,
            $context_course);
    echo $renderer->print_table($data);

    // Log the module viewed event.
    $event = tasklist_viewed::create(array(
        'objectid' => $id,
        'context' => $context_course,
    ));
    $course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);
    $event->add_record_snapshot('course', $course);
    $event->trigger();
} else {
    echo '<p>' . get_string('nopermission', 'tool_richardnz') . '</p>';
}
// End the page properly: IMPORTANT!
echo $renderer->footer();