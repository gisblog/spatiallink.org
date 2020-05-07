/**
 * Javascript file for Sugar
 *
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
 */

// $Id: sugar_3.js,v 1.1 2005/04/29 21:48:25 andrew Exp $

/**
 * DHTML date validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
// Declaring valid date character, minimum year and maximum year
var dtCh= "-";
var minYear=1900;
var maxYear=2100;
var nameIndex = 0;
var typeIndex = 1;
var requiredIndex = 2;
var msgIndex = 3;
var jstypeIndex = 4;
var minIndex = 5;
var maxIndex = 6;
var compareToIndex = 7;
var validate = new Array();
var maxHours = 24;
var requiredTxt = 'Missing Required Field:'
var invalidTxt = 'Invalid Value:'
var secondsSinceLoad = 0;
var alertList = new Array();
function addAlert(type, name,subtitle, description,time, redirect){
	var addIndex = alertList.length;
	alertList[addIndex]= new Array();
	alertList[addIndex]['name'] = name;
	alertList[addIndex]['type'] = type;
	alertList[addIndex]['subtitle'] = subtitle;
	alertList[addIndex]['description'] = replaceAll(description, '<br>', '\n');
	alertList[addIndex]['time'] = time;
	alertList[addIndex]['done'] = 0;
	alertList[addIndex]['redirect'] = redirect;
} 
function checkAlerts(){
	secondsSinceLoad += 1;
	var mj = 0;
	var alertmsg = '';
	for(mj = 0 ; mj < alertList.length; mj++){
		if(alertList[mj]['done'] == 0){
			if(alertList[mj]['time'] < secondsSinceLoad && alertList[mj]['time'] > -1 ){
				alertmsg = alertList[mj]['type'] + ":" + alertList[mj]['name'] + "\n" +alertList[mj]['subtitle']+ "\n"+ alertList[mj]['description'] + "\n\n";
				alertList[mj]['done'] = 1;
				if(alertList[mj]['redirect'] == ''){
					alert(alertmsg);
				}else if(confirm(alertmsg)){
					window.location = alertList[mj]['redirect'];
				}
			}
		}
	}
	
	
	setTimeout("checkAlerts()", 1000);

}
function toggleDisplay(id){
		
			if(this.document.getElementById( id).style.display=='none'){
				this.document.getElementById( id).style.display='inline'
				if(this.document.getElementById(id+"link") != undefined){
					this.document.getElementById(id+"link").style.display='none';
				}
						
			}else{
				this.document.getElementById(  id).style.display='none'
				if(this.document.getElementById(id+"link") != undefined){
					this.document.getElementById(id+"link").style.display='inline';
				}
			}
		}
function checkAll(form, field, value)
{
	for (i = 0; i < form.elements.length; i++){
		if(form.elements[i].name == field)
			form.elements[i].checked = value;
	}
}

function replaceAll(text, src, rep){
		offset = text.toLowerCase().indexOf(src.toLowerCase());
		while ( offset != -1 ) {                         
		text = text.substring(0, offset) + rep + text.substring(offset + src.length ,text.length);
		offset = text.indexOf( src, offset + rep.length + 1);
		}
		return text;
		}


function addForm(formname){
	validate[formname] = new Array();
}
function addToValidate(formname, name, type,required,  msg){
	if( typeof validate[formname] == 'undefined'){
		addForm(formname);
	}
	validate[formname][validate[formname].length] = new Array(name, type,required, msg);	
}
function addToValidateRange(formname, name, type,required,  msg,min,max){
	addToValidate(formname, name, type,required,  msg);
	validate[formname][validate[formname].length - 1][jstypeIndex] = 'range'
	validate[formname][validate[formname].length - 1][minIndex] = min;
	validate[formname][validate[formname].length - 1][maxIndex] = max;
}

function addToValidateDateBefore(formname, name, type,required,  msg,compareTo){
	addToValidate(formname, name, type,required,  msg);
	validate[formname][validate[formname].length - 1][jstypeIndex] = 'isbefore'
	validate[formname][validate[formname].length - 1][compareToIndex] = compareTo;
}


function removeFromValidate(formname, name){
	for(i = 0; i < validate[formname].length; i++){
		if(validate[formname][i][nameIndex] == name){
			validate[formname].splice(i, 1);
		}
	}
}

function toDecimal(original){
	temp = Math.round(original*100)/100;
	if((original * 100) % 100 == 0)
		return temp + '.00';
	if((original * 10) % 10 == 0)
		return temp + '0';
	return temp
}

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   }
   return this
}
var date_reg_positions = {'Y': 1,'m': 2,'d': 3};
var date_reg_format = '([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})'
function isDate(dtStr){
	if(dtStr.length== 0){
		return true;
	}
	myregexp = new RegExp(date_reg_format)
	if(!myregexp.test(dtStr))
		return false

return true
}

function getDateObject(dtStr){
		if(dtStr.length== 0){
		return true;
	}
	myregexp = new RegExp(date_reg_format)
	var dt = myregexp.exec(dtStr)
	var yr = dt[date_reg_positions['Y']];
	var mh = dt[date_reg_positions['m']];
	var dy = dt[date_reg_positions['d']];
	var date1 = new Date();
	date1.setFullYear(yr);
	 date1.setMonth(mh);
	 date1.setDate(dy);
	return date1;
}

function isBefore(value1, value2){
	var d1 = getDateObject(value1);
	var d2 = getDateObject(value2);
	return d2 >= d1;
}

function isValidEmail(emailStr){
	if(emailStr.length== 0){
		return true;
	}
	if(!/^\w+([\.\-\+]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(emailStr))
		return false
	return true	
}

function isValidPhone(phoneStr){
	if(phoneStr.length== 0){
		return true;
	}
	if(!/^[0-9\-\(\)]+$/.test(phoneStr))
		return false
	return true	
}
function isFloat(floatStr){
	if(floatStr.length== 0){
		return true;
	}
	if(!/^[0-9\.]+$/.test(floatStr))
		return false
	return true	
}
var time_reg_format = "[0-9]{1,2}\:[0-9]{2}";
function isTime(timeStr){
	time_reg_format = time_reg_format.replace('([ap]m)', '');
	time_reg_format = time_reg_format.replace('([AP]M)', '');
	if(timeStr.length== 0){
		return true;
	}
	//we now support multiple time formats
	myregexp = new RegExp(time_reg_format)
	if(!myregexp.test(timeStr))
		return false

return true
}

function inRange(value, min, max){
	return value >= min && value <= max;
}


function trim(s) {
	if(typeof(s) == 'undefined')  
		return s;
	while (s.substring(0,1) == " ") {
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ') {
		s = s.substring(0,s.length-1);
	}

	return s;
}


function check_form(formname){
	return validate_form(formname, '');
	
}



function validate_form(formname, startsWith){
	if ( typeof (formname) == 'undefined')
	{
		return false;
	}
	if ( typeof (validate[formname]) == 'undefined')
	{
		return true;
	}
	var form = "document." + formname;
	var isError = false;
	var errorMsg = "";
	for(var i = 0; i < validate[formname].length; i++){
			if(validate[formname][i][nameIndex].indexOf(startsWith) == 0){
			if(typeof eval(form + "." + validate[formname][i][nameIndex] ) != 'undefined'){
			var bail = false;
			if(validate[formname][i][requiredIndex]){
				if(typeof eval(form + "." + validate[formname][i][nameIndex]) == 'undefined' || trim(eval(form + "." + validate[formname][i][nameIndex] + ".value")) == ""){
					errorMsg += '\n' + requiredTxt +' ' + validate[formname][i][msgIndex];
					isError = true;
					bail = true;
				}
			}
			if(!bail){
			
			switch(validate[formname][i][typeIndex]){
				case 'email':
							if(!isValidEmail(trim(eval(form + "." + validate[formname][i][nameIndex] + ".value")))){
								isError = true;
								errorMsg += "\n "+ invalidTxt + " " +	validate[formname][i][msgIndex];
							}
							 break;
				case 'time':
							if( !isTime(trim(eval(form+"." + validate[formname][i][nameIndex] + ".value")))){
								isError = true;
								errorMsg += "\n "+ invalidTxt + " " +	validate[formname][i][msgIndex];
							} break;
				case 'date': if(!isDate(trim(eval(form + "." + validate[formname][i][nameIndex] + ".value")))){
								isError = true;
								errorMsg += "\n "+ invalidTxt + " " +	validate[formname][i][msgIndex];
							}  break;
				case 'alpha': break;
				case 'alphanumeric': break;
				case 'int':if(!isInteger(trim(eval(form + "." + validate[formname][i][nameIndex] + ".value")))){
								isError = true;
								errorMsg += "\n "+ invalidTxt + " " + 	validate[formname][i][msgIndex];
							} break;
				case 'float':if(!isFloat(trim(eval(form + "." + validate[formname][i][nameIndex] + ".value")))){
							isError = true;
							errorMsg += "\n "+ invalidTxt + " " + 	validate[formname][i][msgIndex];
						} break;
				
				
			
				}
				if(typeof validate[formname][i][jstypeIndex]  != 'undefined' && !isError){
					switch(validate[formname][i][jstypeIndex]){
					case 'range':
							if(!inRange(trim(eval(form + "." + validate[formname][i][nameIndex] + ".value")), validate[formname][i][minIndex], validate[formname][i][maxIndex])){
								isError = true;
								errorMsg += "\n " + validate[formname][i][msgIndex] + " value " + eval(form + "." + validate[formname][i][nameIndex] + ".value") + " is not within the valid range (" +validate[formname][i][minIndex] + " - " + validate[formname][i][maxIndex] +  ") " 	;
							}
							break;
					case 'isbefore':
							compareTo = form + "." + validate[formname][i][compareToIndex];
							if(	typeof compareTo != 'undefined'){
								date1 = trim(eval(form + "." + validate[formname][i][nameIndex] + ".value"));
								date2 = trim(eval(compareTo + '.value'));
								if(!isBefore(date1,date2)){ 		
									isError = true;
									errorMsg += "\n " + validate[formname][i][msgIndex] + "(" + date1 + ") is not before " + date2;
								}
							}
					}
				}
		}
			
	}}}
	if (isError == true) {
				alert(errorMsg);
				return false;
			}
		
		return true;
	
}

/**
 * Displays an confirmation box beforme to submit a "DROP/DELETE/ALTER" query.
 * This function is called while clicking links
 *
 * @param   object   the link
 * @param   object   the sql query to submit
 *
 * @return  boolean  whether to run the query or not
 */
