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
 * Prepares table data for a Mustache template.
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace tool_richardnz\local;
defined('MOODLE_INTERNAL') || die;
use \tool_richardnz\local\debugging;
use \tool_richardnz\local\utilities;
class table_data {

    /**
     * Return data for the table header row
     *
     * @return array $headerdata - table column headers
     */
    public static function get_table_headers() {
        $headerdata = array();
        $headerdata[] = get_string('id', 'tool_richardnz');
        $headerdata[] = get_string('name', 'tool_richardnz');
        $headerdata[] = get_string('description', 'tool_richardnz');
        $headerdata[] = get_string('attached', 'tool_richardnz');
        $headerdata[] = get_string('priority', 'tool_richardnz');
        $headerdata[] = get_string('completed', 'tool_richardnz');
        $headerdata[] = get_string('timecreated', 'tool_richardnz');
        $headerdata[] = get_string('timemodified', 'tool_richardnz');
        $headerdata[] = get_string('action', 'tool_richardnz');

        return $headerdata;
    }
    /**
     * Return data for the table rows
     * @param integer $courseid - relevant course id.
     * @param boolean $canedit if true use can edit entries
     * @param boolean $candelete if true use can delete entries
     * @param stdClass $context
     * @return object - data for the mustache renderer
     */
    public static function get_table_data($courseid, $canedit, $candelete,
            $context) {
        global $DB;

        $records = $DB->get_records('tool_richardnz',
            ['courseid' => $courseid], null, 'id, courseid, name,
            description, completed, priority, timecreated,
            timemodified');

        $table = new \stdClass();
        $table->class = 'tool_richardnz_table';
        $table->caption = get_string('tasks', 'tool_richardnz');
        // Check for adding the add task link.
        if ($canedit) {
            $table->addlink = ['url' => new \moodle_url(
                    '/admin/tool/richardnz/edit.php', ['id' => $courseid]),
            'text' => get_string('add_link', 'tool_richardnz')];
        }
        $table->courselink = ['url' => new \moodle_url(
                '/course/view.php',
                ['id' => $courseid]),
                'text' => get_string('course_link', 'tool_richardnz')];
        $table->tableheaders = self::get_table_headers();
        $table->tabledata = array();

        $formatoptions = new \stdClass;
        $formatoptions->noclean = true;
        $formatoptions->overflowdiv = true;
        $formatoptions->context = $context;

        foreach($records as $record) {
            $data = array();
            $data['id'] = $record->id;
            // Contains user input, clean with format_string.
            $data['name'] = format_string($record->name);
            $description = file_rewrite_pluginfile_urls(
                    $record->description, 'pluginfile.php',
                    $context->id, 'tool_richardnz', 'description',
                    $record->id);
            $data['description'] = format_text($description, FORMAT_HTML,
                    $formatoptions);
            $data['attachment'] = self::get_attached_file(
                    $context->id,
                    $record->id);
            $data['priority'] = $record->priority;
            $data['completed'] =
                    $record->completed == 1 ? 'yes' : 'no';
            $data['timecreated'] = $record->timecreated;
            $data['timemodified'] = $record->timemodified;
            // Add the edit/delete links.
            if ($canedit) {
                $url = new \moodle_url('edit.php',
                        ['id' => $record->courseid,
                         'itemid' => $record->id]);
                $icon = ['icon' => 't/edit', 'component' => 'core',
                         'alt'=> get_string(
                         'editlink', 'tool_richardnz')];
                $data['editlink'] = ['link' => $url->out(false),
                        'icon' => $icon];
            } else {
                $data['editlink'] = '-';
            }
            // delete link (add the sesskey!).
            // Note: we make itemid negative to flag deletion required.
            if ($candelete) {
                $url = new \moodle_url('edit.php',
                        ['id' => $record->courseid,
                         'itemid' => -$record->id,
                         'sesskey' => sesskey()]);
                $icon = ['icon' => 't/delete', 'component' => 'core',
                         'alt'=> get_string(
                         'deletelink', 'tool_richardnz')];
                $data['deletelink'] = ['link' => $url->out(false),
                        'icon' => $icon];
            } else {
                $data['deletelink'] = '-';
            }
            $table->tabledata[] = $data;
        }

        return $table;
    }

    /**
     * Save data for a task in the database
     * @param integer $id - relevant course id.
     * @param integer $itemid - relevant task id.
     * @param object data - data from the edit form.
     * @param stdClass $context
     * @param array $options - editor field options
     * @return integer - id of inserted record.
     */
    public static function save_table_data($id, $itemid, $data,
            $context, $options, $fileoptions) {
        global $DB;
        if ($itemid == 0) {
            // A new task to add.
            if (!self::name_exists($id, $data->name)) {
                $data->courseid = $id;
                $data->timecreated = time();
                $data->timemodified = time();
                $data->description = ' ';
                $data->descriptionformat = FORMAT_HTML;
                $itemid = $DB->insert_record('tool_richardnz', $data);

                // We have the record id now. Massage the data.
                $data->id = $itemid;
                $data = file_postupdate_standard_editor(
                        $data,
                        'description',  // editor field.
                        $options,
                        $context,
                        'tool_richardnz',
                        'description', // file area.
                        $itemid);

                // Any attachments?
                $data = file_postupdate_standard_filemanager(
                        $data,
                        'attachment',
                        $fileoptions,
                        $context,
                        'tool_richardnz',
                        'attachment',
                        $itemid);

                // Update the record with full editor data
                $DB->update_record('tool_richardnz', $data);
                return $data->id;
            }
            // Duplicate task name.
            return -1;
        } else {
            // A task to update.
            $data->id = $itemid;
            $data->timemodified = time();
            // Update editor field contents.
            $data = file_postupdate_standard_editor(
                        $data,
                        'description',
                        $options,
                        $context,
                        'tool_richardnz',
                        'description',
                        $itemid);
            // Update attachment data.
            $data = file_postupdate_standard_filemanager(
                        $data,
                        'attachment',
                        $fileoptions,
                        $context,
                        'tool_richardnz',
                        'attachment',
                        $itemid);
            $DB->update_record('tool_richardnz', $data);
            return $itemid;
        }
    }
    /**
     * Check task name for duplicate
     * @param integer $id - relevant course id.
     * @param object $name - task name to check
     * @return boolean - true if name of task is already in database for this course.
     */
    public static function name_exists($id, $name) {
        global $DB;

        return $DB->record_exists('tool_richardnz',
                ['name' => $name, 'courseid' => $id]);
    }
    /**
     * Get the data for a task given its id
     * @param integer $id - relevant item id.
     * @return object - the data record for the task.
     */
    public static function get_task($id) {
        global $DB;
        return $DB->get_record('tool_richardnz', ['id' => $id], '*',
                MUST_EXIST);
    }

    public static function get_attached_file($contextid, $itemid) {
        global $CFG;
        // Note we only allow 1 file to be attached.
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'tool_richardnz', 'attachment',
                $itemid, "filename", false);

        if ($files) {
            foreach ($files as $file) {
                $filename = $file->get_filename();
                $fileurl = file_encode_url($CFG->wwwroot . '/pluginfile.php',
                    '/' . $contextid . '/tool_richardnz/attachment/' . $itemid .
                    '/' . $filename);
                return \html_writer::link($fileurl, $filename);
            }
        } else {
            return '-';
        }
    }
}