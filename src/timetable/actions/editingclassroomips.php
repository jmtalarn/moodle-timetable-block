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
			if ( isset($form->addip) ) { 
				if (validarip($form->ip)){
					$classroomip->classroomid = $form->classroomid;
					$classroomip->ip = $form->ip; 
					insert_record("timetable_classroomip",$classroomip) or die(mysql_error());
				}else{
					$message->text = get_string('invalidinsertedip','block_timetable');
					$message->color = 'RED';		
				}
			}
			if (isset($form->deleteip)){ 
				delete_records("timetable_classroomip", "classroomid", $form->classroomid, "ip",$form->ip) or die(mysql_error());
			}
			/*SETTING VARIABLES */
			$currenttab='editclassroom';
			$ipslist= get_records("timetable_classroomip",'classroomid',$form->classroomid);
			$classroom = get_record("timetable_classroom",'id',$form->classroomid);
?>