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
	function printexistingmessage($message){
		echo '<FONT COLOR="'.$message->color.'">';
		echo $message->text;
		echo '</FONT>';
		echo '<BR>';
	}
	
	function selectMaxUserAccesses($userid,$result_logs,$classesdone){
		$max=1;
		foreach($classesdone as $classe){
			$array = $result_logs[$userid][$classe->id];
			if($array!=''){ 
				if(count($array)>$max){
					$max = count($array);
				}
			}
		}
		return $max;																
	}
function validarip($ip) {

            return ( filter_var($ip, FILTER_VALIDATE_IP) );
      
}

function validarip_backup($ip) { 
	return ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) ? FALSE : TRUE;
}

function print_classhour_delete_form($courseid,$sesskey,$timetableid,$validhour,$i,$group,$weekend){
	$selectclause =  "starthour='" .$validhour->starthour."' AND endhour='".$validhour->endhour."' AND weekday='" . $i ."' AND groupid = " . $group->id . "";
	$c = get_record_select('timetable_hourtable',$selectclause);// or die(mysql_error());
	$classroom = get_record('timetable_classroom','id',$c->classroomid);
	global $USER;
	
	echo '<form action = "../controller/ttcontroller.php?viewrequired=editinghourtable" method = "POST">';
		echo '<input type="hidden" name="sesskey" value="'.$USER->sesskey.'" >';
		echo '<input type="hidden" name="courseid" value="'.$courseid.'" >';
		echo '<input type="hidden" name="timetableid" value="'.$timetableid.'" >';
		
		echo '<input type="hidden" name="groupid" value="'.$group->id.'" >';
		echo '<input type="hidden" name="actionparam" value="action" >';
		echo '<input type="hidden" name="actiontype" value="edithourtable" >';
		echo '<input type="hidden" name="actionedithourtable" value="delete" >';
		echo '<input type="hidden" name="starthour" value="'.$validhour->starthour.'" >';
		echo '<input type="hidden" name="endhour" value="'.$validhour->endhour.'" >';
		echo '<input type="hidden" name="weekday" value="'.$i.'" >';
		echo '<input type="hidden" name="classroomid" value="'.$c->classroomid.'" >';
		echo '<FONT';
		
		if (($classroom->capacity<$group->studentsnumber)||$weekend) {
			$title='';
			if ($weekend) {
				
				$title = $title.get_string('classhouraddedinweekendday','block_timetable');
			}
			
			if ($classroom->capacity<$group->studentsnumber){
				if ($title!=''){ $title=$title.' and '; }
				$title = $title.get_string('expectedstudentsnumberlessthancapacity','block_timetable');
			}
	
			echo ' COLOR="BLUE" title="'.$title.'">';
			
		}else{ 
			echo '>';
		}
			
		echo '<font size="-2">'.$classroom->name.'</font><BR>';
		echo '</FONT>';
		
		
		echo '<input type="image" name="boton" src="../icons/action_stop.gif" width="16" height="16" title="'.get_string('deleteclassroom','block_timetable').'" >';
		
	echo '</form>';
 }


	function print_classhour_add_form($courseid,$sesskey,$timetableid,$validhour,$i,$group,$weekend){
		global $CFG;
		global $USER;
		echo '<form action = "../controller/ttcontroller.php?viewrequired=editinghourtable" method = "POST">';
			echo '<input type="hidden" name="sesskey" value="'.$USER->sesskey.'" >';
			echo '<input type="hidden" name="courseid" value="'.$courseid.'" >';	
			echo '<input type="hidden" name="timetableid" value="'.$timetableid.'" >';
			
			echo '<input type="hidden" name="groupid" value="'.$group->id.'" >';
			echo '<input type="hidden" name="actionparam" value="action" >';
			echo '<input type="hidden" name="actiontype" value="edithourtable" >';
			echo '<input type="hidden" name="actionedithourtable" value="add" >';
			echo '<input type="hidden" name="starthour" value="'.$validhour->starthour.'" >';
			echo '<input type="hidden" name="endhour" value="'.$validhour->endhour.'" >';
			echo '<input type="hidden" name="weekday" value="'.$i.'" >';
			if ($weekend==true) {
				echo '<input type="hidden" name="isweekend" value="true" >';
			}else{
				echo '<input type="hidden" name="isweekend" value="false" >';
			}
			$selectclause =  "starthour='" .$validhour->starthour."' AND endhour='".$validhour->endhour."' AND weekday='" . $i . "'";
			$busyclassrooms = get_records_select('timetable_hourtable',$selectclause); // or die(mysql_error());
	
			$first=true;
			
			if ($busyclassrooms!=''){
				
				$querybusyclassrooms='';
				foreach ($busyclassrooms as $busyclassroom){
					if (!$first) { $querybusyclassrooms = $querybusyclassrooms.','; }
					$querybusyclassrooms=$querybusyclassrooms.$busyclassroom->classroomid;
					$first=false;	
				}
				$freeclassrooms =  get_records_sql("SELECT * FROM {$CFG->prefix}timetable_classroom tc WHERE tc.id not in(".$querybusyclassrooms.")");
			}else{
				$freeclassrooms = get_records('timetable_classroom');				
			}
			
			if ( $freeclassrooms=='' ) {
				echo '<font size="-2">';
				echo get_string('noclassroomsavailable','block_timetable');
				echo '</font>';
			}else{													
		
				echo '<select name="classroomid" STYLE="font-size : 8pt;width: 130px;">';
				foreach ($freeclassrooms as $freeclassroom){
					echo '<option value="'.$freeclassroom->id.'">';
					echo $freeclassroom->name.','.$freeclassroom->capacity;
					echo '</option>';
				}
				echo '</select><BR>';
						
				echo '<input type="image" name="boton" src="../icons/action_go.gif" width="16" height="16" title="'.get_string('updateclassroom','block_timetable').'">';
				
			}
		echo '</form>';
	}
	
