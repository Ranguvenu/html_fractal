<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or localify
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

abstract class evaluation_item_base {

    /** @var string type of the element, should be overridden by each item type */
    protected $type;

    /** @var evaluation_item_form */
    protected $item_form;

    /** @var stdClass */
    protected $item;

    /**
     * constructor
     */
    public function __construct() {
    }

    /**
     * Displays the form for editing an item
     *
     * this function only can used after the call of build_editform()
     */
    public function show_editform() {
        return $this->item_form->render();
    }

    /**
     * Checks if the editing form was cancelled
     *
     * @return bool
     */
    public function is_cancelled() {
        return $this->item_form->is_cancelled();
    }

    /**
     * Gets submitted data from the edit form and saves it in $this->item
     *
     * @return bool
     */
    public function get_data() {
        if ($this->item !== null) {
            return true;
        }
        if ($this->item = $this->item_form->get_data()) {
            return true;
        }
        return false;
    }

    /**
     * Set the item data (to be used by data generators).
     *
     * @param stdClass $itemdata the item data to set
     * @since Moodle 3.3
     */
    public function set_data($itemdata) {
        $this->item = $itemdata;
    }

    /**
     * Creates and returns an instance of the form for editing the item
     *
     * @param stdClass $item
     * @param stdClass $evaluation
     */
    abstract public function build_editform($item, $evaluation);

    /**
     * Saves the item after it has been edited (or created)
     */
    abstract public function save_item();

    /**
     * Converts the value from complete_form data to the string value that is stored in the db.
     * @param mixed $value element from local_evaluation_complete_form::get_data() with the name $item->typ.'_'.$item->id
     * @return string
     */
    public function create_value($value) {
        return strval($value);
    }

    /**
     * Compares the dbvalue with the dependvalue
     *
     * @param stdClass $item
     * @param string $dbvalue is the value input by user in the format as it is stored in the db
     * @param string $dependvalue is the value that it needs to be compared against
     */
    public function compare_value($item, $dbvalue, $dependvalue) {
        return strval($dbvalue) === strval($dependvalue);
    }

    /**
     * Wether this item type has a value that is expected from the user and saved in the stored values.
     * @return int
     */
    public function get_hasvalue() {
        return 1;
    }

    /**
     * Wether this item can be set as both required and not
     * @return bool
     */
    public function can_switch_require() {
        return true;
    }

    /**
     * Adds summary information about an item to the Excel export file
     *
     * @param object $worksheet a reference to the pear_spreadsheet-object
     * @param integer $row_offset
     * @param stdClass $xls_formats see analysis_to_excel.php
     * @param object $item the db-object from evaluation_item
     * @param integer $groupid
     * @param integer $courseid
     * @return integer the new row_offset
     */
    abstract public function excelprint_item(&$worksheet, $row_offset,
                                      $xls_formats, $item,
                                      $groupid, $courseid = false);

    /**
     * Prints analysis for the current item
     *
     * @param $item the db-object from evaluation_item
     * @param string $itemnr
     * @param integer $groupid
     * @param integer $courseid
     * @return integer the new itemnr
     */
    abstract public function print_analysed($item, $itemnr = '', $groupid = false, $courseid = false);

    /**
     * Prepares the value for exporting to Excel
     *
     * @param object $item the db-object from evaluation_item
     * @param string $value a item-related value from evaluation_values
     * @return string
     */
    abstract public function get_printval($item, $value);

    /**
     * Returns the formatted name of the item for the complete form or response view
     *
     * @param stdClass $item
     * @param bool $withpostfix
     * @return string
     */
    public function get_display_name($item, $withpostfix = true) {
        //return format_text($item->name, FORMAT_HTML, array('noclean' => true, 'para' => false)) .
        //        ($withpostfix ? $this->get_display_name_postfix($item) : '');
        return format_text($item->name, FORMAT_HTML, array('noclean' => true, 'para' => false));
    }

    /**
     * Returns the postfix to be appended to the display name that is based on other settings
     *
     * @param stdClass $item
     * @return string
     */
    public function get_display_name_postfix($item) {
        return '';
    }

    /**
     * Adds an input element to the complete form
     *
     * This method is called:
     * - to display the form when user completes evaluation
     * - to display existing elements when teacher edits the evaluation items
     * - to display the evaluation preview (print.php)
     * - to display the completed response
     * - to preview a evaluation template
     *
     * If it is important which locale the form is in, use $form->get_locale()
     *
     * Each item type must add a single form element with the name $item->typ.'_'.$item->id
     * This element must always be present in form data even if nothing is selected (i.e. use advcheckbox and not checkbox).
     * To add an element use either:
     * $form->add_form_element() - adds a single element to the form
     * $form->add_form_group_element() - adds a group element to the form
     *
     * Other useful methods:
     * $form->get_item_value()
     * $form->set_element_default()
     * $form->add_validation_rule()
     * $form->set_element_type()
     *
     * The element must support freezing so it can be used for viewing the response as well.
     * If the desired form element does not support freezing, check $form->is_frozen()
     * and create a static element instead.
     *
     * @param stdClass $item
     * @param local_evaluation_complete_form $form
     */
    abstract public function complete_form_element($item, $form);

