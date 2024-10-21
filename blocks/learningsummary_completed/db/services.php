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
/**
 * Course list block caps.
 *
 * @author eabyas  <info@eabyas.in>
 * @package    Bizlms
 * @subpackage block_learningsummary_completed
 */

defined('MOODLE_INTERNAL') || die;
$functions = array(
  
    'block_learningsummary_completed_getcontent' => array(
        'classname' => 'block_learningsummary_completed_external',
        'methodname' => 'get_summary_content',
        'classpath'   => 'blocks/learningsummary_completed/classes/external.php',
        'description' => 'Get Completed courses for a user',
        'type' => 'read',
        'ajax' => true,
    ),
);
