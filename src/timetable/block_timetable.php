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
	
	  //require_once($CFG->dirroot.'/blocks/timetable/lib.php');
	 
	  class block_timetable extends block_list {
	 
		/// Functions
	
	    function init() {
	        $this->title = get_string('timetable', 'block_timetable');
	        $this->version = 2008090601;
			$this->cron = 1;  
			//El cron comprova això, si això es 1 executa la Funciónó cron d'aquest mateix objecte.
	    }
	    /**
		 * Per a carregar la configuració
		 *
		 */
		function specialization() {
			if (!isset($this->config->title))
			{
				$this->title = get_string('timetable', 'block_timetable');
			}else{
	    		$this->title = $this->config->title;
			}
					
		}
		
	    
	    /**
		 * Muestra el contenido de nuestro bloque.
		 * Es un bloque de tipo lista e instancia única.
		 */
	    function get_content() {
	    	global $COURSE;
	    	global $USER;
			global $CFG;
			global $CALENDARDAYS;
	    	///Chequeamos que haya contenido previo, para no recalcularlo
			if ($this->content !== NULL) {
	        	return $this->content;
	    	}
			
			///Instanciamos la clase
	    	$this->content = new stdClass;
	    	$this->content->items = array();
			$this->content->icons = array();
			$this->content->footer = "";
			//There is a $this->content->footer,$this->content->text as a string
			
				
			$context = get_context_instance(CONTEXT_BLOCK, $this->instance->id);
			
			$timetableid =  $this->instance->id;
			
	//'block/timetable:timetable';
	//'block/timetable:editclassroom';
	//'block/timetable:editclasshours';
        //'block/timetable:editgroup';
        //'block/timetable:readgroup';

			
			if (has_capability('block/timetable:timetable', $context)) {
	  			if (has_capability('block/timetable:editclassroom', $context)) {
	  				$this->content->items[] = '<a href=../blocks/timetable/controller/ttcontroller.php?viewrequired=editclassroom&timetableid='.$this->instance->id.'&courseid='.$COURSE->id.'&sesskey=' . sesskey(). '>' . get_string('editclassroom','block_timetable') . '</a>';
    				$this->content->icons[] = '<img src="../blocks/timetable/icons/editclassroom.png" width="16" height="16" alt="" />';
	  			}
	  			if (has_capability('block/timetable:editclasshours', $context)) {
	  				$this->content->items[] = '<a href=../blocks/timetable/controller/ttcontroller.php?viewrequired=editclasshours&timetableid='.$this->instance->id.'&courseid='.$COURSE->id.'&sesskey=' . sesskey(). '>' . get_string('editclasshours','block_timetable') . '</a>';
    				$this->content->icons[] = '<img src="../blocks/timetable/icons/editclasshours.png" width="16" height="16" alt="" />';
	  			}
	  			if (has_capability('block/timetable:editgroup', $context)) {
	  				$this->content->items[] = '<a href=../blocks/timetable/controller/ttcontroller.php?viewrequired=editgroup&timetableid='.$this->instance->id.'&courseid='.$COURSE->id.'&sesskey=' . sesskey(). '>' . get_string('editgroup','block_timetable') . '</a>';
    				$this->content->icons[] = '<img src="../blocks/timetable/icons/editgroup.png" width="16" height="16" alt="" />';
	  			}
	  			if (has_capability('block/timetable:readgroup', $context)) {
	  					$this->content->items[] = '<a href=../blocks/timetable/controller/ttcontroller.php?viewrequired=readgroup&timetableid='.$this->instance->id.'&courseid='.$COURSE->id.'&sesskey=' . sesskey(). '>' . get_string('readgroup','block_timetable') . '</a>';
    				$this->content->icons[] = '<img src="../blocks/timetable/icons/readgroup.png" width="16" height="16" alt="" />';	
	  			}
		
		
			$userid = $USER->id;
			
			$now3 = usergetdate(time());
			$timeNow = $now3['hours'].':'.$now3['minutes'];
			$diaenlletres = $now3['weekday'];
		
			if ($now3['wday']!=0) {
				$dayNow =  $now3['wday'];
			}else{
					$dayNow = 7;
			}
			
			//////$usergroups = get_records("timetable_users","userid",$userid);
			
			///////$timetablegroups = get_records("timetable_group","timetableid",$timetableid);

			$sql = "select * from mdl_timetable_hourtable m where m.groupid in (select l.groupid from mdl_timetable_users l left join mdl_timetable_group r on l.groupid=r.id where r.timetableid = '".$timetableid."' and l.userid ='".$userid."') order by weekday,starthour asc";
			
		//nextClassesCount = count_records_sql($sql);
			
		$allClasses = get_records_sql($sql);
		
		if ($allClasses!=false){
			require_once($CFG->dirroot.'/calendar/lib.php');
			
			$nextClasse = NULL;
			
			$flag=true;
			foreach ($allClasses as $classe){
				if ($flag==true){
					$nextClasse = $classe;
				}
				$flag=false;
				
				if ($dayNow<=$classe->weekday && $timeNow<=$classe->starthour){
									$nextClasse=$classe;
									break;
				}
			}

			
			$nextClasseClassroom = get_record("timetable_classroom","id",$nextClasse->classroomid);
		
			$nextClasseGrup = get_record("timetable_group","id",$nextClasse->groupid);
			
			if ($nextClasseGrup->name==""){
				$grupname  = get_string('group','block_timetable')." ".$nextClasseGrup->id;
			}else{
				$grupname  = $nextClasseGrup->name;
			}
		
			if ($nextClasseClassroom->name==""){
				$classroomname  = get_string('classroom','block_timetable')." ".$nextClasseClassroom->id;
			}else{
				$classroomname  = $nextClasseClassroom->name;
			}
			 		
			$message = "<b>".get_string('yournextclass','block_timetable').":&nbsp;</b><BR>";
		
			if ($nextClasse->weekday == $dayNow && $nextClasse->starthour>$timeNow){
						$message = $message.get_string( 'today' , 'calendar');
			}else{
						$message = $message.get_string( $CALENDARDAYS [ $nextClasse->weekday % 7] , 'calendar');
			}
			
			$message=$message." ".$nextClasse->starthour."<BR> ";
			$message = $message.$grupname." ".$classroomname;
		
			$texteProximaClasse='<div style="text-align:left;">'. $message .'</div>'."<br />\n";
		
			$this->content->footer = $texteProximaClasse;			
			
		}


		
			}else{
				$this->content->footer = notify( get_string('nocapabilities', 'block_timetable') ,'notifyproblem','center',false);	
				//$this->content->footer = notify( print_string('nocapabilities') ,'notifyproblem','center',false);
			}

		}
		
		/**
		 * Función para darle a nuestro bloque opciones de configuración específicas.
		 * Esto no se requiere en multi-instancia
		 */
		function instance_allow_config() {
			return false;
		}
		function has_config() {
    		return false;
		}
		
		/**
		 * Función para sobreescribir el array asociativo que determina en que formatos se ve el bloque.
		 * Sólo permitimos que se muestre en cursos.
		 */
		function applicable_formats() {
			
	    	return array('course-view' => true, 'site' => false);
		}
		
		//before_delete per esborrar totes les taules quan vulgui esborrar el block
		//Falta instance_create per afegir la instancia a la taula timetable
		/**
		* Función que sobreescribimos para eliminar toda la información referente a ese curso en 
		* nuestra base de datos cuando se elimina una instancia.
		*/
		function instance_delete(){
			
			global $COURSE;
			
			///Borramos todos los registros asociados a ese curso de las tablas user_bloque 
			///y bloque de entrega.
			
			$grupos = get_records('timetable_group','timetableid',$this->instance->id);
			
			foreach ($grupos as $grupo){
				//timetable_group
				//timetable_users
				//timetable_hourtable
			
				delete_records('timetable_users','groupid',$grupo->id);
			
				delete_records('timetable_hourtable','groupid',$grupo->id);
			
				delete_records('timetable_group','id',$grupo->id);
			
				delete_records('timetable_hourtable_log',$grupo->id);		
			}
			
			delete_records('timetable','instanceid',$this->instance->id);
			
			return true;
		}

		function instance_create(){
			
			global $COURSE;
			
			$obj_timetable->instanceid = $this->instance->id;
			$obj_timetable->courseid = $COURSE->id;
			$obj_timetable->name = "Timetable for ".$COURSE->shortname."";
			
			insert_record('timetable',$obj_timetable); 
			return true;
		}
		
	
		function before_delete(){
			drop_table('timetable_hourtable');
			drop_table('timetable_users');
			drop_table('timetable_group');
			drop_table('timetable');
			drop_table('timetable_classroom');
			drop_table("timetable_classroomip");
			drop_table('timetable_hours');
			drop_table("timetable_hourtable_log");
			drop_table("timetable_classroomip_log");
		}
		
		
		    function cron(){
				//Almenys s'haurà d'executar cada hora si es vol tenir un control sobre les classes fetes
				global $CFG;
								
				//require_once("../lib/moodlelib.php");
				mtrace();
			
				//$GMTtime = usertime(time(),date_default_timezone_get());

				//$offset = get_timezone_record($CFG->timezone);

				//$now = $GMTtime + ($offset->gmtoff * 60 );

				//$now2 = usertime(time(),$CFG->timezone);
				
				//$timeNow = strftime ( "%H:%M", $now);
				//$dayNow = strftime("%u",$now);
				//$dataNow = strftime("%D",$now);

			$now3 = usergetdate(time());
			$timeNow = $now3['hours'].':'.$now3['minutes'];
			if ($now3['wday']!=0) {
				$dayNow =  $now3['wday'];
			}else{
					$dayNow = 7;
			}
			$dataNow = $now3['mon'].'/'.$now3['mday'].'/'.substr($now3['year'], 2,2);
			
			mtrace("... time now is ".$timeNow." at weekday ".$dayNow." (".$now3['weekday'].")  and date ".$dataNow." the Server is located on ".date_default_timezone_get()." and the user is located in ".$CFG->timezone."");
			
			//mtrace('	$now2 is '.strftime("%H:%M", usertime(time(),$CFG->timezone) ));
			//mtrace('	$now is '.strftime("%H:%M", $now ));
			
			//mtrace('Experimento 2! '.$now3['hours'].':'.$now3['minutes'].'!!!');	
				
			
				//Mirar si hi ha alguna classe ara 
				$classesdone=get_records_select("timetable_hourtable", 'starthour<="'.$timeNow.'" and endhour>="'.$timeNow.'" and weekday='.$dayNow.''); 

				if ($classesdone!=false){
						foreach ($classesdone as $classe){
							
							$classeinlog=get_record_select("timetable_hourtable_log","starthour=".$classe->starthour." and endhour=".$classe->endhour." and weekday=".$classe->weekday." and groupid=".$classe->groupid." and classroomid=".$classe->classroomid." and data=".$dataNow."");
							
							if($classeinlog==false){
									$classeinlog->starthour = $classe->starthour;
									$classeinlog->endhour = $classe->endhour;
									$classeinlog->weekday = $classe->weekday;
									$classeinlog->groupid = $classe->groupid;
									$classeinlog->classroomid = $classe->classroomid;
									$classroom = get_record("timetable_classroom","id",$classe->classroomid);
									$classeinlog->name= $classroom->name;
									$classeinlog->data = $dataNow;
									
									//$classeinlogid=insert_record("timetable_hourtable_log",$classeinlog,true) or die(mysql_error());
									
									
									
									$classeinlogid=insert_record("timetable_hourtable_log",$classeinlog,true);
									$ips=get_records("timetable_classroomip","classroomid",$classe->classroomid);
									mtrace("....A class started at ".$classeinlog->starthour. " and will end at ".$classeinlog->endhour." in classroom ".$classeinlog->name." inserted with id ".$classeinlogid."");
									if($ips!='' && $classeinlogid!=0){

										foreach($ips as $ip){
												$classroomip_log->hourtable_log_id=$classeinlogid;
												$classroomip_log->ip=$ip->ip;
																								
												insert_record("timetable_classroomip_log",$classroomip_log);
										}
									}
							}
						}
				}else{
					mtrace("...No classes in this moment!");
				}
				//Afegir-la a la taula de classes fetes.
				//Tot el registre de la classe més el timestamp d'ara per la data!
				//Hi haurà d'aver-hi una administració per netejar aquests registres
			}
	}
	 
	 
?>