    /**
     * Returns the list of actions allowed on this item in the edit module
     *
     * @param stdClass $item
     * @param stdClass $evaluation
     * @return action_menu_link[]
     */
    public function edit_actions($item, $evaluation) {
        global $OUTPUT, $PAGE;
        $actions = array();

        $strupdate = $OUTPUT->pix_icon('t/edit', $strupdate, 'moodle', array('class' => 'iconsmall', 'title' => '')).get_string('edit_item', 'local_evaluation');
   //      $actions['update'] = html_writer::link("#displayform",$strupdate, array('onclick'=>'(
			// 			  function(e){
			// require("local_evaluation/newevaluation").displayquestion(' . $item->id . ', ' . $evaluation->id . ')
			// })(event)'));
        $params = json_encode(array('evalid' => $evaluation->id, 'itemid' => $item->id));
        $actions['update'] = html_writer::link('javascript:void(0)', $strupdate, array('data-fg' => 'u', 'data-method' => 'addnew_question', 'data-plugin' => 'local_evaluation', 'class' => ' mb-2 pull-right', 'data-id' => $evaluation->id, 'data-params' => $params));

        if ($this->can_switch_require()) {
            if ($item->required == 1) {
                $buttontitle = get_string('switch_item_to_not_required', 'local_evaluation');
                $buttonimg = 'required';
            } else {
                $buttontitle = get_string('switch_item_to_required', 'local_evaluation');
                $buttonimg = 'notrequired';
            }
            $actions['required'] = new action_menu_link_secondary(
                new moodle_url('/local/evaluation/edit.php', array('id' => $evaluation->id,
                    'switchitemrequired' => $item->id, 'sesskey' => sesskey())),
                new pix_icon($buttonimg, $buttontitle, 'local_evaluation', array('class' => 'iconsmall', 'title' => '')),
                $buttontitle,
                array('class' => 'editing_togglerequired', 'data-action' => 'togglerequired')
            );
        }

        $strdelete = get_string('delete_item', 'local_evaluation');
        $actions['delete'] = new action_menu_link_secondary(
            new moodle_url('/local/evaluation/edit.php', array('id' => $evaluation->id, 'deleteitem' => $item->id, 'sesskey' => sesskey())),
            new pix_icon('t/delete', $strdelete, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $strdelete,
            array('class' => 'editing_delete', 'data-action' => 'delete')
        );

        return $actions;
    }

    /**
     * Return extra data for external functions.
     *
     * Some items may have additional configuration data or default values that should be returned for external functions:
     * - Info elements: The default value information (course or category name)
     * - Captcha: The recaptcha challenge hash key
     *
     * @param stdClass $item the item object
     * @return str the data, may be json_encoded for large structures
     */
    public function get_data_for_external($item) {
        return null;
    }

    /**
     * Return the analysis data ready for external functions.
     *
     * @param stdClass $item     the item (question) information
     * @param int      $groupid  the group id to filter data (optional)
     * @param int      $courseid the course id (optional)
     * @return array an array of data with non scalar types json encoded
     * @since  Moodle 3.3
     */
    abstract public function get_analysed_for_external($item, $groupid = false, $courseid = false);
}

//a dummy class to realize pagebreaks
class evaluation_item_pagebreak extends evaluation_item_base {
    protected $type = "pagebreak";

    public function show_editform() {
    }

    /**
     * Checks if the editing form was cancelled
     * @return bool
     */
    public function is_cancelled() {
    }
    public function get_data() {
    }
    public function build_editform($item, $evaluation) {
    }
    public function save_item() {
    }
    public function create_value($data) {
    }
    public function get_hasvalue() {
        return 0;
    }
    public function excelprint_item(&$worksheet, $row_offset,
                            $xls_formats, $item,
                            $groupid, $courseid = false) {
    }

    public function print_analysed($item, $itemnr = '', $groupid = false, $courseid = false) {
    }
    public function get_printval($item, $value) {
    }
    public function can_switch_require() {
        return false;
    }

    /**
     * Adds an input element to the complete form
     *
     * @param stdClass $item
     * @param local_evaluation_complete_form $form
     */
    public function complete_form_element($item, $form) {
        $form->add_form_element($item,
            ['static',
                $item->typ.'_'.$item->id,
                '',
                html_writer::empty_tag('hr', ['class' => 'evaluation_pagebreak', 'id' => 'evaluation_item_' . $item->id])
            ]);
    }

    /**
     * Returns the list of actions allowed on this item in the edit locale
     *
     * @param stdClass $item
     * @param stdClass $evaluation
     * @return action_menu_link[]
     */
    public function edit_actions($item, $evaluation) {
        $actions = array();
        $strdelete = get_string('delete_pagebreak', 'local_evaluation');
        $actions['delete'] = new action_menu_link_secondary(
            new moodle_url('/local/evaluation/edit.php', array('id' => $evaluation->id, 'deleteitem' => $item->id, 'sesskey' => sesskey())),
            new pix_icon('t/delete', $strdelete, 'moodle', array('class' => 'iconsmall', 'title' => '')),
            $strdelete,
            array('class' => 'editing_delete', 'data-action' => 'delete')
        );
        return $actions;
    }

    /**
     * Return the analysis data ready for external functions.
     *
     * @param stdClass $item     the item (question) information
     * @param int      $groupid  the group id to filter data (optional)
     * @param int      $courseid the course id (optional)
     * @return array an array of data with non scalar types json encoded
     * @since  Moodle 3.3
     */
    public function get_analysed_for_external($item, $groupid = false, $courseid = false) {
        return;
    }
}