//Funció per timetable Lib
function printdeletestudent($courseid,$sesskey,$timetableid,$groupid,$userdataid){
	$context = get_context_instance(CONTEXT_BLOCK, $timetableid);
	global $USER;
	if (has_capability('block/timetable:editgroup', $context)) {
		echo '<td class="cell">';
		echo '<form action = "../controller/ttcontroller.php?viewrequired=readgroup" method = "POST">';
					echo '<input type="hidden" name="sesskey" value="'.$USER->sesskey.'" >';
					echo '<input type="hidden" name="courseid" value="'.$courseid.'" >';	
					echo '<input type="hidden" name="timetableid" value="'.$timetableid.'" >';
					
					echo '<input type="hidden" name="deletestudent" value="deletestudent" >';
					echo '<input type="hidden" name="groupid" value="'.$groupid.'" >';
					echo '<input type="hidden" name="userid" value="'.$userdataid.'" >';
					echo '<input value='.get_string('deletestudent','block_timetable').' type="submit">';
		echo '</form>';	
		echo '</td>';	
	}
}

function printjoinggroup($courseid,$sesskey,$timetableid,$groupid) { 
	global $USER;
	$context = get_context_instance(CONTEXT_BLOCK, $timetableid);
	
	if (has_capability('block/timetable:joingroup', $context)) {
	
		$thisgroup = get_record("timetable_group",'id',$groupid);		
		if (record_exists("timetable_users",'userid',$USER->id,'groupid',$groupid)){
			echo get_string('groupjoinedbyuser','block_timetable').'<BR>';
		}else{
			$selectclause = 'timetableid="'.$timetableid.'" and commongroup=0';
			$groups = get_records_select("timetable_group",$selectclause);
			foreach ($groups as $group){
				if (record_exists("timetable_users",'userid',$USER->id,'groupid',$group->id)){
					$existsingroup = get_record("timetable_group",'id',$group->id);
				}
			}
		
			if (( isset($existsingroup)&&($existsingroup->changeallowed))||!isset($existsingroup)){
				echo '<form action = "../controller/ttcontroller.php?viewrequired=readgroup" method = "POST">';
					echo '<input type="hidden" name="sesskey" value="'.$USER->sesskey.'" >';
					echo '<input type="hidden" name="courseid" value="'.$courseid.'" >';
					echo '<input type="hidden" name="timetableid" value="'.$timetableid.'" >';
					
					echo '<input type="hidden" name="joingroup" value="joingroup" >';
					echo '<input type="hidden" name="groupid" value="'.$groupid.'" >';
					echo '<input value='.get_string('joingroup','block_timetable').' type="submit">';
				echo '</form>';	
			}
			if ( isset($existsingroup)&&!($existsingroup->changeallowed)){
				echo get_string('useringroupwithnochangeallowed','block_timetable');
				echo '<BR>';
			}	
		}
	}	
}

?>
