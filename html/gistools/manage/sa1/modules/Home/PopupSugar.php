<!--
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * Header: /cvsroot/sugarcrm/sugarcrm/modules/Products/ListView.html,v 1.4 2004/07/02 07:02:27 sugarclint Exp {APP.LBL_LIST_CURRENCY_SYM}
 ********************************************************************************/
-->

<body style="margin: 0px;">
<?php 
global $theme;
require_once('themes/'.$theme.'/layout_utils.php');
insert_popup_header($theme); ?>
<script>
	var user_notices = new Array();
	var delay = 25000
	var index = 0;
	var lastIndex = 0;
	var scrollerHeight=620
	var bodyHeight = ''
	var scrollSpeed = 1;
	var curTeam = 'all';
	var scrolling = true;
	

	team = new Array("<p><b>SugarCRM Team Members</b></p>","John Roberts<br>","Clint Oram<br>","Jacob Taylor<br>","Tara Smith<br>", "Julian Ostrow<br>","Robert Rangel<br>","Lam Huynh<br>","Majed Itani<br>","Amie Doolittle<br>","Andrew Hsu<br>","Nate D' Amico<br>","Ron Cootes<br>","Cameron Jackson<br>","Joey Parsons<br>","Bob Schwarzmann<br>","Ajay Gupta<br>","Terra Wadsworth<br>","Jeff Cambell<br>","Drew Currie<br>","Jason Nassi<br>","Jason Green<br>","Russell Kojima</p>","<p><b>Special Thanks To</b></p>","Josh Stein<br>","Mary Coleman<br>","Larry Augustin<br>","Doran Gerstel</p>","<br><br><p>&copy; 2005 SugarCRM Inc. All Rights Reserved.");

	
	function stopNotice(){
			scrolling = false;
	}
	function startNotice(){
			scrolling = true;
	}
	function scrollNotice(){
		
		if(scrolling){
		var body = document.getElementById('NOTICEBODY')
		var daddy = document.getElementById('daddydiv')
	
		if(parseInt(body.style.top) > bodyHeight *-1 ){
			
			body.style.top = (parseInt(body.style.top) - scrollSpeed) + 'px';
		}else{
			
			body.style.top =scrollerHeight + "px"	
		}
		}
		
		setTimeout("scrollNotice()", 100);
		
	}
	function nextNotice(){
		

		

		if(scrolling){
			if(team.length > 0){
				if(index >= team.length	){
					index = 0;	
				}
				var body = document.getElementById('NOTICEBODY');
				if(curTeam != 'all'){
					body.innerHTML = team[index];
				}else{
					
					for(var i = 0; i < team.length; i++){
					body.innerHTML += team[i];
					}
					
					}
				body.style.top = scrollerHeight/2 +'px'
				bodyHeight= parseInt(body.offsetHeight);
				
				
				index++;
				}
				if(curTeam != 'all'){
					
					
				setTimeout("nextNotice()", delay);
				}
				
		}
	}
	

</script>
<div style="width: 300px; height: 400px; text-align: center; background-color: #efefef; border: 1px #9e9e9e solid; padding: 5px;">
<img src="include/images/sugarcrm_about_logo.gif"><br>


<div id='daddydiv' style="position:relative;width=100%;height:350px;overflow:hidden">
<div id='NOTICEBODY' style="position:absolute;left:0px;top:0px;width:100%;z-index: 1; text-align: center;"></div>
</div>
<script>
if(window.addEventListener){
	window.addEventListener("load", nextNotice, false);
	window.addEventListener("load", scrollNotice, false);
}else{
	window.attachEvent("onload", nextNotice);
	window.attachEvent("onload", scrollNotice);	
}
</script>


