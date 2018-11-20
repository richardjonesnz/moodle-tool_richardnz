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

require_once(__DIR__ . '/../../../config.php');
global $DB;

//  Add an optional parameter to the page.  Add to the url.
$id = optional_param('id', 1, PARAM_INT);
$url = new moodle_url('/admin/tool/richardnz/index.php', ['id' => $id]);
$title = get_string('pluginname', 'tool_richardnz');

// Setup the page.
$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($title);
$PAGE->set_heading(get_string('index_header', 'tool_richardnz'));

// Start output to browser.
echo $OUTPUT->header();
echo $OUTPUT->heading($title, 2);
echo get_string('greeting', 'tool_richardnz');

// Get some user data.
$records = $DB->get_records('user', [], null, 'id, firstname, lastname, city');

// Build an html table.
$table = new html_table();

// Headers: explicit but should be in language file really.
$table->head = array('id', 'First name', 'Last name', 'Location');
$table->align =array('left', 'left', 'left', 'left');
$table->wrap =array('nowrap', 'nowrap', 'nowrap', '');
$table->cellspacing = 0;
$table->cellpadding = '2px';
$table->width = '80%';

// Build all the other rows.
foreach($records as $record) {
    $data = array();
    $data[] = $record->id;
    $data[] = $record->firstname;
    $data[] = $record->lastname;
    $data[] = $record->city;
    $table->data[] = $data;
}

echo html_writer::table($table);

// End the page properly: IMPORTANT!
echo $OUTPUT->footer();