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
		/* TAKING ACTIONS*/
		if (isset($form->joingroup)){
					$confirm = optional_param('confirm', PARAM_ALPHANUM);
							global $USER;	
							$userdata->groupid = $form->groupid;
							$userdata->userid = $USER->id;
							
							$studentsenlisted = count_records("timetable_users",'groupid',$form->groupid);
							$group = get_record("timetable_group",'id',$form->groupid);
							$groupstudentsnumber = $group->studentsnumber;
							$freeplaces = $groupstudentsnumber-$studentsenlisted;

					if (isset($confirm) && ($confirm==1)){

							if ($freeplaces>=1){
								//A Timetable_users no est� commongroup ni changeallowed,delete_records("timetable_users",'userid',$USER->id,'commongroup',0,'changeallowed',1);
								//Groups change allowed non commongroup for this timetable
								
								$gcanc = get_records_select("timetable_group",'commongroup=0 and changeallowed=1 and timetableid="'.$timetableid.'"');
								foreach ($gcanc as $aGroup){	
									delete_records("timetable_users",'userid',$USER->id,'groupid',$aGroup->id);
								}
								insert_record("timetable_users",$userdata);
								$form->readaction='hourtable';
							}else{
								$message->text = get_string('noplacesingroup','block_timetable');
								$message->color = 'RED';
							}
					}else{
				
						$toconfirm = "confirmjoingroup";
					}				

		}
		if (isset($form->deletestudent)){
					$confirm = optional_param('confirm', PARAM_ALPHANUM);
	
					if (isset($confirm) && ($confirm==1)){
						delete_records("timetable_users",'userid',$form->userid,'groupid',$form->groupid);
						$form->readaction='students';
					}else{
				
						$toconfirm = "confirmdeletestudentfromgroup";
					}
		}
		if ( isset($form->readaction) ) {
			if ($form->readaction=='students'){
				$context = get_context_instance(CONTEXT_BLOCK, $timetableid);
				$studentslist = get_records("timetable_users",'groupid',$form->groupid);
			}
			if ($form->readaction=='hourtable'){
				require_once($CFG->dirroot.'/calendar/lib.php');
				$validhours = get_records("timetable_hours",null,null,'starthour ASC,endhour ASC');
				$display->minwday = get_user_preferences('calendar_startwday', CALENDAR_STARTING_WEEKDAY);
				$display->maxwday = $display->minwday + 6;
			}
			if ($form->readaction=='attendance'){
				if ( !isset($form->data1day) && !isset($form->data2day) ){ 
					$data2=strtotime("now");
					$data1=strtotime("-7 days");
				}else{
					// Converteix $data2 i $data1 amb strtotime	
					$data2=mktime(23,59,59,$form->data2month,$form->data2day,$form->data2year);
					$data1=mktime(00,00,00,$form->data1month,$form->data1day,$form->data1year);
				}
				// Comprova que $data2 >= $data1
				if($data1>$data2){
					$message->text = get_string('data1greaterthandata2','block_timetable');
					$message->color = 'RED';
				}else{
					//FES LES QUERYS	
					$studentslist = get_records("timetable_users",'groupid',$form->groupid);
				
					$query2 = "data<='".strftime("%D",$data2)."' and data>='".strftime("%D",$data1)."' and groupid=".$form->groupid."";
					
					
					$classesdone = get_records_select("timetable_hourtable_log",$query2);
					//Amb la data de timetable_hourtable_log i la starthour i la endhour es podrà comprovar que el usuari hagi entrat
					//Canviar horainici i horafi de timezone user a timezone server
					foreach($classesdone as $classe){

						foreach($studentslist as $student){
							$userid=$student->userid;
							
							$day = (int) strftime("%d",strtotime($classe->data));
							$month = (int) strftime("%m",strtotime($classe->data));
							$year = (int) strftime("%Y",strtotime($classe->data));
							
							$hour= (int) strftime("%H",strtotime($classe->starthour));
							$minute = (int) strftime("%M",strtotime($classe->starthour));
							
							//$horainici=mktime($hour,$minute,0,$month,$day,$year);
							$horainici = make_timestamp($year, $month, $day,$hour,$minute, 0, $CFG->timezone);
							$hour= (int) strftime("%H",strtotime($classe->endhour));
							$minute = (int) strftime("%M",strtotime($classe->endhour));

							//$horafi=mktime ($hour,$minute,0,$month,$day,$year);							
							$horafi = make_timestamp($year, $month, $day,$hour,$minute, 0, $CFG->timezone);
							$query= "userid=".$userid." and time>=".$horainici." and time<=".$horafi."";
													
							

							$log=get_records_select("log",$query);						

							//A veure que farem amb aquest registre!Posar IP i compararla amb les ipClassrooms

							if ($log==''){
									

									$resultcell->ip=get_string('noaccess','block_timetable');
									$resultcell->accesstime="";
									$resultcell->color="BLACK";
									
									$result_logs[$userid][$classe->id][]=array("ip"=>$resultcell->ip,"accesstime"=>$resultcell->accesstime,"color"=>$resultcell->color);

							}else{
								$oldtime='HH:MM';
								foreach($log as $l){
									$accesstime = strftime ( "%H:%M",$l->time);
									$at=usergetdate($l->time,$CFG->timezone);
									$accesstime=$at['hours'].":".$at['minutes'];
									if ($oldtime!=$accesstime){ //Evitar mostrar els accessos diferenciats de pocs segons
											$resultcell->ip = $l->ip;
											
											$resultcell->accesstime=$accesstime;
											
											if (record_exists("timetable_classroomip_log","ip",$l->ip,"hourtable_log_id",$classe->id) ){ $resultcell->color = "BLACK"; }else{ $resultcell->color = "RED"; }

											$result_logs[$userid][$classe->id][]=array("ip"=>$resultcell->ip,"accesstime"=>$resultcell->accesstime,"color"=>$resultcell->color);
											$oldtime=$accesstime;
									}
								}
							}
								
							
							 
						}
						unset($ips);
						unset($validips);
					}
					
					
				//S'haurà de veure com se li especifica la taula, si amb esquema o sense!
					$query= "SELECT * FROM moodle.mdl_log l LEFT JOIN moodle.mdl_timetable_users u ON l.userid=u.userid Where u.groupid=".$form->groupid." and l.time>=".$data1." and l.time<=".$data2."";
					$registreslogin = get_records_sql($query);
						
				}
						

			} //readaction=='attendance'
		} //Fi del read Action
		
		/* SETTING VARIABLES */
		$studentsenlisted = count_records("timetable_users",'groupid',$form->groupid);
		if (isset($form->groupid)){
			$group = get_record("timetable_group",'id',$form->groupid);
			$groupstudentsnumber = $group->studentsnumber;
			$freeplaces = $groupstudentsnumber-$studentsenlisted;
		}
				
		$currenttab='readgroup';
		$groups = get_records("timetable_group",'timetableid',$timetableid);

?>
