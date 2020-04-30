<!--include cell table: self-->
		<table width="375" border="0" align="left" cellspacing="0" cellpadding="0" bgcolor="#c2c0c0">
	<tr>
<td class="medium" colspan="2">
&bull;&nbsp;Please tell us about your self::
</td>
	</tr>
		
	<tr>
<td class="medium">
First Name
</td>
<td class="medium">
<input type="text" name="firstname" value="" size="" maxlength="32" />
</td>
	</tr>

	<tr>
<td class="medium">
Last Name
</td>
<td class="medium">
<input type="text" name="lastname" value="" size="" maxlength="32" />
</td>
	</tr>
	
	<tr>
<td class="medium">
Email
</td>
<td class="medium">
<input type="text" name="email" id="email" value="" size="" maxlength="32" />
</td>
	</tr>

	<tr>
<td class="medium">
Job Title
</td>
<td class="medium">
<input type="text" name="jobtitle" value="" size="" maxlength="32" />
</td>
	</tr>

	<tr>
<td class="medium">
Location
</td>
<td class="medium">
<!--values of attribute "type": "text", "password", "checkbox", "radio", "submit", "reset", "file", "hidden", "image", "button"-->
<?php
/*
	form: location {cocode+zipcode+place+state+latitude+longitude}
	-->db: sl1_gisvolunteer_location {SN ZIPCODE LATITUDE LONGITUDE PLACE COUNTY STATE} {1 00210 43.005895 -71.013202 PORTSMOUTH 015 33} + sl1_gisvolunteer_fips {SN COUNTY CO_NAME STATE ST_NAME} {1 001 Autauga 01 AL}
	-->db: sl1_gisvolunteer_location_co {SN COCODE LATITUDE LAT_MIN LONGITUDE LON_MIN COUNTRY} {1 1 33 33 00 N 65 65 00 E Afghanistan}
*/
//	include country
include '/var/chroot/home/content/57/3881957/html/inc/inc_country.php';
?>
</td>
	</tr>
	
	
	<tr>
<td class="medium">
&nbsp;
</td>
<td class="medium">
<!--values of attribute "type": "text", "password", "checkbox", "radio", "submit", "reset", "file", "hidden", "image", "button"-->
<input type="text" name="zipcode" id="zipcode" value="" size="5" maxlength="5" onblur="updatepsll();" />
</td>
	</tr>

	<tr>
<td class="medium">
&nbsp;
</td>
<td class="medium">
<input readonly="readonly" type="text" name="place" id="place" value="" size="" maxlength="32" class="read_only" />
<input readonly="readonly" type="text" name="state" id="state" value="" size="2" maxlength="2" class="read_only" />
</td>
	</tr>
	
	<tr>
<td class="medium">
&nbsp;
</td>
<td class="medium">
<input readonly="readonly" type="text" name="latitude" id="latitude" value="" size="11" maxlength="11" class="read_only" /> Lat 
<input readonly="readonly" type="text" name="longitude" id="longitude" value="" size="11" maxlength="11" class="read_only" /> Lon
</td>
	</tr>	

	<tr>
<td class="medium" colspan="2">
<br />
<?php
//	include line
//	include '/var/chroot/home/content/57/3881957/html/inc/inc_line_black.php';
?>
</td>
	</tr>
	
	<tr>
<td class="medium">
Experience
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="experience" size="1">
	<option value ="1">1</option>
	<option value ="2">2</option>
	<option value ="3">3</option>
	<option value ="4">4</option>
	<option value ="5">5</option>
	<option value ="6">6</option>
	<option value ="7">7</option>
	<option value ="8">8</option>
	<option value ="9">9</option>
	<option value ="10">10+</option>
</select> Year[s]
</td>
	</tr>

	<tr>
<td class="medium">
Proficiency
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="proficiency" size="1">
  	<option value ="1">Low</option>
  	<option value ="2">Medium</option>
  	<option value ="3">High</option>
</select>
</td>
	</tr>

	<tr>
<td class="medium">
Education
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="education" size="1">
  	<option value ="1">Diploma</option>
  	<option value ="2">Bachelor</option>
  	<option value ="3">Graduate</option>
  	<option value ="4">Doctoral</option>
</select>
</td>
	</tr>
		
	<tr>
<td class="medium">
Volunteering
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="vexperience" size="1">
	<option value ="1">None</option>
	<option value ="2">Limited</option>
	<option value ="3">Extensive</option>
</select>
</td>
	</tr>
	
	<tr>
<td class="medium">
Fee Estimate
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="fee" size="1">
	<option value ="1">Volunteer</option>
	<option value ="2">USD $1 - USD $25</option>
	<option value ="3">USD $26 - USD $50</option>
	<option value ="4">USD $51 - USD $75</option>
	<option value ="5">USD $76 - USD $100</option>
	<option value ="6">USD $101+</option>
</select> Per Hour
</td>
	</tr>	
		</table>
<!--include cell table: self-->