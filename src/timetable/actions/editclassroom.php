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
			if ( isset($form->actionparam) ) {
			
				if ($form->actionparam=='add'){
					
					
					$classroom->id = $form->classroomid;
					$classroom->name = $form->classroomname;
					$classroom->capacity =$form->classroomcapacity;
				
					
					insert_record("timetable_classroom",$classroom) or die(mysql_error());
					$actionparam='';
					
					
				}
				if ($form->actionparam=='action'){
			
					if ($form->actiontype=='delete'){
						$confirm = optional_param('confirm', PARAM_ALPHANUM);
						
						if (isset($confirm) && ($confirm==1)){
							$cId = $form->classroomid;
							delete_records("timetable_classroom", "id", $cId);
							
							delete_records("timetable_hourtable","classroomid",$cId);
							delete_records("timetable_classroomip", "classroomid", $cId);
						}else{
					
							$toconfirm = "confirmdeleteclassroom";
						}
						
					}
					if ($form->actiontype=='update'){
						$classroom->id = $form->classroomid;
						$classroom->name = $form->classroomname;
						$classroom->capacity =$form->classroomcapacity;
						update_record("timetable_classroom", $classroom) or die(mysql_error());
						
					}
					if ($form->actiontype=='editclassroomips'){ 
					
						$viewrequired = 'editingclassroomips';
					
					}
				}
			}
			/*SETTING VARIABLES */
			$currenttab='editclassroom';
			if ((isset($form->actiontype))&&($form->actiontype=='editclassroomips') ){
				$ipslist= get_records("timetable_classroomip",'classroomid',$form->classroomid);
				$classroom = get_record("timetable_classroom",'id',$form->classroomid) or die(mysql_error());
			}else{
					$classrooms = get_records("timetable_classroom");
			}
?>