<?php
/**
 * This file is part of eAbyas
 *
 * Copyright eAbyas Info Solutons Pvt Ltd, India
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author eabyas  <info@eabyas.in>
 */
defined('MOODLE_INTERNAL') || die();
function xmldb_local_learningplan_install(){
    global $CFG,$DB;
    /*notifictaions content*/
    $dbman = $DB->get_manager(); // loads ddl manager and xmldb classes
    $table = new xmldb_table('local_notification_type');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('shortname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('parent_module', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('pluginname', XMLDB_TYPE_CHAR, '255', null, null, null, '0');

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        // $table->add_key('primary', XMLDB_KEY_FOREIGN, array('id'));
        $result = $dbman->create_table($table);
    }
    $table = new xmldb_table('local_notification_info');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('costcenterid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('notificationid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        
        $table->add_field('moduletype', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        // $table->add_field('shortname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('moduleid', XMLDB_TYPE_TEXT, null, null, null, null, null);
        // courses
        $table->add_field('reminderdays', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('completiondays', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('enable_cc', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('subject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('body', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, '0');
        $table->add_field('adminbody', XMLDB_TYPE_TEXT, null, null, null, null, '0');
        $table->add_field('attachment_filepath', XMLDB_TYPE_CHAR, null, null, null, null, '0');
        $table->add_field('status', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        
        $table->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('foreign', XMLDB_KEY_FOREIGN, array('costcenterid'));
        // $table->add_key('foreign', XMLDB_KEY_FOREIGN, array('notificationid'));
        // $table->add_key('foreign', XMLDB_KEY_FOREIGN, array('notificationid'));
        $result = $dbman->create_table($table);
    }
    $table = new xmldb_table('local_emaillogs');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('notification_infoid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('from_userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('to_userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        
        $table->add_field('from_emailid', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('to_emailid', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        // $table->add_field('shortname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('moduletype', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('moduleid', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('teammemberid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        // courses
        $table->add_field('reminderdays', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('enable_cc', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('subject', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('emailbody', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, '0');
        $table->add_field('adminbody', XMLDB_TYPE_TEXT, null, null, null, null, '0');
        $table->add_field('attachment_filepath', XMLDB_TYPE_CHAR, null, null, null, null, '0');
        $table->add_field('status', XMLDB_TYPE_INTEGER, 10, null, null, null, '0');
        
        $table->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');

        $table->add_field('sent_date', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('sent_by', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        // $table->add_key('foreign', XMLDB_KEY_FOREIGN, array('costcenterid'));
        // $table->add_key('foreign', XMLDB_KEY_FOREIGN, array('notificationid'));
        // $table->add_key('foreign', XMLDB_KEY_FOREIGN, array('notificationid'));
        $result = $dbman->create_table($table);
    }
    $table = new xmldb_table('local_notification_strings');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('module', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        
        $table->add_field('usercreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        // $table->add_field('pluginname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, '0');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        // $table->add_key('primary', XMLDB_KEY_FOREIGN, array('id'));
        $result = $dbman->create_table($table);
    }
    $time = time();
    $initcontent = array('name' => 'Learning Path','shortname' => 'learningplan','parent_module' => '0','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL, 'pluginname' => 'learningplan');
    $parentid = $DB->get_field('local_notification_type', 'id', array('shortname' => 'learningplan'));
    if(!$parentid){
        $parentid = $DB->insert_record('local_notification_type', $initcontent);
    }
    $notification_type_data = array(
        array('name' => 'Learning Path Enrollment','shortname' => 'learningplan_enrol','parent_module' => $parentid,'usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL, 'pluginname' => 'learningplan'),
        array('name' => 'Learning Path Unenrollment','shortname' => 'learningplan_unenrol','parent_module' => $parentid,'usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL, 'pluginname' => 'learningplan'),
        array('name' => 'Learning Path Completion','shortname' => 'learningplan_completion','parent_module' => $parentid,'usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL, 'pluginname' => 'learningplan'),
        // array('name' => 'Learning Path Reminder','shortname' => 'lep_reminder','parent_module' => $parentid,'usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL, 'pluginname' => 'learningplan')
    );
    foreach($notification_type_data as $notification_type){
        unset($notification_type['timecreated']);
        if(!$DB->record_exists('local_notification_type',  $notification_type)){
            $notification_type['timecreated'] = $time;
            $DB->insert_record('local_notification_type', $notification_type);
        }
    }
    $strings = array(
        array('name' => '[lep_name]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_course]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_department]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_status]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_enroluserfulname]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_enroluseremail]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_completiondate]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_creator]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_type]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_link]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL),
        array('name' => '[lep_unenroldate]','module' => 'learningplan','usercreated' => '2','timecreated' => $time,'usermodified' => 2,'timemodified' => NULL)
    );
    foreach($strings as $string){
        unset($string['timecreated']);
        if(!$DB->record_exists('local_notification_strings', $string)){
            $string_obj = (object)$string;
            $string_obj->timecreated = $time;
            $DB->insert_record('local_notification_strings', $string_obj);
        }
    }  
    $corecomponent = new \core_component();
    $pluginexist = $corecomponent::get_plugin_directory('local','certificates');
    if($pluginexist){
        $table = new xmldb_table('local_learningplan');
       if ($dbman->table_exists($table)) {
        $field = new xmldb_field('certificateid', XMLDB_TYPE_INTEGER, 10, null, null, null);
           if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
           }   
        }
    }


    $table = new xmldb_table('local_lp_unenrolments_log'); 
    
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('lpathid', XMLDB_TYPE_INTEGER, '10', XMLDB_NOTNULL, null, null, null);
        $table->add_field('coursetypeid', XMLDB_TYPE_INTEGER, '10', XMLDB_NOTNULL, null, null, null);
        $table->add_field('coursetype', XMLDB_TYPE_CHAR, '255', XMLDB_NOTNULL, null, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', XMLDB_NOTNULL, null, null, null);
        $table->add_field('unenrol_reason', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('time', XMLDB_TYPE_INTEGER, '10', null, null, null, 0);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $dbman->create_table($table);
    }
    
}
