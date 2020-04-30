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
/*********************************************************************************
 * $Id: logging.php,v 1.13.6.1 2005/05/17 23:56:27 robert Exp $
 * Description:  Kicks off log4php.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
require_once('config.php');

define('LOG4PHP_DIR', 'log4php');
define('LOG4PHP_DEFAULT_INIT_OVERRIDE', true);

require_once(LOG4PHP_DIR.'/LoggerManager.php');
require_once(LOG4PHP_DIR.'/LoggerPropertyConfigurator.php');

if (! isset($simple_log) || $simple_log == false)
{
$config = new LoggerPropertyConfigurator();
$config->configure('log4php.properties');
}

class SimpleLog
{
        var $fp;
        var $logfile = 'sugarcrm.log';
        var $loglevel = 5;
				var $nolog = false;
        function SimpleLog()
        {
					global $loglevel,$logfile;
					if (! empty($loglevel))
					{
							if ( $loglevel == 'fatal') $this->loglevel = 5;
							else if ( $loglevel == 'error') $this->loglevel = 4;
							else if ( $loglevel == 'warn') $this->loglevel = 3;
							else if ( $loglevel == 'debug') $this->loglevel = 2;
							else if ( $loglevel == 'info') $this->loglevel = 1;
					}
					if (! empty($logfile))
					{
							$this->logfile = $logfile;	
					}
 					$this->fp = @ fopen($this->logfile, 'a+');
					if (! $this->fp )
					{
						$this->nolog = true;
					}
        }
        function info($string)
        {
								if ($this->loglevel > 1 || $this->nolog) return;
                fwrite($this->fp, "info:[".strftime("%Y-%m-%d %T")."] $string\n")
                        or die("Logger Failed to write to:". $this->logfile);
        }
        function debug($string)
        {
								if ( $this->loglevel > 2 || $this->nolog ) return;
                fwrite($this->fp, "debug:[".strftime("%Y-%m-%d %T")."] $string\n")
                        or die("Logger Failed to write to:". $this->logfile);
        }
        function warn($string)
        {
								if ( $this->loglevel > 3 || $this->nolog ) return;
                fwrite($this->fp, "warn:[".strftime("%Y-%m-%d %T")."] $string\n")
                        or die("Logger Failed to write to:". $this->logfile);
        }
        function error($string)
        {
								if ( $this->loglevel > 4 || $this->nolog ) return;
                fwrite($this->fp, "error:[".strftime("%Y-%m-%d %T")."] $string\n")
                        or die("Logger Failed to write to:". $this->logfile);
        }
        function fatal($string)
        {
								if (  $this->loglevel > 5)  return;
								if ( $this->nolog ) die($string);
                fwrite($this->fp, "fatal:[".strftime("%Y-%m-%d %T")."] $string\n") 
                        or die("Logger Failed to write to:". $this->logfile);
								die($string);
        }
}

?>
