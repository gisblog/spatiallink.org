<?php
//	error_reporting = E_ALL & ~ ( E_NOTICE | E_WARNING )
error_reporting(0);
require("/inc/inc_db_regionone.php");
require("/inc/inc_visitor_log.php");
//
include("header.php");
include("functions.php");
include("/inc/inc_db_metalfab.php");

$nowdate = date("Y-m-d");
if ((!isset($op)) || ($op == "")){$op = "home";}
							#	$op = $_GET['op'];
							#print (!isset($op));
### If no $cmd, just list items ###
     if ((!isset($cmd)) || ($cmd == "")){
		   		$cmd = "listing";		}
		   		
################## search ###################
function search() {
    global $database, $connection ;
    $query="SELECT servicecategories.CategoryName,servicesubcategories.SubcategoryName,servicesubcategories.SubcategoryID FROM servicecategories,servicesubcategories WHERE  servicecategories.categoryID = servicesubcategories.categoryID";
    $result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());
    while(list($CatName, $SubCatName,$SubcategoryID) = mysql_fetch_row($result))
    {
	   $catData[]= $CatName.":".$SubCatName.":".$SubcategoryID.":";
    }
    print '
    <script language="javascript">
    var arrayData = new Array();
    ';
    print "\r\n";
    foreach($catData as $key => $value)
    {
      print
      'arrayData[';print $key; print']="'; print $value; print'";';
    }
  	print '
  	</script>
	<script language="javascript">
  	function populateData( name ) {
	  	select	= window.document.form.SubCategory;
    	string	= "";
    	//	0 - will display the new options only
		//	1 - will display the first existing option plus the new options
		count	= 0;
		//	Clear the old list (above element 0)
    	select.options.length = count;
		//	Place all matching categories into Options    
		for( i = 0; i < arrayData.length; i++ )
	    {
			string = arrayData[i].split( ":" );
			
			if( string[0] == name )
	        {
				select.options[count] = new Option( string[1] );
				select.options[count++].value = string[2];
	            
			}
	    }
	    //	alert(window.document.form.SubCategory.options[0].value);
		select.options[0].selected = true;
		//	Set which option from subcategory is to be selected
		//	Give subcategory focus and select it
		select.focus();
	}	//	end populateData function
	</script>
	';
    print "
    <br />
    <center>
    <table width=\"80%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    <tr>
       <td><center>Select a category and subcategory of expertise to search for companies that meet your needs</center></td>
    </tr>
    </table>
    <br />
    <form action='index.php?op=listing' method='post' name='form'>
    ";
	opentable();
	print "<tr><td><b>Choose a Category of Expertise</b></td><td><b>Choose a Subcategory</b></td></tr>";
	print "<tr>";
	print "\n<td valign=top> <select onChange=\"javascript:populateData(this.options[selectedIndex].text);\"  name=\"category\" style=\"width:300\" size=8 multiple >";
                global $database, $connection ;
                $query="SELECT servicecategories.CategoryName FROM servicecategories ORDER BY CategoryName";
                $result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());
                $numRows = mysql_num_rows($result);
                for($i=0;$i<$numRows;$i++)
                {
                  $data = mysql_fetch_row($result);
                  if($i==0)
                  {
                    print "<option>";print "$data[0]"; print "</option>";
                    //	$defaultCategory = $data[0];
                  }
                  else
                  {
                    print "<option>";print "$data[0]"; print "</option>";
                  }
                }
    print "</select></td>\n";

    global $database, $connection ;
    $query="SELECT servicesubcategories.SubcategoryID, servicesubcategories.SubcategoryName FROM servicesubcategories WHERE CategoryID IN (Select CategoryID From servicecategories WHERE CategoryName = \"$defaultCategory\")";

    print "<td valign=top> <select name=\"SubCategory\" style=\"width:300\" size=8 multiple>";
    print "</select></td>";
    print "</tr>";
    print "<tr><td colspan='2' align='center'><input type=\"submit\" value=\"Submit\" name=\"Submit\"/> </td></tr>";
    closetable();
    print "</form></center><br />";
}
################## end of search ###############
################## listing ####################
function listing() {
	/**	gps **/
	//	create distinct filename
    $filename_distinct = str_replace(".", "_", microtime(true));
	//	create distinct file
    $filehandle_gps = fopen("/intern/metalfab/gps/".$filename_distinct.".xml", "a");
    $filecontent_1 = "<markers>\r\n";
    fwrite($filehandle_gps, $filecontent_1);
	/**	done **/
	global $database, $connection ;
	$category =  $_POST['category'];
	$subcategory=  $_POST['SubCategory'];

    if (!isset($subcategory) || !isset($category))
    {
      print "<br /><br /><font size=\"2\">You did not select a category and/or subcategory. Please use the back button on your browser to do so.</font size><br /><br /><br />";
      /**	gps **/
      ?>
      <script type="text/javascript">
      //<![CDATA[
	      function gps() {
      }
      //]]>
      </script>
      <?php
      /**	done **/
      } else {
      $query="select distinct a.CompanyID,CompanyName,City,YearsInBusiness,url,State,Photo from company as a left join company_subcategory as b ON a.CompanyID = b.CompanyID where SubcategoryID = $subcategory order by CompanyName";
	  $result = mysql_db_query($database, $query, $connection) or die ("Error\ return y: $query.\ " . mysql_error());
      print "<br />";
      //	<form action=\"?op=inquiry\" method=\"post\">
      /**	gps **/
      print "
      <input type=\"hidden\" name=\"category\" value=\"$category\" />
      <table align=center border=\"1\" cellspacing=\"1\" cellpadding=\"2\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"640\">
      <tr align = \"center\">
      	<td width=\"5%\" bgcolor=\"#758A6D\"><font color=\"#FFFFFF\"><b>SN</b></font></td>
      	<td width=\"60%\" bgcolor=\"#758A6D\"><font color=\"#FFFFFF\"><b>Company</b></font></td>
        <td width=\"25%\" bgcolor=\"#758A6D\"><font color=\"#FFFFFF\"><b>City / Town</b></font></td>
        <td width=\"10%\" bgcolor=\"#758A6D\"><font color=\"#FFFFFF\"><b>More</b></font></td>
       </tr>
       ";
       $sn =1;
       /**	done **/
       $j = 0;
       
      while(list($CompanyID, $CompanyName, $City , $YearsInBusiness,$url,$State,$Photo) = mysql_fetch_row($result))
      {
	      /**	gps **/
	      //	capture photo
	      $gps_photo = $Photo;
	      /**	done **/
         $j++;
         if ($j % 2 == 0)
         {
            $altcolor="FFFFFF";
         }
         else
         {
            $altcolor="E5E5E5";
         }
  	     $queryContacts="SELECT distinct contact_phonenumber.PhoneNumber FROM contact_phonenumber, contacts Where contact_phonenumber.ContactID = contacts.ContactID And contact_phonenumber.ContactID IN (Select contacts.ContactID From contacts Where CompanyID = $CompanyID)";
	     $resultContact = mysql_db_query($database, $queryContacts, $connection) or die ("Error in query: $query. " . mysql_error());
          //	list($ConPhone,$ConName) = mysql_fetch_row($resultContact);   
          /**	gps **/
          //	get variables
          $query_gps = "SELECT * FROM gps_ll83 WHERE gps_ll83.N1 = $CompanyID";
          $result_gps = mysql_db_query($database, $query_gps, $connection) or die ($query_gps." ".mysql_error());
          while(list($id, $n1, $n2, $n3, $point_x, $point_y) = mysql_fetch_row($result_gps)) {
		     //	capture variables
		     $gps_sn = $sn;
		     $gps_id = $id;
		     $gps_n1 = $n1;
		     $gps_n2 = $n2;
		     $gps_n3 = $n3;
		     $gps_point_x = $point_x;
		     $gps_point_y = $point_y;
		     //	write variables
		     $filecontent_2 = "<marker sn=\"".$gps_sn."\" company=\"".str_replace("’", "&#8217;", htmlentities($CompanyName))."\" photo=\"".$gps_photo."\" lng=\"".$gps_point_x."\" lat=\"".$gps_point_y."\" />\r\n";
		     fwrite($filehandle_gps, $filecontent_2);
		}
		$gps = $gps_sn.",".$gps_point_x.",".$gps_point_y;
		?>
		   <tr bgcolor="<?php print $altcolor; ?>">
           <td width="10" align="center">
           <a href="#" id="<?php print $gps_sn; ?>" name="<?php print $gps_sn; ?>" onclick="javascript:gps(<?php print $gps; ?>);"><?php print $gps_sn; ?></a>
           </td>
           <?php
           print "
           <td >
           $CompanyName<br/><a href=\"http://$url\" title='Visit Website' target='_blank'>$url
           </td>
           <td >
           $City
           </td>
           <td >
           <a href ='?op=moreinfo&comp=";print $CompanyID;print "' target='_blank' title='Click here for more information'>More...</a>
           </td>
        </tr>
		";
		$sn++;
        /**	done **/
		if ($cr=1){$cr=0;}else{$cr=1;}
      }
      //	end of while loop
      print "</table>";
      //	</form>
      /**	gps **/
      //	close distinct file
      $filecontent_3 = "</markers>";
      fwrite($filehandle_gps, $filecontent_3);
      fclose($filehandle_gps);
      include "gps.php";
      /**	done **/    
	}
}
################## end of listing ###############
################## others ####################
function home()
{
  home_body();
}

