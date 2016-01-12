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
		/*TAKING ACTIONS*/
		$editinghourtable = false;

		if ( isset($form->actionparam) ) {
			
			if ($form->actionparam=='add'){
				$group->id = $form->groupid;
				$group->studentsnumber = $form->groupstudentsnumber;
				
				if ( (isset($form->groupchangeallowed))&&(($form->groupchangeallowed)==1) ){
					$group->changeallowed= $form->groupchangeallowed;
				}else{
					$group->changeallowed= 0;
				}
				if ( (isset($form->commongroup)) && (($form->commongroup)==1) ){
					$group->commongroup= $form->commongroup;
				}else{
					$group->commongroup= 0;
				}
				$group->timetableid = $timetableid;
				$group->name = $form->groupname;
						
				insert_record("timetable_group",$group) or die(mysql_error());
				
				$actionparam='';
			}
			if ($form->actionparam=='action'){
						
				if ($form->actiontype=='delete'){
					$confirm = optional_param('confirm', PARAM_ALPHANUM);
					
					if (isset($confirm) && ($confirm==1)){
						$gId = $form->groupid;
						delete_records("timetable_group", "id", $gId);
						delete_records("timetable_hourtable","groupid",$gId);
						delete_records("timetable_users","groupid",$gId);
					}else{
				
						$toconfirm = "confirmdeletegroup";
					}
			
				}
				if ($form->actiontype=='update'){
					$group->id = $form->groupid;
					$group->studentsnumber = $form->groupstudentsnumber;
					if (isset($form->groupchangeallowed)){
						$group->changeallowed= $form->groupchangeallowed;
					}else{
						$group->changeallowed= 0;
					}
					if (isset($form->commongroup)){
						$group->commongroup= $form->commongroup;
					}else{
						$group->commongroup= 0;
					}
					$group->name = $form->groupname;
					update_record("timetable_group", $group) or die(mysql_error());
				}
				if ($form->actiontype=='edithourtable'){ 
					
					$editinghourtable=true;
					$viewrequired = 'editinghourtable';
					$group->timetableid = $timetableid;
					$group->id = $form->groupid;
				}
				if ($form->actiontype=='addallcoursestudents'){
					$confirm = optional_param('confirm', PARAM_ALPHANUM);
					
					if (isset($confirm) && ($confirm==1)){
							$usersincourse = get_records('course_display','course',$courseid);
							if ($usersincourse==''){
								$message->text = get_string('nousersincourse','block_timetable');
								$message->color = 'BLUE';
								
							}else{
								foreach ($usersincourse as $userincourse){
									$userdata->groupid = $form->groupid;
									$userdata->userid = $userincourse->userid;
									
									if (!(record_exists("timetable_users",'userid',$userdata->userid,'groupid',$userdata->groupid))){
										insert_record("timetable_users",$userdata);
									}
									
									
								}
							}
					}else{
				
						$toconfirm = "confirmaddallstudentstogroup";
					}
					}
			}
		}
		/*SETTING VARIABLES*/
		$currenttab='editgroup';
		$groups = get_records("timetable_group",'timetableid',$timetableid);
		
		
		if ((isset($form->actiontype))&&($form->actiontype=='edithourtable') ){
				require_once($CFG->dirroot.'/calendar/lib.php');
				$group = get_record("timetable_group",'timetableid',$timetableid,'id',$form->groupid);
				$validhours = get_records("timetable_hours",null,null,'starthour ASC,endhour ASC');
				$display->minwday = get_user_preferences('calendar_startwday', CALENDAR_STARTING_WEEKDAY);
				$display->maxwday = $display->minwday + 6;
		}

?>