function confirmLink(theLink, theSqlQuery)
{
    // Confirmation is not required in the configuration file
    // or browser is Opera (crappy js implementation)
    if (confirmMsg == '' || typeof(window.opera) != 'undefined') {
        return true;
    }

    var is_confirmed = confirm(confirmMsg + ' :\n' + theSqlQuery);
    if (is_confirmed) {
        theLink.href += '&is_js_confirmed=1';
    }

    return is_confirmed;
} // end of the 'confirmLink()' function


/**
 * Displays an error message if a "DROP DATABASE" statement is submitted
 * while it isn't allowed, else confirms a "DROP/DELETE/ALTER" query before
 * sumitting it if required.
 * This function is called by the 'checkSqlQuery()' js function.
 *
 * @param   object   the form
 * @param   object   the sql query textarea
 *
 * @return  boolean  whether to run the query or not
 *
 * @see     checkSqlQuery()
 */
function confirmQuery(theForm1, sqlQuery1)
{
    // Confirmation is not required in the configuration file
    if (confirmMsg == '') {
        return true;
    }

    // The replace function (js1.2) isn't supported
    else if (typeof(sqlQuery1.value.replace) == 'undefined') {
        return true;
    }

    // js1.2+ -> validation with regular expressions
    else {
        // "DROP DATABASE" statement isn't allowed
        if (noDropDbMsg != '') {
            var drop_re = new RegExp('DROP\\s+(IF EXISTS\\s+)?DATABASE\\s', 'i');
            if (drop_re.test(sqlQuery1.value)) {
                alert(noDropDbMsg);
                theForm1.reset();
                sqlQuery1.focus();
                return false;
            } // end if
        } // end if

        // Confirms a "DROP/DELETE/ALTER" statement
        //
        // TODO: find a way (if possible) to use the parser-analyser
        // for this kind of verification
        // For now, I just added a ^ to check for the statement at
        // beginning of expression
        
        //var do_confirm_re_0 = new RegExp('DROP\\s+(IF EXISTS\\s+)?(TABLE|DATABASE)\\s', 'i');
        //var do_confirm_re_1 = new RegExp('ALTER\\s+TABLE\\s+((`[^`]+`)|([A-Za-z0-9_$]+))\\s+DROP\\s', 'i');
        //var do_confirm_re_2 = new RegExp('DELETE\\s+FROM\\s', 'i');
        var do_confirm_re_0 = new RegExp('^DROP\\s+(IF EXISTS\\s+)?(TABLE|DATABASE)\\s', 'i');
        var do_confirm_re_1 = new RegExp('^ALTER\\s+TABLE\\s+((`[^`]+`)|([A-Za-z0-9_$]+))\\s+DROP\\s', 'i');
        var do_confirm_re_2 = new RegExp('^DELETE\\s+FROM\\s', 'i');
        if (do_confirm_re_0.test(sqlQuery1.value)
            || do_confirm_re_1.test(sqlQuery1.value)
            || do_confirm_re_2.test(sqlQuery1.value)) {
            var message      = (sqlQuery1.value.length > 100)
                             ? sqlQuery1.value.substr(0, 100) + '\n    ...'
                             : sqlQuery1.value;
            var is_confirmed = confirm(confirmMsg + ' :\n' + message);
            // drop/delete/alter statement is confirmed -> update the
            // "is_js_confirmed" form field so the confirm test won't be
            // run on the server side and allows to submit the form
            if (is_confirmed) {
                theForm1.elements['is_js_confirmed'].value = 1;
                return true;
            }
            // "DROP/DELETE/ALTER" statement is rejected -> do not submit
            // the form
            else {
                window.focus();
                sqlQuery1.focus();
                return false;
            } // end if (handle confirm box result)
        } // end if (display confirm box)
    } // end confirmation stuff

    return true;
} // end of the 'confirmQuery()' function


