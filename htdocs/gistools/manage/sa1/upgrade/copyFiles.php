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

// $Id: copyFiles.php,v 1.10.2.3 2005/05/12 17:42:43 bob Exp $

if( !isset($upgrade_script) || ($upgrade_script == false) ){
	die('Unable to process script directly.');
}

set_time_limit(90);

    $upgrade_target_dir     = $_SESSION['upgrade_target_dir'];
    $upgrade_source_dir     = $_SESSION['upgrade_source_dir'];

    // flush after each output so the user can see the progress in real-time
    ob_implicit_flush();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Script-Type" content="text/javascript">
   <meta http-equiv="Content-Style-Type" content="text/css">
   <title>SugarCRM Upgrade Wizard: Step <?php echo $next_step ?></title>
   <link rel="stylesheet" href="upgrade.css" type="text/css" />
</head>
<body>
<form action="index.php" method="post" id="form">
<table cellspacing="0" cellpadding="0" border="0" align="center" class="shell" style="height=15px;margin-bottom=0px">
<tr>
   <th width="400">Step <?php echo $next_step ?>: Perform Setup</th>
   <th width="200" height="30" style="text-align: right;"><a href="http://www.sugarcrm.com" target=
      "_blank"><IMG src="../include/images/sugarcrm_login.png" width="120" height="19" alt="SugarCRM" border="0"></a></th>
</tr>
<tr>
   <td colspan="2" width="800">

<?php
	if($upgrade_source_dir != $upgrade_target_dir){
    print( "Finding files...<br>" );

    $original_dir   = getCwd();

    chdir( $upgrade_source_dir );
    $all_src_files  = findAllFiles( ".", array() );

    chdir( $upgrade_target_dir );
    $all_dest_files = findAllFiles( ".", array() );

    $skip_files = array(    "\\./config\\.php\$",
                            "\\./cache/custom_fields/custom_fields_def\\.php",
                            "\\./.*/company_logo\\.png",
                    );
    $skipped_files = "";

    print( "Disabling current version of Sugar...<br>" );
    copy( "$upgrade_target_dir/index.php", "$upgrade_target_dir/index.php.bak" );
    unlink( "$upgrade_target_dir/index.php" );

    print( "Putting up maintenance page...<br>" );
    copy( "$upgrade_source_dir/maintenance.php", "$upgrade_target_dir/index.php" );

    print( "Copying files...<br>" );
    foreach( $all_src_files as $src_file ){
        $do_copy = true;
        if( preg_match( "/\.html$/", $src_file ) && is_file( "$upgrade_target_dir/$src_file" ) ){
            // add a regexp here for files you WANT to overwrite
            if(
                ( !preg_match( "#\./themes/#",                                  $src_file ) ) &&
                ( !preg_match( "#\./modules/Activities/OpenListView\.html#",    $src_file ) ) &&
                ( !preg_match( "#\./modules/Administration/.*html#",            $src_file ) ) &&
                ( !preg_match( "#\./modules/Calls/.*html#",                     $src_file ) ) &&
                ( !preg_match( "#\./modules/Dropdown/.*html#",                  $src_file ) ) &&
                ( !preg_match( "#\./modules/DynamicLayout/.*html#",             $src_file ) ) &&
                ( !preg_match( "#\./modules/EditCustomFields/.*html#",          $src_file ) ) &&
                ( !preg_match( "#\./modules/Emails/.*html#",                    $src_file ) ) &&
                ( !preg_match( "#\./modules/Home/.*html#",                      $src_file ) ) &&
                ( !preg_match( "#\./modules/iFrame/.*html#",                    $src_file ) ) &&
                ( !preg_match( "#\./modules/Meetings/.*html#",                  $src_file ) ) &&
                ( !preg_match( "#\./modules/Users/.*html#",                     $src_file ) )
            ){
                print( "Skipping already present html file: $upgrade_target_dir/$src_file <br>" );
                $do_copy = false;
            }
        }

        foreach( $skip_files as $skip_file ){
            if( preg_match( "#" . $skip_file . "#", $src_file ) && is_file( "$upgrade_target_dir/$src_file" ) ){
                print( "Skipping copy of critical file: $upgrade_target_dir/$src_file <br>" );
                $do_copy = false;
            }
        }

        if( $do_copy ){
            $temp_dir = dirname( "$upgrade_target_dir/$src_file" );
            if( !is_dir( $temp_dir ) ){
                mkdir_recursive( $temp_dir );
            }
            if( preg_match( "#\./index.php\$#", $src_file ) ){
                copy( "$upgrade_source_dir/$src_file", "$upgrade_target_dir/$src_file.bak" );
            }
            else{
                copy( "$upgrade_source_dir/$src_file", "$upgrade_target_dir/$src_file" );
            }
        }
        else{
            $skipped_files .= "$upgrade_source_dir/$src_file\n";
        }
    }

    print( "If any html files were skipped, your original files have been left intact and you will need to merge any new changes manually.<br>" );

    chdir( $original_dir );
    if( $fh = @ fopen("skipped_files.txt", "w") ){
        fputs( $fh, $skipped_files, strlen($skipped_files) );
        fclose( $fh );
        print( "A list of these files is available in a file called: skipped_files.txt <BR>" );
    }
    else{
        print( "Please copy the file names listed above for future reference.<BR>" );
    }
	}else{
		print ("No files to copy.<br>Source directory is the same as target directory. <br>");
	}

    print( "Done!<br>" );
    print( "Click 'Next' to continue...<br>" );
?>
	</td>
</tr>
<tr>
<td align="right" colspan="2">
<hr>
<table cellspacing="0" cellpadding="0" border="0" class="stdTable">
<tr>
<td>
     <input type="hidden" name="current_step" value="<?php echo $next_step ?>">
     <table cellspacing="0" cellpadding="0" border="0" class="stdTable">
       <tr>
         <td><input class="button" type="submit" name="goto" value="Next" /></td>
       </tr>
     </table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
</form>
</body>
</html>
