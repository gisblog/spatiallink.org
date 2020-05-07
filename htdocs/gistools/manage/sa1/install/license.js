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
// $Id: license.js,v 1.2 2005/04/18 02:08:11 bob Exp $

function toggleLicenseAccept(){
    var theForm     = document.forms[0];

    if( theForm.setup_license_accept.checked ){
        theForm.setup_license_accept.checked = "";
    }
    else {
        theForm.setup_license_accept.checked = "yes";
    }

    toggleNextButton();
}

function toggleNextButton(){
    var theForm     = document.forms[0];
    var nextButton  = document.getElementById( "button_next" );

    if( theForm.setup_license_accept.checked ){
        nextButton.disabled = '';
        nextButton.focus();
    }
    else {
        nextButton.disabled = "disabled";
    }
}