/**
 * Displays an error message if the user submitted the sql query form with no
 * sql query, else checks for "DROP/DELETE/ALTER" statements
 *
 * @param   object   the form
 *
 * @return  boolean  always false
 *
 * @see     confirmQuery()
 */
function checkSqlQuery(theForm)
{
    var sqlQuery = theForm.elements['sql_query'];
    var isEmpty  = 1;

    // The replace function (js1.2) isn't supported -> basic tests
    if (typeof(sqlQuery.value.replace) == 'undefined') {
        isEmpty      = (sqlQuery.value == '') ? 1 : 0;
        if (isEmpty && typeof(theForm.elements['sql_file']) != 'undefined') {
            isEmpty  = (theForm.elements['sql_file'].value == '') ? 1 : 0;
        }
        if (isEmpty && typeof(theForm.elements['sql_localfile']) != 'undefined') {
            isEmpty  = (theForm.elements['sql_localfile'].value == '') ? 1 : 0;
        }
        if (isEmpty && typeof(theForm.elements['id_bookmark']) != 'undefined') {
            isEmpty  = (theForm.elements['id_bookmark'].value == null || theForm.elements['id_bookmark'].value == '');
        }
    }
    // js1.2+ -> validation with regular expressions
    else {
        var space_re = new RegExp('\\s+');
        isEmpty      = (sqlQuery.value.replace(space_re, '') == '') ? 1 : 0;
        // Checks for "DROP/DELETE/ALTER" statements
        if (!isEmpty && !confirmQuery(theForm, sqlQuery)) {
            return false;
        }
        if (isEmpty && typeof(theForm.elements['sql_file']) != 'undefined') {
            isEmpty  = (theForm.elements['sql_file'].value.replace(space_re, '') == '') ? 1 : 0;
        }
        if (isEmpty && typeof(theForm.elements['sql_localfile']) != 'undefined') {
            isEmpty  = (theForm.elements['sql_localfile'].value.replace(space_re, '') == '') ? 1 : 0;
        }
        if (isEmpty && typeof(theForm.elements['id_bookmark']) != 'undefined') {
            isEmpty  = (theForm.elements['id_bookmark'].value == null || theForm.elements['id_bookmark'].value == '');
            isEmpty  = (theForm.elements['id_bookmark'].selectedIndex == 0);
        }
        if (isEmpty) {
            theForm.reset();
        }
    }

    if (isEmpty) {
        sqlQuery.select();
        alert(errorMsg0);
        sqlQuery.focus();
        return false;
    }

    return true;
} // end of the 'checkSqlQuery()' function


