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
 * Form to add or edit tasks in the list.
 *
 * @package    tool_richardnz
 * @copyright  2018 Richard Jones <richardnz@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace tool_richardnz\local;
require_once('../../../lib/formslib.php');

defined('MOODLE_INTERNAL') || die;

class task_form extends \moodleform {

    public function definition() {

        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name', 'tool_richardnz'),
                ['size' => '40']);
        $mform->setType('name', PARAM_NOTAGS);

        $mform->addElement('advcheckbox', 'completed',
                get_string('completed', 'tool_richardnz'));
        $mform->setDefault('completed', 0);

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'itemid', $this->_customdata['itemid']);
        $mform->setType('itemid', PARAM_INT);

        $this->add_action_buttons($cancel = true);
    }
}