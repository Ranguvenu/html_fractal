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

namespace local_learningdashboard\output;

/**
 * Class learningdashboard
 *
 * @package    local_learningdashboard
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class learningdashboard {
    public function display_learnerview()
    {
        global $OUTPUT, $DB, $USER, $CFG;
        $sql = "SELECT lr.id,lr.name,lr.classes,lr.shortname
                FROM {local_regiment} AS lr WHERE 1 ";
        $params = [];
        if(!(is_siteadmin() || has_capability('local/classmanagement:is_central_user' ,\context_system::instance()))){
            $sql .= " AND lr.shortname LIKE :usertrgunit ";
            $params['usertrgunit'] = $USER->profile['trgunit'];
        }
        $regiments = [];//$DB->get_records_sql($sql, $params);
        $admindashboard = array();
        if(!empty($regiments)){
            $sno =1;
            foreach($regiments as $key => $reg){
                $admindashboard['regid'] = $reg->id;
                $admindashboardarr[] = $admindashboard;      
                $sno++;     
            }
        }
        return $OUTPUT->render_from_template('local_learningdashboard/learnerview', ['admindashboard'=>$admindashboardarr]);
    }
}