/**
 * Displays an error message if an element of a form hasn't been completed and
 * should be
 *
 * @param   object   the form
 * @param   string   the name of the form field to put the focus on
 *
 * @return  boolean  whether the form field is empty or not
 */
function emptyFormElements(theForm, theFieldName)
{
    var isEmpty  = 1;
    var theField = theForm.elements[theFieldName];
    // Whether the replace function (js1.2) is supported or not
    var isRegExp = (typeof(theField.value.replace) != 'undefined');

    if (!isRegExp) {
        isEmpty      = (theField.value == '') ? 1 : 0;
    } else {
        var space_re = new RegExp('\\s+');
        isEmpty      = (theField.value.replace(space_re, '') == '') ? 1 : 0;
    }
    if (isEmpty) {
        theForm.reset();
        theField.select();
        alert(errorMsg0);
        theField.focus();
        return false;
    }

    return true;
} // end of the 'emptyFormElements()' function


/**
 * Ensures a value submitted in a form is numeric and is in a range
 *
 * @param   object   the form
 * @param   string   the name of the form field to check
 * @param   integer  the minimum authorized value
 * @param   integer  the maximum authorized value
 *
 * @return  boolean  whether a valid number has been submitted or not
 */
function checkFormElementInRange(theForm, theFieldName, min, max)
{
    var theField         = theForm.elements[theFieldName];
    var val              = parseInt(theField.value);

    if (typeof(min) == 'undefined') {
        min = 0;
    }
    if (typeof(max) == 'undefined') {
        max = Number.MAX_VALUE;
    }

    // It's not a number
    if (isNaN(val)) {
        theField.select();
        alert(errorMsg1);
        theField.focus();
        return false;
    }
    // It's a number but it is not between min and max
    else if (val < min || val > max) {
        theField.select();
        alert(val + errorMsg2);
        theField.focus();
        return false;
    }
    // It's a valid number
    else {
        theField.value = val;
    }

    return true;
} // end of the 'checkFormElementInRange()' function


