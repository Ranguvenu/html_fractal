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
 * @package    block_ned_mentor
 * @copyright  Michael Gardener <mgardener@cissq.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . '/formslib.php');
$PAGE->requires->js('/blocks/ned_mentor/validation.js');

class notification_form extends moodleform {

    public function definition() {

        $mform =& $this->_form;

        $g2 = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['g2'])) {
            if ($this->_customdata['g2']) {
                $g2 = 'block_ned_mentor_checkbox_checked';
            }
        }

        $g4 = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['g4'])) {
            if ($this->_customdata['g4']) {
                $g4 = 'block_ned_mentor_checkbox_checked';
            }
        }

        $g4value = '';
        if (isset($this->_customdata['g4_value'])) {
            if ($this->_customdata['g4_value'] >= 0) {
                $g4value = $this->_customdata['g4_value'];
            }
        }

        $g6 = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['g6'])) {
            if ($this->_customdata['g6']) {
                $g6 = 'block_ned_mentor_checkbox_checked';
            }
        }

        $g6value = '';
        if (isset($this->_customdata['g6_value'])) {
            if ($this->_customdata['g6_value'] >= 0) {
                $g6value = $this->_customdata['g6_value'];
            }
        }

        $n1 = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['n1'])) {
            if ($this->_customdata['n1']) {
                $n1 = 'block_ned_mentor_checkbox_checked';
            }
        }

        $n1value = '';
        if (isset($this->_customdata['n1_value'])) {
            if ($this->_customdata['n1_value'] >= 0) {
                $n1value = $this->_customdata['n1_value'];
            }
        }

        $n2 = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['n2'])) {
            if ($this->_customdata['n2']) {
                $n2 = 'block_ned_mentor_checkbox_checked';
            }
        }

        $n2value = '';
        if (isset($this->_customdata['n2_value'])) {
            if ($this->_customdata['n2_value'] >= 0) {
                $n2value = $this->_customdata['n2_value'];
            }
        }

        $teacheremail = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['teacheremail'])) {
            if ($this->_customdata['teacheremail']) {
                $teacheremail = 'block_ned_mentor_checkbox_checked';
            }
        }
        $teachersms = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['teachersms'])) {
            if ($this->_customdata['teachersms']) {
                $teachersms = 'block_ned_mentor_checkbox_checked';
            }
        }

        $studentemail = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['studentemail'])) {
            if ($this->_customdata['studentemail']) {
                $studentemail = 'block_ned_mentor_checkbox_checked';
            }
        }
        $studentsms = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['studentsms'])) {
            if ($this->_customdata['studentsms']) {
                $studentsms = 'block_ned_mentor_checkbox_checked';
            }
        }

        $mentoremail = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['mentoremail'])) {
            if ($this->_customdata['mentoremail']) {
                $mentoremail = 'block_ned_mentor_checkbox_checked';
            }
        }
        $mentorsms = 'block_ned_mentor_checkbox';
        if (isset($this->_customdata['mentorsms'])) {
            if ($this->_customdata['mentorsms']) {
                $mentorsms = 'block_ned_mentor_checkbox_checked';
            }
        }

        $period = '';
        if (isset($this->_customdata['period'])) {
            if ($this->_customdata['period'] >= 0) {
                $period = $this->_customdata['period'];
            }
        }

        $mform->addElement('hidden', 'id', '');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'action', '');
        $mform->setType('action', PARAM_TEXT);

        $mform->addElement('text', 'name', get_string('rule_name', 'block_ned_mentor'));
        $mform->setType('name', PARAM_NOTAGS);

        $table = new html_table();
        $table->attributes['class'] = 'notification';

        $c1 = new html_table_cell();
        $c1->colspan = 2;
        $c1->header = true;
        $c1->text = get_string('whentosend', 'block_ned_mentor');
        $table->data[] = new html_table_row(array( $c1));

        $c1 = new html_table_cell();
        $c1->colspan = 2;

        $c1->text = (
            html_writer::tag('p', $g2('g2', 'g2', '_checkbox', 1) . ' Course Grade') .
            html_writer::tag('p', $g4('g4', 'g4', '_checkbox', 1) . ' Course Grade below ' .
                block_ned_mentor_textinput('g4_value', 'g4_value', '_textinput', $g4value) . ' %'
            ) .
            html_writer::tag('p', $g6('g6', 'g6', '_checkbox', 1) . ' Course Grade above ' .
                block_ned_mentor_textinput('g6_value', 'g6_value', '_textinput', $g6value) . ' %'
            ) .
            html_writer::tag('p', $n1('n1', 'n1', '_checkbox', 1) . ' No login for ' .
                block_ned_mentor_textinput('n1_value', 'n1_value', '_textinput', $n1value) . ' days <N1>'
            ) .
            html_writer::tag('p', $n2('n2', 'n2', '_checkbox', 1) . ' No activity for ' .
                block_ned_mentor_textinput('n2_value', 'n2_value', '_textinput', $n2value) . ' days <N2>'
            )
        );

        $table->data[] = new html_table_row(array( $c1));

        // Second row.
        $c1 = new html_table_cell();
        $c2 = new html_table_cell();
        $c1->header = true;
        $c2->header = true;
        $c1->text = get_string('whotosend', 'block_ned_mentor');
        $c2->text = get_string('howoften', 'block_ned_mentor');
        $table->data[] = new html_table_row(array( $c1, $c2));

        $c1 = new html_table_cell();
        $c2 = new html_table_cell();

        $c1->text = (
            html_writer::tag('table',
                html_writer::tag('tr',
                    html_writer::tag('td', 'Teacher').
                    html_writer::tag('td', $teacheremail('teacheremail', 'teacheremail', '_checkbox', 1) . ' Email').
                    html_writer::tag('td', $teachersms('teachersms', 'teachersms', '_checkbox', 1) . ' SMS')
                ).
                html_writer::tag('tr',
                    html_writer::tag('td', 'Mentor').
                    html_writer::tag('td', $mentoremail('mentoremail', 'mentoremail', '_checkbox', 1) . ' Email').
                    html_writer::tag('td', $mentorsms('mentorsms', 'mentorsms', '_checkbox', 1) . ' SMS')
                ).
                html_writer::tag('tr',
                    html_writer::tag('td', 'Student').
                    html_writer::tag('td', $studentemail('studentemail', 'studentemail', '_checkbox', 1) . ' Email').
                    html_writer::tag('td', $studentsms('studentsms', 'studentsms', '_checkbox', 1) . ' SMS')
                ),
                array('class' => 'block_ned_mentor_whotosend')
            )
        );

        $c2->text = (
            html_writer::tag('p', 'Every ' . block_ned_mentor_textinput('period', 'period', '_textinput', $period) . ' days')
        );

        $table->data[] = new html_table_row(array( $c1, $c2));

        // Apply To Header.
        $c1 = new html_table_cell();
        $c1->colspan = 2;
        $c1->header = true;
        $c1->text = "Appended Message (optional)";
        $table->data[] = new html_table_row(array( $c1));

        // Apply To Header.
        $c1 = new html_table_cell();
        $c1->colspan = 2;
        $c1->style = 'text-align: center;';

        $appendedmessage = '';
        if (isset($this->_customdata['appended_message'])) {
            $appendedmessage = $this->_customdata['appended_message'];
        }

        $c1->text = '<textarea name="appended_message" rows="6" cols="80">' .
                        $appendedmessage .
                    '</textarea>';
        $table->data[] = new html_table_row(array( $c1));

        // Apply To Header.
        $c1 = new html_table_cell();
        $c1->colspan = 2;
        $c1->header = true;
        $c1->text = "Apply to";
        $table->data[] = new html_table_row(array( $c1));

        $c1 = new html_table_cell();
        $c1->colspan = 2;

        $categories = block_ned_mentor_get_course_category_tree();

        $c1->text = block_ned_mentor_category_tree_form($categories,
                                       (isset($this->_customdata['category'])) ? $this->_customdata['category'] : '',
                                       (isset($this->_customdata['course'])) ? $this->_customdata['course'] : '');
        $table->data[] = new html_table_row(array( $c1));

        $mform->addElement('static', 'selectors', '', html_writer::table($table));

        $this->add_action_buttons();

    }
}
