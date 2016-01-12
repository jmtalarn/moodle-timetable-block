<?php
/*****************************************************************************************************
*
*	NOTICE OF COPYRIGHT    
*	
*	This file is part of Timetable Block for Moodle.
*
*    	Timetable Module for Moodle.
*    	Copyright (C) 2009 onwards Joan Maria Talarn
*
*    	This program is free software; you can redistribute it and/or modify it under the terms of the 
*    	GNU General Public License as published by the Free Software Foundation; either version 3 of the License, 
*    	or (at your option) any later version.
*
*    	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
*    	without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
*    	See the GNU General Public License for more details.
*
*    	You should have received a copy of the GNU General Public License along with this program.  
*      If not, see <http://www.gnu.org/licenses/>
*
****************************************************************************************************/  
//
// Capability definitions for the timetable block.
//
// The capabilities are loaded into the database table when the module is
// installed or updated. Whenever the capability definitions are updated,
// the module version number should be bumped up.
//
// The system has four possible values for a capability:
// CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT, and inherit (not set).
//
//
// CAPABILITY NAMING CONVENTION
//
// It is important that capability names are unique. The naming convention
// for capabilities that are specific to modules and blocks is as follows:
//   [mod/block]/<component_name>:<capabilityname>
//
// component_name should be the same as the directory name of the mod or block.
//
// Core moodle capabilities are defined thus:
//    moodle/<capabilityclass>:<capabilityname>
//
// Examples: mod/forum:viewpost
//           block/recent_activity:view
//           moodle/site:deleteuser
//
// The variable name for the capability definitions array follows the format
//   $<componenttype>_<component_name>_capabilities
//
// For the core capabilities, the variable is $moodle_capabilities.


$block_timetable_capabilities = array(

    'block/timetable:timetable' => array(

        'riskbitmask' => RISK_SPAM,

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),

    'block/timetable:editclassroom' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'admin' => CAP_ALLOW
        )
    ),
    'block/timetable:readclassroom' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),
    'block/timetable:editclasshours' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'admin' => CAP_ALLOW
        )
    ),
    'block/timetable:readclasshours' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),
    'block/timetable:editgroup' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'editingteacher' => CAP_ALLOW
        )
    ),
    'block/timetable:readgroup' => array(

        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'admin' => CAP_ALLOW
        )
    ),
    'block/timetable:seeattendance' => array(
    
    		'captype' => 'read',
    		'contextlevel' => CONTEXT_SYSTEM,
    		'legacy' => array(
    			'teacher' => CAP_ALLOW,
    			'editingteacher' => CAP_ALLOW
    		)
    ),
    'block/timetable:joingroup' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'legacy' => array(
            'student' => CAP_ALLOW
        )
    )

);

?>
