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
		require_once($CFG->dirroot.'/calendar/lib.php');
		/* TAKING ACTIONS */
		if ( isset($form->actionparam) ) {
		
			if ($form->actionparam=='addclasshour'){
				$classhour->starthour = strftime ( "%H:%M", strtotime( $form->starthr . ':' . $form->startmin));
				$classhour->endhour = strftime ( "%H:%M", strtotime( $form->endhr . ':' . $form->endmin));
				
				if ((strtotime($classhour->starthour)>=strtotime($classhour->endhour))) {
					$message->text = get_string('invalidinsertedhours','block_timetable');
					$message->color = 'RED';
				}else{
					insert_record("timetable_hours",$classhour) or die(mysql_error());
				}
				//El tercer parametre es per retornar l'ID, no en hi ha i no em fa falta'
				$actionparam='';
			}
			if ($form->actionparam=='delclasshour'){
					$confirm = optional_param('confirm', PARAM_ALPHANUM);
					
					if (isset($confirm) && ($confirm==1)){
						delete_records("timetable_hours", "starthour", $form->starthour, "endhour",$form->endhour) or die(mysql_error());
						delete_records("timetable_hourtable","starthour", $form->starthour, "endhour",$form->endhour) or die(mysql_error());
						$actionparam='';
					}else{
				
						$toconfirm = "confirmdeleteclasshour";
					}				
			}
		}
		/* SETTING VARIABLES*/
		$currenttab='editclasshours';
		$classhours = get_records("timetable_hours",null,null,'starthour ASC,endhour ASC');
		$display->minwday = get_user_preferences('calendar_startwday', CALENDAR_STARTING_WEEKDAY);
		$display->maxwday = $display->minwday + 6;
?>