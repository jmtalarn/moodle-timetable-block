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

require_once("../../../config.php");
require_once("../lib.php");
$viewrequired = required_param('viewrequired', PARAM_CLEAN);

$courseid = required_param('courseid', PARAM_CLEAN);
$timetableid = required_param('timetableid', PARAM_CLEAN);

$sesskey = required_param('sesskey', PARAM_ALPHANUM);

require_login($courseid);
confirm_sesskey($sesskey);



$form = data_submitted();

switch ($viewrequired) {
	case 'editclassroom':
		include("../actions/".$viewrequired.".php");
		break;
	case 'editingclassroomips':
		include("../actions/".$viewrequired.".php");
	break;
	case 'editgroup':
		include("../actions/".$viewrequired.".php");
	break;
	case 'editinghourtable':
		include("../actions/".$viewrequired.".php");
	break;	
	case 'editclasshours':
		include("../actions/".$viewrequired.".php");
	break;
	case 'readgroup':
		include("../actions/".$viewrequired.".php");
	break;
}
if (isset($toconfirm)){
		include('../views/confirm.php');
}else{
	include('../views/'.$viewrequired.'.html');
}
?>
