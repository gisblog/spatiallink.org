<?php
/* Send e-mail, via web form, using "gdform.php": gdform.php provides a simple clocked address form-mailer. It is useful because it hides your email address.

	http://ftphelp.secureserver.net/php-mysql-tutorial.html
	
	"Please use our gdform.php or gdform.cgi for your mailing functionality. Go Daddy’s CGI accounts provide our customers with access to a form-mailer script. This script accepts information gathered from an HTML form and emails that information back to a single address that the customer specifies. As such, knowledge of HTML is needed to make effective use of this service. 

	To use the service create and upload the form as normal. Bear in mind that the script will sort the names of the items on the form alphabetically when it composes the form response email, and that it respects case. Here is the order the form-mailer script recognizes: 

	• Uppercase Letters First 
	• Lowercase Letters Second 
	• Numbers Third 

	By naming form items appropriately it is possible to organize response information as it is submitted. 
	
	HTML allows you to send the output from a form in two ways: get or post. You may use either with our form-mailer script. Just be sure to list the script in the action line as follows: 

	action=/cgi/gdform.cgi 

	Note that using the “get” method will limit the number of characters that your form can send to our script. This limitation is just part of the HTML and is not particular to Go Daddy. Further, the size of response emails is capped at 100 KB. Since the form-mailer will use plain text to send its messages this will not be an issue in most cases. 

	As mentioned above, form responses will be sent by the script to the email address that you specify. This setting is controlled through Account Management on our site. Visit www.godaddy.com and click on the 'Settings' option from the "Web Hosting & Databases" / "Hosting & Email" menu at the top of the page. Through this link you may type in the email address that you would like our script to use for your form. 

	Lastly, there are three special fields that you may employ in your form: subject, redirect, and email. The names of these three fields must remain lowercase in order to work. You may make changes along the following lines: 

	• The “subject” field controls the subject line of the form response email 
	• The “redirect” field controls the page visitors will see after submitting the form 
	• The “email” field controls the return address for the form response email 
	
	Here is an example code snippet: 

		<form name="egbwcontact" action="gdform.php" method="post"> 
		<input type="hidden" name="subject" value="EGBW website contact form"> 
		<input type="hidden" name="redirect" value="thankyou.html"> 
		...... 
		<input type="submit" name="Action" value="Submit"> 
		</form> */
		
    $request_method = $_SERVER["REQUEST_METHOD"];
    if($request_method == "GET"){
      $query_vars = $_GET;
    } elseif ($request_method == "POST"){
      $query_vars = $_POST;
    }
    reset($query_vars);
    $t = date("U");
    $fp = fopen("../data/gdform_$t","w");
    while (list ($key, $val) = each ($query_vars)) {
     fputs($fp,"<GDFORM_VARIABLE NAME=$key START>\n");
     fputs($fp,"$val\n");
     fputs($fp,"<GDFORM_VARIABLE NAME=$key END>\n");
     if ($key == "redirect") { $landing_page = $val;}
    }
    fclose($fp);
    if ($landing_page != ""){
	header("Location: http://".$_SERVER["HTTP_HOST"]."/$landing_page");
    } else {
	header("Location: http://".$_SERVER["HTTP_HOST"]."/");
    }
?>