/**
 * Ensures the choice between 'transmit', 'zipped', 'gzipped' and 'bzipped'
 * checkboxes is consistant
 *
 * @param   object   the form
 * @param   string   a code for the action that causes this function to be run
 *
 * @return  boolean  always true
 */
function checkTransmitDump(theForm, theAction)
{
    var formElts = theForm.elements;

    // 'zipped' option has been checked
    if (theAction == 'zip' && formElts['zip'].checked) {
        if (!formElts['asfile'].checked) {
            theForm.elements['asfile'].checked = true;
        }
        if (typeof(formElts['gzip']) != 'undefined' && formElts['gzip'].checked) {
            theForm.elements['gzip'].checked = false;
        }
        if (typeof(formElts['bzip']) != 'undefined' && formElts['bzip'].checked) {
            theForm.elements['bzip'].checked = false;
        }
    }
    // 'gzipped' option has been checked
    else if (theAction == 'gzip' && formElts['gzip'].checked) {
        if (!formElts['asfile'].checked) {
            theForm.elements['asfile'].checked = true;
        }
        if (typeof(formElts['zip']) != 'undefined' && formElts['zip'].checked) {
            theForm.elements['zip'].checked = false;
        }
        if (typeof(formElts['bzip']) != 'undefined' && formElts['bzip'].checked) {
            theForm.elements['bzip'].checked = false;
        }
    }
    // 'bzipped' option has been checked
    else if (theAction == 'bzip' && formElts['bzip'].checked) {
        if (!formElts['asfile'].checked) {
            theForm.elements['asfile'].checked = true;
        }
        if (typeof(formElts['zip']) != 'undefined' && formElts['zip'].checked) {
            theForm.elements['zip'].checked = false;
        }
        if (typeof(formElts['gzip']) != 'undefined' && formElts['gzip'].checked) {
            theForm.elements['gzip'].checked = false;
        }
    }
    // 'transmit' option has been unchecked
    else if (theAction == 'transmit' && !formElts['asfile'].checked) {
        if (typeof(formElts['zip']) != 'undefined' && formElts['zip'].checked) {
            theForm.elements['zip'].checked = false;
        }
        if ((typeof(formElts['gzip']) != 'undefined' && formElts['gzip'].checked)) {
            theForm.elements['gzip'].checked = false;
        }
        if ((typeof(formElts['bzip']) != 'undefined' && formElts['bzip'].checked)) {
            theForm.elements['bzip'].checked = false;
        }
    }

    return true;
} // end of the 'checkTransmitDump()' function


