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

print_header_simple(get_string('editclasshours','block_timetable'),'',	get_string('editclasshours','block_timetable'),'','',false);		

include('../tabs.php');


	$optionsyes= data_submitted();
	if ($viewrequired=='readgroup'){
		$optionsno = array('sesskey'=>$optionsyes->sesskey, 'courseid'=>$optionsyes->courseid,'timetableid'=>$optionsyes->timetableid,'groupid'=>$optionsyes->groupid);
	
	}else{
		$optionsno = array('sesskey'=>$optionsyes->sesskey, 'courseid'=>$optionsyes->courseid,'timetableid'=>$optionsyes->timetableid);
	}	
		notice_yesno(get_string($toconfirm,'block_timetable'), "../controller/ttcontroller.php?viewrequired=$viewrequired&confirm=1",
                                                   "../controller/ttcontroller.php?viewrequired=$viewrequired", $optionsyes,$optionsno);




print_footer(get_record('course', 'id',$courseid));
?>