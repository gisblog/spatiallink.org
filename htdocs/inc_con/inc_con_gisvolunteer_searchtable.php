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
<input type="text" name="email" value="" size="" maxlength="32" />
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
Office Name
</td>
<td class="medium">
<input type="text" name="officename" value="" size="" maxlength="32" />
</td>
	</tr>
	
	<tr>
<td class="medium">
Office Status
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="officestatus" size="1">
  	<option value ="1" selected="selected">For-profit</option>
  	<option value ="2">Government</option>
  	<option value ="3">Non-profit</option>
</select>
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
include '/opt/bitnami/apache2/htdocs/inc/inc_country.php';
?>
</td>
	</tr>

	<tr>
<td class="medium">
&nbsp;
</td>
<td class="medium">
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
//	include '/opt/bitnami/apache2/htdocs/inc/inc_line_black.php';
?>
&bull;&nbsp;Please tell us about the contract job::
</td>
	</tr>
			
	<tr>
<td class="medium">
Estimate
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="jobestimate" size="1"> 
  	<option value ="1" selected="selected">1</option>
  	<option value ="2">2-4</option>
  	<option value ="3">4+</option>
</select> Week[s]
</td>
	</tr>
	
	<tr>
<td class="medium">
Urgency
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="joburgency" size="1">
  	<option value ="1" selected="selected">Low</option>
  	<option value ="2">Medium</option>
  	<option value ="3">High</option>
</select>
</td>
	</tr>

	<tr>
<td class="medium">
<!--size is in characters: also, <td onmouseover="alert('Options')" onmouseout="" class="medium">Options</td>-->
Buffer
</td>
<td class="medium">
<!--NO multiple selection-->
<select name="searchbuffer" id="searchbuffer" size="1">
	<option value="9" selected="selected">9</option>
	<option value="">Incompatible Browser</option>
</select>
</td>
	</tr>
		</table>
<!--include cell table: self-->
