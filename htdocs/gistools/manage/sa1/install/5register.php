<?php
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

// $Id: 5register.php,v 1.21 2005/04/18 02:08:11 bob Exp $

$suicide = true;
if(isset($install_script))
{
   if($install_script)
   {
      $suicide = false;
   }
}

if($suicide)
{
   // mysterious suicide note
   die('Unable to process script directly.');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Setup Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="install/install.css" type="text/css" />
   <script type="text/javascript" src="install/installCommon.js"></script>
   <script type="text/javascript" src="install/5register.js"></script>
</head>
<body onload="javascript:document.getElementById('defaultFocus').focus();">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell">
<tr>
    <th width="400">Step <?php echo $next_step ?>: Registration</th>
	<th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
    <td colspan="2" width="600">
<?php
if (!isset($_POST['confirm']) || !$_POST['confirm']) {?>

<p>Please take a moment to register with SugarCRM. By letting us know a little bit about how your company plans to use SugarCRM, we can ensure we are always delivering the right product for your business needs.</p>

<p>Your name and email address are the only required fields for registration. All other fields are optional, but very helpful. We do not sell, rent, share, or otherwise distribute the information collected here to third parties. </p>


<!-- begin registration -->
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="stdTable">

  <form action="http://www.sugarcrm.com/home/index.php" method="POST" name="mosForm" target="_blank">

<tr>
    <td>
<span class="required">* Required field</span>
    </td>
	</tr>
    <tr>
    <td>
<span class="required">*</span> Name:</td>
      <td width="70%"><input type="text" name="name" size="40" value="" class="inputbox" id="defaultFocus" /></td>
    </tr>

	<tr>
      <td><span class="required">*</span> Business Email:</td>
      <td><input type="text" name="email" size="40" value="" class="inputbox" /></td>
    </tr>

    <TR>
	<TD>Job Title</TD>
	<TD><SELECT class="inputbox" name="position">
        <OPTION value=''>-Select one-</OPTION>
        <OPTION>CEO/President/Owner</OPTION>
        <OPTION>CFO</OPTION>
        <OPTION>CTO/CIO</OPTION>
        <OPTION>VP/Dir/Mgr, Business Dev.</OPTION>
        <OPTION>VP/Dir/Mgr, Marketing</OPTION>
        <OPTION>VP/Dir/Mgr, Ops/IS/IT</OPTION>
        <OPTION>VP/Dir/Mgr, Sales</OPTION>
        <OPTION>VP/Dir/Mgr, Support</OPTION>
        <OPTION>Government Official</OPTION>
        <OPTION>Sales Rep.</OPTION>
        <OPTION>Customer Support Rep.</OPTION>
        <OPTION>Consultant</OPTION>
        <OPTION>Industry Analyst</OPTION>
        <OPTION>Student/Researcher</OPTION>
        <OPTION>Other</OPTION>
		</SELECT>
</TR>
<TR>
	<TD>How did you hear about SugarCRM?</TD>
	<TD><SELECT class="inputbox" name="leadsource">
        <OPTION value=''>-Select one-</OPTION>
        <OPTION>Word of Mouth</OPTION>
 		<OPTION>Sourceforge.Net</OPTION>
		<OPTION>MySQL.com</OPTION>
        <OPTION>Search Engine</OPTION>
        <OPTION>News Article</OPTION>
        <OPTION>Mail/email </OPTION>
        <OPTION>Advertising</OPTION>
        <OPTION>Training</OPTION>
        <OPTION>Newsletter</OPTION>
        <OPTION>Event</OPTION>
        <OPTION>Other</OPTION>
		</select>
</TD></TR>

      <input type="hidden" name="username" value="" />
      <input type="hidden" name="password" value="" />
      <input type="hidden" name="password2" value="" />



<script language="JavaScript">
	function register_detailed_check(thefield) {
		if (thefield.value == "") {
			alert("Field may not be left blank!");
			return false;
		}
		return true;
	}
</script>

<TR>
	<TD colspan=2><br></TD>
</TR>
<TR>
	<TD>Company Name</TD>
	<TD><INPUT TYPE="text" class="inputbox" name="company" size="40"></TD>
</TR>
<TR>
	<TD>Company Address</TD>
	<TD><TEXTAREA class="inputbox" cols="30" name="coaddress" rows="3"></TEXTAREA>
	</TD>
</TR>
<TR>
	<TD>City</TD>
	<TD><INPUT class="inputbox" cols="60" name="cocity" size="20"></TEXTAREA>
	</TD>
</TR>
<TR>
	<TD>State (US/Canada only):</TD>
	<TD><select class="inputbox" name="costate">
 		<OPTION value=''>-Select one-</OPTION>
		<OPTION>Alberta</OPTION>
		<OPTION>Alaska</OPTION>
		<OPTION>Alabama</OPTION>
		<OPTION>Arkansas</OPTION>
		<OPTION>Arizona</OPTION>
		<OPTION>British Columbia</OPTION>
		<OPTION>California</OPTION>
		<OPTION>Colorado</OPTION>
		<OPTION>Connecticut</OPTION>
		<OPTION>District of Columbia</OPTION>
		<OPTION>Delaware</OPTION>
		<OPTION>Florida</OPTION>
		<OPTION>Georgia</OPTION>
		<OPTION>Hawaii</OPTION>
		<OPTION>Iowa</OPTION>
		<OPTION>Idaho</OPTION>
		<OPTION>Illinois</OPTION>
		<OPTION>Indiana</OPTION>
		<OPTION>Kansas</OPTION>
		<OPTION>Kentucky</OPTION>
		<OPTION>Louisiana</OPTION>
		<OPTION>Labrador</OPTION>
		<OPTION>Massachusetts</OPTION>
		<OPTION>Manitoba</OPTION>
		<OPTION>Maryland</OPTION>
		<OPTION>Maine</OPTION>
		<OPTION>Michigan</OPTION>
		<OPTION>Minnesota</OPTION>
		<OPTION>Missouri</OPTION>
		<OPTION>Mississippi</OPTION>
		<OPTION>Montana</OPTION>
		<OPTION>New Brunswick</OPTION>
		<OPTION>North Carolina</OPTION>
		<OPTION>North Dakota</OPTION>
		<OPTION>Nebraska</OPTION>
		<OPTION>Newfoundland</OPTION>
		<OPTION>New Hampshire</OPTION>
		<OPTION>New Jersey</OPTION>
		<OPTION>New Mexico</OPTION>
		<OPTION>Nova Scotia</OPTION>
		<OPTION>Northwest Territories</OPTION>
		<OPTION>Nevada</OPTION>
		<OPTION>New York</OPTION>
		<OPTION>Ohio</OPTION>
		<OPTION>Oklahoma</OPTION>
		<OPTION>Ontario</OPTION>
		<OPTION>Oregon</OPTION>
		<OPTION>Pennsylvania</OPTION>
		<OPTION>Prince Edward Island</OPTION>
		<OPTION>Quebec</OPTION>
		<OPTION>Puerto Rico</OPTION>
		<OPTION>Rhode Island</OPTION>
		<OPTION>South Carolina</OPTION>
		<OPTION>South Dakota</OPTION>
		<OPTION>Saskatchewan</OPTION>
		<OPTION>Tennessee</OPTION>
		<OPTION>Texas</OPTION>
		<OPTION>Utah</OPTION>
		<OPTION>Virginia</OPTION>
		<OPTION>Vermont</OPTION>
		<OPTION>Washington</OPTION>
		<OPTION>Wisconsin</OPTION>
		<OPTION>West Virginia</OPTION>
		<OPTION>Wyoming</OPTION>
		<OPTION>Yukon Territory
	</TD>
</TR>
<TR>
	<TD>Postal Code</TD>
	<TD><INPUT class="inputbox" cols="60" name="copostalcode" size="10"></TEXTAREA>
	</TD>
</TR>
<TR>
	<TD>Country</TD>
	<TD><select class="inputbox" name="costate">
		<OPTION value=''>-Select one-</OPTION>
		<OPTION>United States</OPTION>
		<OPTION>Abu Dhabi</OPTION>
		<OPTION>Aden</OPTION>
		<OPTION>Afghanistan</OPTION>
		<OPTION>Albania</OPTION>
		<OPTION>Algeria</OPTION>
		<OPTION>American Samoa</OPTION>
		<OPTION>Andorra</OPTION>
		<OPTION>Angola</OPTION>
		<OPTION>Antarctica</OPTION>
		<OPTION>Antigua</OPTION>
		<OPTION>Argentina</OPTION>
		<OPTION>Armenia</OPTION>
		<OPTION>Aruba</OPTION>
		<OPTION>Australia</OPTION>
		<OPTION>Austria</OPTION>
		<OPTION>Azerbaijan</OPTION>
		<OPTION>Bahamas</OPTION>
		<OPTION>Bahrain</OPTION>
		<OPTION>Bangladesh</OPTION>
		<OPTION>Barbados</OPTION>
		<OPTION>Belgium</OPTION>
		<OPTION>Belize</OPTION>
		<OPTION>Benin</OPTION>
		<OPTION>Bermuda</OPTION>
		<OPTION>Bhutan</OPTION>
		<OPTION>Bolivia</OPTION>
		<OPTION>Bosnia</OPTION>
		<OPTION>Botswana</OPTION>
		<OPTION>Bouvet Island</OPTION>
		<OPTION>Brazil</OPTION>
		<OPTION>British Antarctica Territory</OPTION>
		<OPTION>British Indian Ocean Territory</OPTION>
		<OPTION>British West Indies</OPTION>
		<OPTION>Brunei</OPTION>
		<OPTION>Bulgaria</OPTION>
		<OPTION>Burkina Faso</OPTION>
		<OPTION>Burundi</OPTION>
		<OPTION>Cambodia</OPTION>
		<OPTION>Cameroon</OPTION>
		<OPTION>Canada</OPTION>
		<OPTION>Canal Zone</OPTION>
		<OPTION>Canary Island</OPTION>
		<OPTION>Canton and Enderbury Island</OPTION>
		<OPTION>Cape Verdi Islands</OPTION>
		<OPTION>Cayman Islands</OPTION>
		<OPTION>Central African Republic</OPTION>
		<OPTION>Cevlon</OPTION>
		<OPTION>Chad</OPTION>
		<OPTION>Channel Island UK</OPTION>
		<OPTION>Chile</OPTION>
		<OPTION>China</OPTION>
		<OPTION>Christmas Island</OPTION>
		<OPTION>Cocos (Keeling) Island</OPTION>
		<OPTION>Colombia</OPTION>
		<OPTION>Comoro Islands</OPTION>
		<OPTION>Congo</OPTION>
		<OPTION>Congo Kinshasa</OPTION>
		<OPTION>Cook Islands</OPTION>
		<OPTION>Costa Rica</OPTION>
		<OPTION>Croatia</OPTION>
		<OPTION>Cuba</OPTION>
		<OPTION>Curacao</OPTION>
		<OPTION>Cyprus</OPTION>
		<OPTION>Czech Republic</OPTION>
		<OPTION>Dahomey</OPTION>
		<OPTION>Denmark</OPTION>
		<OPTION>Djibouti</OPTION>
		<OPTION>Dominica</OPTION>
		<OPTION>Dominican Republic</OPTION>
		<OPTION>Dronning Muad Land</OPTION>
		<OPTION>Dubai</OPTION>
		<OPTION>Ecuador</OPTION>
		<OPTION>Egypt</OPTION>
		<OPTION>El Salvador</OPTION>
		<OPTION>Equatorial Guinea</OPTION>
		<OPTION>Estonia</OPTION>
		<OPTION>Ethiopia</OPTION>
		<OPTION>Faeroe Islands</OPTION>
		<OPTION>Falkland Islands</OPTION>
		<OPTION>Fiji</OPTION>
		<OPTION>Finland</OPTION>
		<OPTION>France</OPTION>
		<OPTION>French Guiana</OPTION>
		<OPTION>French Polynesia</OPTION>
		<OPTION>French Southern & Antarctica</OPTION>
		<OPTION>French Territory of Afars</OPTION>
		<OPTION>French West Indies</OPTION>
		<OPTION>Gabon</OPTION>
		<OPTION>Gambia</OPTION>
		<OPTION>Gaza</OPTION>
		<OPTION>Georgia</OPTION>
		<OPTION>Germany</OPTION>
		<OPTION>Ghana</OPTION>
		<OPTION>Gibraltar</OPTION>
		<OPTION>Greece</OPTION>
		<OPTION>Greenland</OPTION>
		<OPTION>Guadeloupe</OPTION>
		<OPTION>Guam</OPTION>
		<OPTION>Guatemala</OPTION>
		<OPTION>Guinea</OPTION>
		<OPTION>Guinea</OPTION>
		<OPTION>Bissau</OPTION>
		<OPTION>Guyana</OPTION>
		<OPTION>Haiti</OPTION>
		<OPTION>Heard & McDonald Islands</OPTION>
		<OPTION>Holland</OPTION>
		<OPTION>Honduras</OPTION>
		<OPTION>Hong Kong</OPTION>
		<OPTION>Hungary</OPTION>
		<OPTION>Iceland</OPTION>
		<OPTION>Ifni</OPTION>
		<OPTION>India</OPTION>
		<OPTION>Indonesia</OPTION>
		<OPTION>Iran</OPTION>
		<OPTION>Iraq</OPTION>
		<OPTION>Iraq-Saudi Arania Neutral Zone</OPTION>
		<OPTION>Ireland</OPTION>
		<OPTION>Israel</OPTION>
		<OPTION>Italy</OPTION>
		<OPTION>Ivory Coast</OPTION>
		<OPTION>Jamaica</OPTION>
		<OPTION>Japan</OPTION>
		<OPTION>Johnston Atoll</OPTION>
		<OPTION>Jordon</OPTION>
		<OPTION>Kazakhstan</OPTION>
		<OPTION>Kenya</OPTION>
		<OPTION>Korea</OPTION>
		<OPTION>Korea, North</OPTION>
		<OPTION>Korea, South</OPTION>
		<OPTION>Kuwait</OPTION>
		<OPTION>Kyrgyzstan</OPTION>
		<OPTION>Laos</OPTION>
		<OPTION>Latvia</OPTION>
		<OPTION>Lebanon</OPTION>
		<OPTION>Leeward Islands</OPTION>
		<OPTION>Lesotho</OPTION>
		<OPTION>Liberia</OPTION>
		<OPTION>Libya</OPTION>
		<OPTION>Liechtenstein</OPTION>
		<OPTION>Lithuania</OPTION>
		<OPTION>Luxembourg</OPTION>
		<OPTION>Macao</OPTION>
		<OPTION>Macedonia</OPTION>
		<OPTION>Madagascar</OPTION>
		<OPTION>Malawi</OPTION>
		<OPTION>Malaysia</OPTION>
		<OPTION>Maldives</OPTION>
		<OPTION>Mali</OPTION>
		<OPTION>Malta</OPTION>
		<OPTION>Mariana Islands</OPTION>
		<OPTION>Martinique</OPTION>
		<OPTION>Mauritania</OPTION>
		<OPTION>Mauritius</OPTION>
		<OPTION>Melanesia</OPTION>
		<OPTION>Mexico</OPTION>
		<OPTION>Micronesia</OPTION>
		<OPTION>Midway Islands</OPTION>
		<OPTION>Moldovia</OPTION>
		<OPTION>Monaco</OPTION>
		<OPTION>Mongolia</OPTION>
		<OPTION>Montserrat</OPTION>
		<OPTION>Morocco</OPTION>
		<OPTION>Mozambique</OPTION>
		<OPTION>Myanamar</OPTION>
		<OPTION>Namibia</OPTION>
		<OPTION>Nauru</OPTION>
		<OPTION>Navassa Island</OPTION>
		<OPTION>Nepal</OPTION>
		<OPTION>Netherlands</OPTION>
		<OPTION>Netherlands Antilles Neutral Zone</OPTION>
		<OPTION>New Caladonia</OPTION>
		<OPTION>New Hebrides</OPTION>
		<OPTION>New Zealand</OPTION>
		<OPTION>Nicaragua</OPTION>
		<OPTION>Niger</OPTION>
		<OPTION>Nigeria</OPTION>
		<OPTION>Niue</OPTION>
		<OPTION>Norfolk Island</OPTION>
		<OPTION>North Ireland, UK</OPTION>
		<OPTION>Norway</OPTION>
		<OPTION>Oman</OPTION>
		<OPTION>Pacific Island</OPTION>
		<OPTION>Pakistan</OPTION>
		<OPTION>Panama</OPTION>
		<OPTION>Papua New Guinea</OPTION>
		<OPTION>Paracel Islands</OPTION>
		<OPTION>Paraguay</OPTION>
		<OPTION>Peru</OPTION>
		<OPTION>Philippines</OPTION>
		<OPTION>Pitcairn Islands</OPTION>
		<OPTION>Poland</OPTION>
		<OPTION>Polynesia</OPTION>
		<OPTION>Portugal</OPTION>
		<OPTION>Portuguese Guinea</OPTION>
		<OPTION>Portuguese Timor</OPTION>
		<OPTION>Principe & Sao Tome</OPTION>
		<OPTION>Puerto Rico</OPTION>
		<OPTION>Qatar</OPTION>
		<OPTION>Republic of Belarus</OPTION>
		<OPTION>Republic of South Africa</OPTION>
		<OPTION>Reunion</OPTION>
		<OPTION>Romania</OPTION>
		<OPTION>Russia</OPTION>
		<OPTION>Rwanda</OPTION>
		<OPTION>Ryukyu Islands</OPTION>
		<OPTION>SW Africa</OPTION>
		<OPTION>Sabah</OPTION>
		<OPTION>San Marino</OPTION>
		<OPTION>San Tome & Principe</OPTION>
		<OPTION>Saudi Arabia</OPTION>
		<OPTION>Scotland</OPTION>
		<OPTION>Senegal</OPTION>
		<OPTION>Seychelles</OPTION>
		<OPTION>Sierra Leone</OPTION>
		<OPTION>Sikkim</OPTION>
		<OPTION>Singapore</OPTION>
		<OPTION>Slovakia</OPTION>
		<OPTION>Slovenia</OPTION>
		<OPTION>Solomon Islands</OPTION>
		<OPTION>Somalia</OPTION>
		<OPTION>Somaliliand</OPTION>
		<OPTION>South Africa</OPTION>
		<OPTION>South Yemen</OPTION>
		<OPTION>Spain</OPTION>
		<OPTION>Spanish Sahara</OPTION>
		<OPTION>Spartly Islands</OPTION>
		<OPTION>Sri Lanka</OPTION>
		<OPTION>St. Christopher-Nevis-Anguilla</OPTION>
		<OPTION>St. Helena</OPTION>
		<OPTION>St. Kittis</OPTION>
		<OPTION>St. Lucia</OPTION>
		<OPTION>St. Pierre & Miquelon</OPTION>
		<OPTION>St. Vincent</OPTION>
		<OPTION>Sudan</OPTION>
		<OPTION>Surinam</OPTION>
		<OPTION>Svalbard & Jan Mayen Islands</OPTION>
		<OPTION>Swaziland</OPTION>
		<OPTION>Sweden</OPTION>
		<OPTION>Switzerland</OPTION>
		<OPTION>Syria</OPTION>
		<OPTION>Taiwan</OPTION>
		<OPTION>Tajikistan</OPTION>
		<OPTION>Tanzania</OPTION>
		<OPTION>Thailand</OPTION>
		<OPTION>Togo</OPTION>
		<OPTION>Tonga</OPTION>
		<OPTION>Transkei</OPTION>
		<OPTION>Trinidad</OPTION>
		<OPTION>Tunisia</OPTION>
		<OPTION>Turkey</OPTION>
		<OPTION>Turkmenistan</OPTION>
		<OPTION>Turks & Caicos Islands</OPTION>
		<OPTION>US Pacific Island</OPTION>
		<OPTION>US Virgin Islands</OPTION>
		<OPTION>USA</OPTION>
		<OPTION>Uganda</OPTION>
		<OPTION>Ukraine</OPTION>
		<OPTION>United Arab Emirates</OPTION>
		<OPTION>United Kingdom</OPTION>
		<OPTION>Upper Volta</OPTION>
		<OPTION>Uruguay</OPTION>
		<OPTION>Uzbekistan</OPTION>
		<OPTION>Vanuatu</OPTION>
		<OPTION>Vatican City</OPTION>
		<OPTION>Venezuela</OPTION>
		<OPTION>Vietnam</OPTION>
		<OPTION>Wake Island</OPTION>
		<OPTION>Wales</OPTION>
		<OPTION>West Indies</OPTION>
		<OPTION>Western Sahara</OPTION>
		<OPTION>Western Samoa</OPTION>
		<OPTION>Windward Islands</OPTION>
		<OPTION>Yemen</OPTION>
		<OPTION>Yugoslavia</OPTION>
		<OPTION>Zaire</OPTION>
		<OPTION>Zambia</OPTION>
		<OPTION>Zimbabwe</OPTION>
		</select>
 	</TD>
</TR>
<TR>
	<TD>Telephone Number</TD>
	<TD><INPUT TYPE="text" class="inputbox" name="telephone" size="20"></TD>
</TR>
<TR>
	<TD>How many employees?</TD>
	<TD><SELECT class="inputbox" name="noemployees">
        <OPTION value=''>-Select one-</OPTION>
		<OPTION>1 - 20</OPTION>
		<OPTION>21 - 100</OPTION>
		<OPTION>101 - 500</OPTION>
		<OPTION>501 - 2000</OPTION>
		<OPTION>2000+</OPTION>
		</select>

	<INPUT TYPE="hidden" value="1" name="_REGISTER_DETAILS_ON">
</TD>
</TR>
<TR>
	<TD>Your company's industry?</TD>
	<TD><SELECT class="inputbox" name="industry">
        <OPTION value=''>-Select one-</OPTION>
        <OPTION>Aerospace and Defense</OPTION>
        <OPTION>Agriculture and Forestry</OPTION>
        <OPTION>Automotive</OPTION>
        <OPTION>Banking and Finance</OPTION>
        <OPTION>Computers and Technology</OPTION>
        <OPTION>Education and Training</OPTION>
        <OPTION>Engineering, Construction and Scientific</OPTION>
        <OPTION>Entertainment, Travel and Hospitality</OPTION>
        <OPTION>Food and Beverage</OPTION>
        <OPTION>Government and Public Administration</OPTION>
        <OPTION>Healthcare</OPTION>
        <OPTION>Insurance</OPTION>
        <OPTION>Legal Solutions</OPTION>
        <OPTION>Manufacturing Industries</OPTION>
        <OPTION>Media and Information Publishing</OPTION>
        <OPTION>Natural Resources</OPTION>
        <OPTION>Nonprofit Organizations and Trade Associations</OPTION>
        <OPTION>Pharmaceutical</OPTION>
        <OPTION>Professional, Scientific and Technical Services</OPTION>
        <OPTION>Real Estate</OPTION>
        <OPTION>Retail and Wholesale</OPTION>
        <OPTION>Telecommunications</OPTION>
        <OPTION>Textiles and Apparel</OPTION>
        <OPTION>Transportation and Shipping</OPTION>
        <OPTION>Utilities</OPTION>
        <OPTION>Other</OPTION>
		</select>
</TD>
</TR>
<TR>
	<TD>Planned number of SugarCRM users:</TD>
	<TD><SELECT class="inputbox" name="nousers">
        <OPTION value=''>-Select one-</OPTION>
        <OPTION>1 to 20</OPTION>
        <OPTION>21 to 100</OPTION>
        <OPTION>101 to 500</OPTION>
        <OPTION>501 to 2000</OPTION>
        <OPTION>2001+</OPTION>
		</select>
</TD></TR>

<TR>
	<TD>Current sales software:</TD>
	<TD><SELECT class="inputbox" name="sfasoftware">
        <OPTION value=''>-Select one-</OPTION>
        <OPTION>In-house developed</OPTION>
		<OPTION>Siebel Systems</OPTION>
		<OPTION>Salesforce.com</OPTION>
        <OPTION>Microsoft CRM</OPTION>
        <OPTION>SalesLogix</OPTION>
        <OPTION>Onyx</OPTION>
        <OPTION>Pivotal</OPTION>
        <OPTION>PeopleSoft</OPTION>
        <OPTION>SAP</OPTION>
        <OPTION>ACCPAC</OPTION>
        <OPTION>Act!</OPTION>
        <OPTION>GoldMine</OPTION>
        <OPTION>Other</OPTION>
		</select>
</TD></TR>

<TR>
	<TD>Other open source software in use: </TD>
	<TD><table width='100%'><tr><td><tr><td>
		<select class="inputbox" multiple size='9' name="ossoftware">
		<OPTION>Red Hat/Fedora Linux</OPTION>
        <OPTION>SuSe Linux</OPTION>
        <OPTION>FreeBSD</OPTION>
        <OPTION>Debian Linux</OPTION>
        <OPTION>MySQL</OPTION>
        <OPTION>PostgreSQL</OPTION>
        <OPTION>Apache Web Server</OPTION>
        <OPTION>JBoss</OPTION>
        <OPTION>PHP</OPTION>
		</select>
        </td></tr>
		</table>
</TD></TR>
<TR>
	<TD>Comments:</TD>
	<TD><textarea class="inputbox" name="comments" cols='50' rows='10'></textarea>
 </TD></TR>


    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr>

      <td colspan=2>
  <input type="hidden" name="id" value="0" />
  <input type="hidden" name="gid" value="0" />
  <input type="hidden" name="installer" value="true" />
   <input type="hidden" name="installer2_0" value="true" />
  <input type="hidden" name="emailpass" value="0" />
  <input type="hidden" name="option" value="com_extended_registration" />
  <input type="hidden" name="task" value="register" />


</td></tr>
<tr><td colspan='2' align='right'>
  <input class="button" type="button" value="Send Registration"  accessKey="S" onclick="submitbutton()" />
  </form></td>
		</tr>
</table>
<!-- end registration -->


<?php } else {?>
Thank you for registering. Click on the Finish button to login to SugarCRM. You will need to log in for the first time using the username "admin" and the password you entered in step 2.
<?php }?>
	</td>
</tr>
<tr>
	<td align="right" colspan="2" height="20">
	<hr>
	<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
		<tr>
                    <td><input class="button" type="button" onclick="window.open('http://www.sugarcrm.com/forums/');" value="Help" /></td>
		    <td>
                    <form action="index.php" method="post" name="form" id="form">
                    <input type="hidden" name="default_user_name" value="admin">
                    <input class="button" type="submit" name="next" value="Finish" />
		    </form>
                    </td>
		</tr>
	</table>
	</td>
</tr>
</table>
<br>
</body>
</html>
