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
echo format_text(get_string('param', 'tool_richardnz', $id));

// Set up some example strings.
$userinputs = array();
$userinputs[] = 'no <b>tags</b> allowed in strings';
$userinputs[] = '<span class="multilang" lang="en">RIGHT</span><span class="multilang" lang="fr">WRONG</span>';
$userinputs[] = 'a" onmouseover=”alert(\'XSS\')" asdf="';
$userinputs[] = "3>2";
$userinputs[] = "2<3"; // Interesting effect, huh?
$example = 0;

// Output example strings using Moodle functions.
foreach ($userinputs as $userinput) {
    $example++;
    echo '<br><br>';
    echo '<h4>Example ' . $example . '</h4>';
    // Used when you want to escape the value.
    echo html_writer::div(s($userinput));
    // Used for one-line strings, such as forum post subject.
    echo html_writer::div(format_string($userinput));
    // Used for multil-line rich-text contents such as forum post body.
    echo html_writer::div(format_text($userinput));
}

// End the page properly: IMPORTANT!
echo $OUTPUT->footer();