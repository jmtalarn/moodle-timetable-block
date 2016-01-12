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
/**
 * Sets up the tabs used by the timetable pages based on the users capabilites.
 * @package timetable
 */
 

$context = get_context_instance(CONTEXT_BLOCK, $timetableid);

if (!isset($currenttab)) {
    $currenttab = '';
}

//$sesskey = required_param('sesskey', PARAM_ALPHANUM);	

$tabs = array();
$row  = array();
$inactive = array();
$activated = array();

    //'mod/timetable:timetable';
    //'mod/timetable:editclassroom';
    //'mod/timetable:editclasshours';
    //'mod/timetable:editgroup';
    //'mod/timetable:readgroup';


if (has_capability('block/timetable:editclassroom', $context)) {
    $row[] = new tabobject('editclassroom', "$CFG->wwwroot/blocks/timetable/controller/ttcontroller.php?viewrequired=editclassroom&timetableid=".$timetableid."&courseid=".$courseid."&sesskey=".$sesskey."", get_string('classroom', 'block_timetable'));
}
if (has_capability('block/timetable:editclasshours', $context)) {
    $row[] = new tabobject('editclasshours', "$CFG->wwwroot/blocks/timetable/controller/ttcontroller.php?viewrequired=editclasshours&timetableid=".$timetableid."&courseid=".$courseid."&sesskey=" .$sesskey."", get_string('classhours', 'block_timetable'));
}
if (has_capability('block/timetable:editgroup', $context)) {
    $row[] = new tabobject('editgroup', "$CFG->wwwroot/blocks/timetable/controller/ttcontroller.php?viewrequired=editgroup&timetableid=".$timetableid."&courseid=".$courseid."&sesskey=" .$sesskey."", get_string('groups', 'block_timetable'));
}
if (has_capability('block/timetable:readgroup', $context)) {
    $row[] = new tabobject('readgroup', "$CFG->wwwroot/blocks/timetable/controller/ttcontroller.php?viewrequired=readgroup&timetableid=".$timetableid."&courseid=".$courseid."&sesskey=" .$sesskey."", get_string('viewgroups', 'block_timetable'));
}
    $tabs[] = $row;

echo '<div style="position: relative; float:right;z-index: 1;">';
helpbutton($currenttab, get_string('help', 'block_timetable'), "timetable", true, true);
echo '</div>';

print_tabs($tabs, $currenttab, $inactive, $activated);

if (isset($message)) { printexistingmessage($message); }



?>