function contact()
{
  contact_body();
}

function moreinfo($idOfCompany)
{
   global $database, $connection ;
   $query="SELECT CompanyName, Address, City, State, ZipCode, URL,AreasOfService FROM Company WHERE  CompanyID = \"$idOfCompany\"";
   $result = mysql_db_query($database, $query, $connection) or die ("Error in query: $query. " . mysql_error());
   list($cName, $address,$city,$state,$zip,$url,$AreasOfService) = mysql_fetch_row($result);
   print "
   <br /><center><h4>$cName</h4></center>
   <br />
   <font size=\"2\">
         ";
   if(isset($url))
   {
     print "Website: <a href=http://$url title='Visit Website' target='_blank'>$url</a><br /><br />";
   }

   print "
   $cName
   <br />$address
   <br />$city $state $zip
   <br /><br />
   ";

   $queryPhone="SELECT distinct contact_phonenumber.PhoneNumber FROM contact_phonenumber, contacts Where contact_phonenumber.ContactID = contacts.ContactID And contact_phonenumber.ContactID IN (Select contacts.ContactID From contacts Where CompanyID = $idOfCompany)";
   $resultPhone = mysql_db_query($database, $queryPhone, $connection) or die ("Error in query: $queryPhone. " . mysql_error());

   print "Phone Number(s):";
   while(list($ConPhone)= mysql_fetch_row($resultPhone))
   {
       print "$ConPhone";
   }

   print "
   <br />
   Fax Number(s): 
   ";
   
   $queryFax="SELECT Distinct FaxNumber From contacts where CompanyID=$idOfCompany";
   $resultFax=mysql_db_query($database,$queryFax, $connection) or die ("Error in query: $queryFax. " . mysql_error());

   while(list($faxNumber)=mysql_fetch_row($resultFax))
   {
     print "$faxNumber";
   }

   print "<br />";

   $queryEmail="Select distinct Email From contact_email Where contact_email.ContactID = contactID And contact_email.ContactID IN (Select contacts.ContactID From contacts Where CompanyID = $idOfCompany)";
   $resultEmail= mysql_db_query($database, $queryEmail, $connection) or die ("Error in query: $queryEmail. " . mysql_error());
   print "E-mail Address: ";
   while(list($email)= mysql_fetch_row($resultEmail))
   {
          print "<a href=mailto:$email>$email</a>";
   }
   
   print "<br /><br />Areas of Service: $AreasOfService<br /><br />";
   $querySIC="Select SICcode FROM company_sic WHERE CompanyID = $idOfCompany";
   $resultSIC=mysql_db_query($database, $querySIC,$connection)or die ("Error in query: $querySIC. " . mysql_error());
   print "SIC: ";
   while(list($SIC)=mysql_fetch_row($resultSIC))
   {
     print "$SIC;";
   }
   print "<br />";
   //	print $AreasOfService;
   $queryNAICS="Select NAICScode FROM company_naics WHERE CompanyID = $idOfCompany";
   $resultNAICS=mysql_db_query($database, $queryNAICS,$connection)or die ("Error in query: $queryNAICS. " . mysql_error());
   print "NAICS: ";
   while(list($NAICS)=mysql_fetch_row($resultNAICS))
   {
     print "$NAICS;";
   }
   print "</font size><br /><br />";
}
//	start switch
switch($op) {
	case "home":home();break;
	case "moreinfo":moreinfo($comp);break;
	case "search":search();break;
	case "listing":listing();break;
	case "contact":contact();break;
	case "Viewlist":Viewlist($id_catg,$id_subcatg,$page);break;
	case "submitads":submitads($title,$cdesc,$catgforprocess,$prize,$website,$email,$country,$postfor);break;
	case "AddAds":AddAds();break;
	case "search_ads":Search_Ads($query,$page);break;
	case "EmailAds":EmailAds($email);break;
	case "SendEmail":SendEmail($to,$from,$subject,$message);break;
	//	default:selecting();break;
}
//	end switch
################## end of others ###############
include("footer.php");
?>