/**
 * This array is used to remember mark status of rows in browse mode
 */
var marked_row = new Array;


/**
 * Sets/unsets the pointer and marker in browse mode
 *
 * @param   object    the table row
 * @param   interger  the row number
 * @param   string    the action calling this script (over, out or click)
 * @param   string    the default background color
 * @param   string    the color to use for mouseover
 * @param   string    the color to use for marking a row
 *
 * @return  boolean  whether pointer is set or not
 */
function setPointer(theRow, theRowNum, theAction, theDefaultColor, thePointerColor, theMarkColor)
{
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;
    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        currentColor = theCells[0].style.backgroundColor;
        domDetect    = false;
    } // end 3

    // 4. Defines the new color
    // 4.1 Current color is the default one
    if (currentColor == ''
        || currentColor.toLowerCase() == theDefaultColor.toLowerCase()) {
        if (theAction == 'over' && thePointerColor != '') {
            newColor              = thePointerColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    // 4.1.2 Current color is the pointer one
    else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
             && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
        if (theAction == 'out') {
            newColor              = theDefaultColor;
        }
        else if (theAction == 'click' && theMarkColor != '') {
            newColor              = theMarkColor;
            marked_row[theRowNum] = true;
        }
    }
    // 4.1.3 Current color is the marker one
    else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
        if (theAction == 'click') {
            newColor              = (thePointerColor != '')
                                  ? thePointerColor
                                  : theDefaultColor;
            marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                                  ? true
                                  : null;
        }
    } // end 4

    // 5. Sets the new color...
    if (newColor) {
        var c = null;
        // 5.1 ... with DOM compatible browsers except Opera
        if (domDetect) {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            } // end for
        }
        // 5.2 ... with other browsers
        else {
            for (c = 0; c < rowCellsCnt; c++) {
                theCells[c].style.backgroundColor = newColor;
            }
        }
    } // end 5

    return true;
} // end of the 'setPointer()' function

/*
 * Sets/unsets the pointer and marker in vertical browse mode
 *
 * @param   object    the table row
 * @param   interger  the row number
 * @param   string    the action calling this script (over, out or click)
 * @param   string    the default background color
 * @param   string    the color to use for mouseover
 * @param   string    the color to use for marking a row
 *
 * @return  boolean  whether pointer is set or not
 *
 * @author Garvin Hicking <me@supergarv.de> (rewrite of setPointer.)
 */
