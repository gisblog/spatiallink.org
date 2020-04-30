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

jsolait.baseURL = 'include/jsolait/lib';
urllib = importModule('urllib');

var global_request_registry = new Object();


///////////////////////////////////////////////
// Class SugarClass
// superclass for all Sugar* sub-classes
//
///////////////////////////////////////////////

function SugarClass()
{
 //   if ( arguments.length > 0 )
        this.init();
}

SugarClass.prototype.init = function() {
}

// create inheritance for a class
SugarClass.inherit = function(className,parentClassName) {

  var str = className+".prototype = new "+parentClassName+"();";
  str += className+".prototype.constructor = "+className+";";
  str += className+".superclass = "+parentClassName+".prototype;";

  try {
    eval(str);
  } catch (e) { }

}


var jsolait_baseURL = 'include/jsolait/lib';
var jsonrpc = jsolait.importModule("jsonrpc");

// Root class of Sugar JS Application:

SugarClass.inherit("SugarContainer","SugarClass");

function SugarContainer(root_div)
{
        GLOBAL_REGISTRY.container = this;
        this.init(root_div);
}

SugarContainer.prototype.init = function(root_div) {
    this.root_div = root_div;
    SugarContainer.superclass.init.call(this);
}

SugarContainer.prototype.start = function(root_widget) {

      this.root_widget = new root_widget();
      this.root_widget.load(this.root_div);
   
}

var req_count = 0;

//////////////////////////////////////////////////
// class: SugarDateTime 
// date and time utilities
//
//////////////////////////////////////////////////

SugarClass.inherit("SugarDateTime","SugarClass");

function SugarDateTime()
{
        this.init(root_div);
}

  SugarDateTime.prototype.init = function(root_div){
    this.root_div = root_div;
  }

  // return the javascript Date object
  // given the Sugar Meetings date_start/time_start or date_end/time_end
  SugarDateTime.mysql2jsDateTime = function(mysql_date,mysql_time){

      //var match = /(\d{4})-(\d{2})-(\d{2})/;
      var match = new RegExp(date_reg_format);
      if(((result= match.exec(mysql_date))) == null)
      {
         return null;
      }

      var match2 = new RegExp(time_reg_format);
     // var match2 = /(\d{2}):(\d{2})/;

      if((result2= match2.exec(mysql_time)) == null)
      {
         result2= [0,0,0,0];
      }
      var match3 = /^0(\d)/;

      if((result3= match3.exec(result2[1])) != null)
      {
         result2[1] = result3[1];
      }

		 	if ( typeof (result2[3]) != 'undefined')
			{
       if ( result2[3] == 'pm' || result2[3] == 'PM')
       {
        if (parseInt( result2[1] ) != 12)
				{
         result2[1] = parseInt( result2[1] ) + 12;	
				}

       }
       else if ( result2[1] == 12 ) {
         result2[1] = 0;	
       }
			}

      return new Date(result[date_reg_positions['Y']],result[date_reg_positions['m']] - 1,result[date_reg_positions['d']],result2[1],result2[2],0,0);

    }
    // make it a static func

    // return the formatted day of the week of the date given a date object
    SugarDateTime.prototype.getFormattedDate = function(date_obj) {
      //return date_obj) + " " +
      var dow = GLOBAL_REGISTRY['calendar_strings']['dom_cal_weekdays_long'][date_obj.getDay()];
      var month = date_obj.getMonth() + 1;
      month = GLOBAL_REGISTRY['calendar_strings']['dom_cal_month_long'][month];
      return dow+" "+date_obj.getDate()+" "+month+" "+date_obj.getFullYear();
    }

    SugarDateTime.getFormattedDate = SugarDateTime.prototype.getFormattedDate;

    // return the formatted day of the week of the date given a date object
    SugarDateTime.prototype.getFormattedDOW = function(date_obj) {
      var hour = config.strings.mod_strings.Calendar.dow[date_obj.getDay()];
    }
    SugarDateTime.getFormattedDOW = SugarDateTime.prototype.getFormattedDOW;

    // return the formatted hour of the date given a date object
    SugarDateTime.getAMPM = function(date_obj) {
      var hour = date_obj.getHour();
      var am_pm = 'AM';
      if (hour > 12)
      {
        hour -= 12;
        am_pm = 'PM';
      }
      else if ( hour == 12)
      {
        am_pm = 'PM';
      }
      else if (hour == 0)
      {
        hour = 12;
      }
      return am_pm;
    }
    SugarDateTime.getFormattedHour = SugarDateTime.prototype.getFormattedHour;

    //mod.SugarDateTime.getFormattedDate = publ.getFormattedDate;

    // return the javascript Date object given a vCal UTC string
  SugarDateTime.prototype.parseUTCDate = function(date_string) {

      var match = /(\d{4})(\d{2})(\d{2})T(\d{2})(\d{2})(\d{2})Z/;

      if(((result= match.exec(date_string))) != null)
      {
 //        alert(result[1]+","+result[2]+","+result[3]+","+result[4]+","+result[5]+","+result[6]);
         var new_date = new Date(Date.UTC(result[1],result[2] - 1,result[3],result[4],result[5],parseInt(result[6])+time_offset));
         return new_date;
      }

  }
  SugarDateTime.parseUTCDate = SugarDateTime.prototype.parseUTCDate;

    // create a hash based on a date
  SugarDateTime.prototype.getUTCHash = function(startdate){
            var month = ( startdate.getUTCMonth() < 10) ? "0"+startdate.getUTCMonth():""+startdate.getUTCMonth();
            var day = ( startdate.getUTCDate() < 10) ? "0"+startdate.getUTCDate():""+startdate.getUTCDate();
            var hours = ( startdate.getUTCHours() < 10) ? "0"+startdate.getUTCHours():""+startdate.getUTCHours();
            var minutes = ( startdate.getUTCMinutes() < 10) ? "0"+startdate.getUTCMinutes():""+startdate.getUTCMinutes();
            return startdate.getUTCFullYear()+month+day+hours+minutes;
            return startdate.getUTCFullYear()+month+day+hours+minutes;
  }
  SugarDateTime.getUTCHash = SugarDateTime.prototype.getUTCHash;

