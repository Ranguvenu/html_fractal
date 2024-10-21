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

 * Learning Summary Inprogress block caps.
 *
 * @author eabyas  <info@eabyas.in>
 * @package    fractal
 * @subpackage block_learningsummary_inprogress
 */

defined('MOODLE_INTERNAL') || die;
require_once("$CFG->libdir/externallib.php");
require_once($CFG->libdir.'/enrollib.php');
use block_learningsummary_inprogress\output\blockview;
use block_learningsummary_inprogress\plugin;
class block_learningsummary_inprogress_external extends external_api{


/**
     * Returns description of method parameters.
     *
     * @return external_function_parameters.
     */
  
  public static function get_summary_content_parameters() {
        return new external_function_parameters([
             'options' => new external_value(PARAM_RAW, 'The paging data for the service'),
             'dataoptions' => new external_value(PARAM_RAW, 'The data for the service'),
             'offset' => new external_value(PARAM_INT, 'Number of items to skip from the begging of the result set',
                    VALUE_DEFAULT, 0),
             'limit' => new external_value(PARAM_INT, 'Maximum number of results to return',
                    VALUE_DEFAULT, 0),
             'filterdata' => new external_value(PARAM_RAW, 'The data for the service'),
        ]);
    }

    /**
     * Gets the list of suggested course for the given criteria. The suggested course
     * will be exported in a summaries format and won't include all of the
     * suggested course data.
     *
     * @param int $userid Userid id to find suggested course
     * @param int $contextid The context id where the suggested course will be rendered
     * @param int $limit Maximum number of results to return
     * @param int $offset Number of items to skip from the beginning of the result set.
     * @return array The list of Learning Summary Inprogress and total challenge count.
     */
    public static function get_summary_content($options,$dataoptions,$offset = 0,$limit = 0, $filterdata) {
        global $PAGE;
        $sitecontext = context_system::instance();

        require_login();
        $PAGE->set_url('/my/index.php', array());
        $PAGE->set_context($sitecontext);
        // Parameter validation.
        $params = self::validate_parameters(
            self::get_summary_content_parameters(),
            [
                'options' => $options,
                'dataoptions' => $dataoptions,
                'offset' => $offset,
                'limit' => $limit,
                'filterdata' => $filterdata
            ]
        );
        $data_object = (json_decode($dataoptions));
        $offset = $params['offset'];
        $limit = $params['limit']; 
        $filtervalues = json_decode($filterdata);
       
        $stable = new \stdClass();
        $stable->sort = 'visible DESC, sortorder ASC';
        $stable->fields = 'summary, summaryformat, enddate,startdate';
        $stable->thead = false;
        $stable->search =$data_object->search_query;
        $stable->start = $offset;
        $stable->length = $limit;

        if ($data_object->blocktype == 'inprogress') {
            $totalcourses=plugin::get_inprogress_content($stable,$filtervalues,$data_object);
        } 
        $totalcount=$totalcourses['allcoursecount']; 

        $data = array();
        if($totalcount>0){
            $config = $data_object;
            $renderer = $PAGE->get_renderer(plugin::COMPONENT);
            $data = array_merge($data,$renderer->render(new blockview($config,$stable,$filtervalues)));
        }

        $nomycourselister=new \stdClass();
        return [
            'totalcount' => $totalcount,
            'records' =>$data,
            'options' => $options,
            'dataoptions' => $dataoptions,
            'filterdata' => $filterdata,
            'nodata' => get_string('nocourselister',plugin::COMPONENT,$nomycourselister)
        ];
    }

    /**
     * Returns description of method result value.
     */
    public static function get_summary_content_returns() {
        return new external_single_structure([
            'options' => new external_value(PARAM_RAW, 'The paging data for the service'),
            'dataoptions' => new external_value(PARAM_RAW, 'The data for the service'),
            'totalcount' => new external_value(PARAM_INT, 'total number of inprogress courses in result set'),
            'filterdata' => new external_value(PARAM_RAW, 'The data for the service'),
            'nodata' => new external_value(PARAM_RAW, 'The data for the service'),
            'records' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'coursenums' => new external_value(PARAM_INT, ' numbers'),
                                    'courseid' => new external_value(PARAM_INT, 'id'),
                                    'url' => new external_value(PARAM_RAW,'url',VALUE_OPTIONAL),
                                    'title' => new external_value(PARAM_RAW, 'title'),
                                    'description' => new external_value(PARAM_RAW, 'course description', VALUE_OPTIONAL),
                                    'coursetype' => new external_value(PARAM_RAW, 'coursetype', VALUE_OPTIONAL),
                                    'imageurl' => new external_value(PARAM_RAW, 'imageurl', VALUE_OPTIONAL),
                                    'iconurl' => new external_value(PARAM_RAW, 'iconurl', VALUE_OPTIONAL),
                                    'iconalt' => new external_value(PARAM_RAW, 'iconalt', VALUE_OPTIONAL),
                                    'progress' => new external_value(PARAM_RAW, 'progress', VALUE_OPTIONAL),
                                    'progressflag' => new external_value(PARAM_RAW, 'progressflag', VALUE_OPTIONAL),
                                 )
                            )
                  )
        ]);
    }

}