function setVerticalPointer(theRow, theRowNum, theAction, theDefaultColor1, theDefaultColor2, thePointerColor, theMarkColor) {
    var theCells = null;

    // 1. Pointer and mark feature are disabled or the browser can't get the
    //    row -> exits
    if ((thePointerColor == '' && theMarkColor == '')
        || typeof(theRow.style) == 'undefined') {
        return false;
    }

    // 2. Gets the current row and exits if the browser can't get it
    if (typeof(document.getElementsByTagName) != 'undefined') {
        theCells = theRow.getElementsByTagName('td');
    }
    else if (typeof(theRow.cells) != 'undefined') {
        theCells = theRow.cells;
    }
    else {
        return false;
    }

    // 3. Gets the current color...
    var rowCellsCnt  = theCells.length;
    var domDetect    = null;
    var currentColor = null;
    var newColor     = null;

    // 3.1 ... with DOM compatible browsers except Opera that does not return
    //         valid values with "getAttribute"
    if (typeof(window.opera) == 'undefined'
        && typeof(theCells[0].getAttribute) != 'undefined') {
        currentColor = theCells[0].getAttribute('bgcolor');
        domDetect    = true;
    }
    // 3.2 ... with other browsers
    else {
        domDetect    = false;
    } // end 3

    var c = null;
    // 5.1 ... with DOM compatible browsers except Opera
    for (c = 0; c < rowCellsCnt; c++) {
        if (domDetect) {
            currentColor = theCells[c].getAttribute('bgcolor');
        } else {
            currentColor = theCells[c].style.backgroundColor;
        }

        // 4. Defines the new color
        // 4.1 Current color is the default one
        if (currentColor == ''
            || currentColor.toLowerCase() == theDefaultColor1.toLowerCase() 
            || currentColor.toLowerCase() == theDefaultColor2.toLowerCase()) {
            if (theAction == 'over' && thePointerColor != '') {
                newColor              = thePointerColor;
            } else if (theAction == 'click' && theMarkColor != '') {
                newColor              = theMarkColor;
                marked_row[theRowNum] = true;
            }
        }
        // 4.1.2 Current color is the pointer one
        else if (currentColor.toLowerCase() == thePointerColor.toLowerCase()
                 && (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])) {
            if (theAction == 'out') {
                if (c % 2) {
                    newColor              = theDefaultColor1;
                } else {
                    newColor              = theDefaultColor2;
                }
            }
            else if (theAction == 'click' && theMarkColor != '') {
                newColor              = theMarkColor;
                marked_row[theRowNum] = true;
            }
        }
        // 4.1.3 Current color is the marker one
        else if (currentColor.toLowerCase() == theMarkColor.toLowerCase()) {
            if (theAction == 'click') {
                newColor              = (thePointerColor != '')
                                      ? thePointerColor
                                      : ((c % 2) ? theDefaultColor1 : theDefaultColor2);
                marked_row[theRowNum] = (typeof(marked_row[theRowNum]) == 'undefined' || !marked_row[theRowNum])
                                      ? true
                                      : null;
            }
        } // end 4

        // 5. Sets the new color...
        if (newColor) {
            if (domDetect) {
                theCells[c].setAttribute('bgcolor', newColor, 0);
            }
            // 5.2 ... with other browsers
            else {
                theCells[c].style.backgroundColor = newColor;
            }
        } // end 5
    } // end for

     return true;
 } // end of the 'setVerticalPointer()' function

/**
 * Checks/unchecks all tables
 *
 * @param   string   the form name
 * @param   boolean  whether to check or to uncheck the element
 *
 * @return  boolean  always true
 */
function setCheckboxes(the_form, do_check)
{
    var elts      = (typeof(document.forms[the_form].elements['selected_db[]']) != 'undefined')
                  ? document.forms[the_form].elements['selected_db[]']
                  : (typeof(document.forms[the_form].elements['selected_tbl[]']) != 'undefined')
          ? document.forms[the_form].elements['selected_tbl[]']
          : document.forms[the_form].elements['selected_fld[]'];
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) {
        for (var i = 0; i < elts_cnt; i++) {
            elts[i].checked = do_check;
        } // end for
    } else {
        elts.checked        = do_check;
    } // end if... else

    return true;
} // end of the 'setCheckboxes()' function


/**
  * Checks/unchecks all options of a <select> element
  *
  * @param   string   the form name
  * @param   string   the element name
  * @param   boolean  whether to check or to uncheck the element
  *
  * @return  boolean  always true
  */
function setSelectOptions(the_form, the_select, do_check)
{
    var selectObject = document.forms[the_form].elements[the_select];
    var selectCount  = selectObject.length;

    for (var i = 0; i < selectCount; i++) {
        selectObject.options[i].selected = do_check;
    } // end for

    return true;
} // end of the 'setSelectOptions()' function

/**
  * Allows moving around inputs/select by Ctrl+arrows
  *
  * @param   object   event data   
  */
