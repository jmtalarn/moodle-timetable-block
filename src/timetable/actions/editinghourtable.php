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
		/* TAKING ACTIONS */
		if ( isset($form->actionedithourtable) ) {	
			if ($form->actionedithourtable=='add'){
				$hourtable->groupid = $form->groupid;
				$hourtable->starthour = $form->starthour;
				$hourtable->endhour = $form->endhour;
				$hourtable->weekday = $form->weekday;
				$hourtable->classroomid = $form->classroomid;
				
				$classtocheck = get_record("timetable_classroom",'id',$hourtable->classroomid);
				$grouptocheck = get_record("timetable_group",'id',$hourtable->groupid);
				
				if ($classtocheck->capacity<$grouptocheck->studentsnumber){
				 	$message->text= get_string('expectedstudentsnumberlessthancapacity','block_timetable');
				 	$message->color = 'BLUE';
				}
				if ($form->isweekend=='true'){
				 	$message->text = get_string('classhouraddedinweekendday','block_timetable');
				 	$message->color = 'BLUE';
				}
				insert_record("timetable_hourtable",$hourtable) or die(mysql_error());
				$form->actionedithourtable='';
			}
			if ($form->actionedithourtable=='delete'){	

						$clausedelete = "starthour='" .$form->starthour."' AND endhour='".$form->endhour."' 
						AND weekday='" . $form->weekday . "' AND groupid='". $form->groupid."' 
						AND classroomid='". $form->classroomid . "'";
						
						delete_records_select("timetable_hourtable", $clausedelete);
						$form->actionedithourtable='';
		
			}
		}
		/* SETTING VARIABLES */
		$currenttab='editgroup';
		$group = get_record("timetable_group",'timetableid',$timetableid,'id',$form->groupid);
		require_once($CFG->dirroot.'/calendar/lib.php');
		$validhours = get_records("timetable_hours",null,null,'starthour ASC,endhour ASC');
		$display->minwday = get_user_preferences('calendar_startwday', CALENDAR_STARTING_WEEKDAY);
		$display->maxwday = $display->minwday + 6;
?>