function onKeyDownArrowsHandler(e) {
    e = e||window.event;
    var o = (e.srcElement||e.target);
    if (!o) return;
    if (o.tagName != "TEXTAREA" && o.tagName != "INPUT" && o.tagName != "SELECT") return;
    if (!e.ctrlKey) return;
    if (!o.id) return;

    var pos = o.id.split("_");
    if (pos[0] != "field" || typeof pos[2] == "undefined") return;

    var x = pos[2], y=pos[1];
    
    // skip non existent fields
    for (i=0; i<10; i++)
    {
        switch(e.keyCode) {
            case 38: y--; break; // up
            case 40: y++; break; // down
            case 37: x--; break; // left
            case 39: x++; break; // right
            default: return;
        }

        var id = "field_" + y + "_" + x;
        var nO = document.getElementById(id);
        if (nO) break;
    }
    
    if (!nO) return;
    nO.focus();
    if (nO.tagName != 'SELECT') {
        nO.select();
    }
    e.returnValue = false;
}

/**
  * Inserts multiple fields.
  *
  */
function insertValueQuery() {
    var myQuery = document.sqlform.sql_query;
    var myListBox = document.sqlform.dummy;

    if(myListBox.options.length > 0) {
        var chaineAj = "";
        var NbSelect = 0;
        for(var i=0; i<myListBox.options.length; i++) {
            if (myListBox.options[i].selected){
                NbSelect++;
                if (NbSelect > 1)
                    chaineAj += ", ";
                chaineAj += myListBox.options[i].value;
            }
        } 

        //IE support
        if (document.selection) {
            myQuery.focus();
            sel = document.selection.createRange();
            sel.text = chaineAj;
            document.sqlform.insert.focus();
        }
        //MOZILLA/NETSCAPE support
        else if (document.sqlform.sql_query.selectionStart || document.sqlform.sql_query.selectionStart == "0") {
            var startPos = document.sqlform.sql_query.selectionStart;
            var endPos = document.sqlform.sql_query.selectionEnd;
            var chaineSql = document.sqlform.sql_query.value;
        
            myQuery.value = chaineSql.substring(0, startPos) + chaineAj + chaineSql.substring(endPos, chaineSql.length);
        } else {
            myQuery.value += chaineAj;
        }
    }
}

/**
  * listbox redirection
  */
function goToUrl(selObj, goToLocation){
    eval("document.location.href = '" + goToLocation + "pos=" + selObj.options[selObj.selectedIndex].value + "'");
}


var global_xmlhttp = false;

var json_objects = new Object();

function loadXMLHTTP()
{

 if ( global_xmlhttp != false)
 {
   return;
 }

 try
 {
  global_xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
 }
 catch (e)
 {
  try
  {
   global_xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  catch (E)
  {
   global_xmlhttp = false;
  }
 }

 if (!global_xmlhttp && typeof XMLHttpRequest!='undefined')
 {
  global_xmlhttp = new XMLHttpRequest();
 }
}


// NOW LOAD THE OBJECT..
loadXMLHTTP();


function call_json_method(module,action,vars,variable_name,callback)
{

 global_xmlhttp.open("GET", "json.php?module="+module+"&action="+action+"&"+vars,true);

 global_xmlhttp.onreadystatechange= function() {

 if (global_xmlhttp.readyState==4)
 {

  
  if (global_xmlhttp.status == 200)
  {
	try
        {
		eval("json_objects['"+variable_name+"'] =" + global_xmlhttp.responseText);
        }
        catch(exception)
        {
         alert("ERROR:"+exception);
        }

   var args = {responseText:global_xmlhttp.responseText,responseXML:global_xmlhttp.responseXML};
   callback.call(document,args);
  }
  else
  {
    alert("There was a problem retrieving the XML data:\n" +
      global_xmlhttp.statusText);
  }
 }
}

global_xmlhttp.send(null);
}

function insert_at_cursor(field, value) 
{
 //ie:

 if (document.selection) 
 {
  field.focus();
  sel = document.selection.createRange();
  sel.text = value;
 }
 //mozilla:
 else if (field.selectionStart || field.selectionStart == '0') 
 {
  var start_pos = field.selectionStart;
  var end_pos = field.selectionEnd;
  field.value = field.value.substring(0, start_pos) + value + field.value.substring(end_pos, field.value.length);
 } 
 else 
 {
   field.value += value;
 }
}
