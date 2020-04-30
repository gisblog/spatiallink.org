////////////////////////////////////////////////// // class: SugarWidgetListView
// widget to display a list view
//
//////////////////////////////////////////////////


GLOBAL_REGISTRY['widget_element_map'] = new Object();

SugarClass.inherit("SugarWidgetListView","SugarClass");

function SugarWidgetListView()
{
        this.init();
}

SugarWidgetListView.prototype.init = function() {

}

SugarWidgetListView.prototype.load = function(parentNode) {

  this.parentNode = parentNode;

  this.display();

}


SugarWidgetListView.prototype.display = function() {
//alert("DISPLAY");

if(typeof GLOBAL_REGISTRY['result_list'] == 'undefined')
{
 this.display_loading();
 return;
}

 var div = document.getElementById('list_div_win');
 div.style.display = 'block';
 //div.style.height='125px';
 var html = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="listView">';
   html += '<tr>';
   html += '<td scope="col" width="2%" class="listViewThS1" NOWRAP>&nbsp;</td>';
   html += '<td scope="col" width="20%" class="listViewThS1" NOWRAP>'+GLOBAL_REGISTRY['meeting_strings']['LBL_NAME']+'</td>';
   html += '<td scope="col" width="20%" class="listViewThS1" NOWRAP>'+GLOBAL_REGISTRY['meeting_strings']['LBL_EMAIL']+'</td>';
   html += '<td scope="col" width="20%" class="listViewThS1" NOWRAP>'+GLOBAL_REGISTRY['meeting_strings']['LBL_PHONE']+'</td>';
   html += '<td scope="col" width="18%" class="listViewThS1" NOWRAP>&nbsp;</td>';
   html += '</tr>';
 //var html = '<table width="100%" cellpadding="0" cellspacing="0">';
 for(var i=0;i<GLOBAL_REGISTRY['result_list'].length;i++)
 {
   var bean = GLOBAL_REGISTRY['result_list'][i];
   var disabled = false;
   

   if (  typeof( GLOBAL_REGISTRY.focus.users_arr_hash[ bean.fields.id] ) != 'undefined') {
     disabled = true;
   }
   var className='schedulerEvenListRow';
   if ( (i%2) == 0 )
   {
      className='schedulerOddListRow';
   }
   else {
      className='schedulerEvenListRow';
   }
   if ( typeof (bean.fields.first_name) == 'undefined')
   { bean.fields.first_name = ''; }

   if ( typeof (bean.fields.email1) == 'undefined')
   { bean.fields.email1 = ''; }

   if ( typeof (bean.fields.phone_work) == 'undefined')
   { bean.fields.phone_work = ''; }

   html += '<tr class="'+className+'">';
   html += '<td class="'+className+'"><img src="'+GLOBAL_REGISTRY.config['site_url']+'/themes/'+GLOBAL_REGISTRY.current_user.theme+'/images/'+bean.module+'s.gif"/></td>';
   html += '<td class="'+className+'">'+bean.fields.first_name+' '+bean.fields.last_name+'</td>';
   html += '<td class="'+className+'">'+bean.fields.email1+'</td>';
   html += '<td class="'+className+'">'+bean.fields.phone_work+'</td>';
   html += '<td class="'+className+'" align="right">';
 //  hidden = 'hidden';
   hidden = 'visible';
   if ( ! disabled )
   {
//	hidden = 'visible';
   }
    html += '<input type="button" class="button" onclick="this.disabled=true;SugarWidgetSchedulerAttendees.form_add_attendee('+i+');" value="'+GLOBAL_REGISTRY['meeting_strings']['LBL_ADD_BUTTON']+'"/ style="visibility: '+hidden+'"/>';
   html += '</td>';
   
   html += '</tr>';
   html += '<tr><td colspan="20" class="listViewHRS1"></td></tr>';
 }
   html += '</table>';
this.parentNode.innerHTML = html;
/*
var str = '';
var elem =  this.parentNode;
for( sty in elem.style)
{
  if (typeof(elem.style[sty]) != 'function')
  {
    str += 'style:'+sty+',value:'+elem.style.sty+'\n';
  }
}
alert(str);
*/
}

SugarWidgetListView.prototype.display_loading = function() {


// var html = '<div style="display: none;background: #ffffff; width: 100%;height: 100%"></div>';
//this.parentNode.innerHTML = html;
}

//////////////////////////////////////////////////
// class: SugarWidgetSchedulerSearch
// widget to display the meeting scheduler search box
//
//////////////////////////////////////////////////

SugarClass.inherit("SugarWidgetSchedulerSearch","SugarClass");

function SugarWidgetSchedulerSearch()
{
        this.init();
}

SugarWidgetSchedulerSearch.prototype.init = function() {
      this.form_id = 'scheduler_search';
      GLOBAL_REGISTRY['widget_element_map'][this.form_id] = this;
}

SugarWidgetSchedulerSearch.prototype.load = function(parentNode){
  this.parentNode = parentNode;
  this.display();
}

SugarWidgetSchedulerSearch.submit = function(form){
  //construct query obj:
  var conditions  = new Array();
  
  if ( form.search_first_name.value != '')
  {
  conditions[conditions.length] = {"name":"first_name","op":"starts_with","value":form.search_first_name.value}
  }

  if ( form.search_last_name.value != '')
  {
    conditions[conditions.length] = {"name":"last_name","op":"starts_with","value":form.search_last_name.value}
  }

  if ( form.search_email.value != '')
  {
    conditions[conditions.length] = {"name":"email1","op":"starts_with","value":form.search_email.value}
  }

  var query = {"modules":["Users","Contacts"],"group":"and","field_list":['id','first_name','last_name','email1'],"conditions":conditions}

      global_request_registry[req_count] = [this,'display'];
      req_id = global_rpcClient.call_method('query',query);
      global_request_registry[req_id] = [ GLOBAL_REGISTRY['widget_element_map'][form.id],'refresh_list'];

      //req_count++;
}

SugarWidgetSchedulerSearch.prototype.refresh_list =  function(rslt) {
GLOBAL_REGISTRY['result_list'] = rslt['list'];
this.list_view.display();
}


SugarWidgetSchedulerSearch.prototype.display = function() {
  var html ='<br/><h5 class="listViewSubHeadS1">'+GLOBAL_REGISTRY['meeting_strings']['LBL_ADD_INVITEE']+'</h5><table border="0" cellpadding="0" cellspacing="0" width="100%" class="tabForm">';
    html +='<tr><td>';
    html += '<form id="'+this.form_id+'" onsubmit="SugarWidgetSchedulerSearch.submit(this);return false;">';

    html += '<table width="100%" cellpadding="0" cellspacing="0" width="100%" >'
    html += '<tr>';
    //html += '<form id="'+this.form_id+'"><table width="100%"><tbody><tr>';
    html += '<td  class="dataLabel" nowrap><b>'+GLOBAL_REGISTRY['meeting_strings']['LBL_FIRST_NAME']+':</b>&nbsp;&nbsp;<input class="dataField" name="search_first_name" value="" type="text" size="10"></td>';
    html += '<td class="dataLabel" nowrap><b>'+GLOBAL_REGISTRY['meeting_strings']['LBL_LAST_NAME']+':</b>&nbsp;&nbsp;<input class="dataField" name="search_last_name" value="" type="text" size="10"></td>';
    html += '<td class="dataLabel" nowrap><b>'+GLOBAL_REGISTRY['meeting_strings']['LBL_EMAIL']+':</b>&nbsp;&nbsp;<input class="dataField" name="search_email" type="text" value="" size="15"></td>';
    //html += '<td valign="center"><input type="submit" onclick="SugarWidgetSchedulerSearch.submit(this.form);" value="Search" ></td></tr></tbody></table></form>';
    html += '<td valign="center"><input type="submit" class="button" value="'+GLOBAL_REGISTRY['meeting_strings']['LBL_SEARCH_BUTTON']+'" ></td></tr>';
    html += '</table>';

  html += '</form>';
  html += '</td></tr></table>';

  // append the list_view as the third row of the outside table
  this.parentNode.innerHTML= html;

  var div = document.createElement('div');
  div.setAttribute('id','list_div_win');
  div.style.overflow = 'auto'; 
  div.style.width = '100%'; 
  div.style.height= '125px';
  div.style.display = 'none';

  this.list_view = new SugarWidgetListView();
  this.list_view.load(div);

  this.parentNode.appendChild(document.createElement('br'));
  this.parentNode.appendChild(div);
}

//////////////////////////////////////////////////
// class: SugarWidgetScheduler
// widget to display the meeting scheduler
//
//////////////////////////////////////////////////

SugarClass.inherit("SugarWidgetScheduler","SugarClass");

function SugarWidgetScheduler()
{
        this.init();
}

SugarWidgetScheduler.prototype.init = function(){
         //var row = new  SugarWidgetScheduleAttendees();
         //row.load(this);
}

SugarWidgetScheduler.prototype.load = function(parentNode){
  this.parentNode = parentNode;
  this.display();
}

SugarWidgetScheduler.fill_invitees = function(form) {

  for(var i=0;i<GLOBAL_REGISTRY.focus.users_arr.length;i++)
  {
   if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'User')
   {
     form.user_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
   }
   else if ( GLOBAL_REGISTRY.focus.users_arr[i].module == 'Contact')
   {
     form.contact_invitees.value += GLOBAL_REGISTRY.focus.users_arr[i].fields.id + ",";
   }
  }

}

SugarWidgetScheduler.update_time = function()
{
        GLOBAL_REGISTRY.focus.fields.date_start = document.EditView.date_start.value;
        var minute_start = document.EditView.time_minute_start.value;

        if ( minute_start == "0")
        {
          minute_start = "00";
        }

	if ( typeof( document.EditView.meridiem ) != 'undefined' )
        {
         GLOBAL_REGISTRY.focus.fields.time_start = document.EditView.time_hour_start.value+time_separator+minute_start + document.EditView.meridiem[document.EditView.meridiem.selectedIndex].value;
        } else {
         GLOBAL_REGISTRY.focus.fields.time_start = document.EditView.time_hour_start.value+time_separator+minute_start;
	}

        GLOBAL_REGISTRY.focus.fields.duration_hours = document.EditView.duration_hours.value;
        GLOBAL_REGISTRY.focus.fields.duration_minutes = document.EditView.duration_minutes.value;
        GLOBAL_REGISTRY.focus.fields.datetime_start = SugarDateTime.mysql2jsDateTime(GLOBAL_REGISTRY.focus.fields.date_start,GLOBAL_REGISTRY.focus.fields.time_start);

        GLOBAL_REGISTRY.scheduler_attendees_obj.init();
        GLOBAL_REGISTRY.scheduler_attendees_obj.display();
}

SugarWidgetScheduler.prototype.display = function(){
  var table = document.createElement('table');
  table.width="100%";
  table.border="0";
  table.cellspacing="0";

  var tr = table.insertRow(table.rows.length);
  var td = tr.insertCell(tr.cells.length);
//  td.appendChild(document.createTextNode('dflkj'));

  var attendees = new SugarWidgetSchedulerAttendees();
  attendees.load(td);

  var tr = table.insertRow(table.rows.length);
  var td = tr.insertCell(tr.cells.length);
  var search = new SugarWidgetSchedulerSearch();
  search.load(td);

  if(this.parentNode.childNodes.length == 0 )
  {
    this.parentNode.appendChild(table);
  } else {
    this.parentNode.replaceChild(table,this.parentNode.childNodes[0]);
  }
}
//////////////////////////////////////////////////
// class: SugarWidgetSchedulerAttendees 
// widget to display the meeting attendees and availability
//
//////////////////////////////////////////////////

SugarClass.inherit("SugarWidgetSchedulerAttendees","SugarClass");

function SugarWidgetSchedulerAttendees()
{
        this.init();
}

    SugarWidgetSchedulerAttendees.prototype.init = function(){


      // this.datetime = new SugarDateTime();
       GLOBAL_REGISTRY.scheduler_attendees_obj = this;


      // if ( typeof( GLOBAL_REGISTRY.focus.fields.date_start ) == 'undefined' )
      // {
        var minute_start = document.EditView.time_minute_start.value;
        if ( minute_start == "0")
        {
          minute_start = "00";
        }

        if ( typeof( document.EditView.meridiem ) != 'undefined' )
        {
         GLOBAL_REGISTRY.focus.fields.time_start = document.EditView.time_hour_start.value+time_separator+minute_start + document.EditView.meridiem[document.EditView.meridiem.selectedIndex].value;
        } else {
         GLOBAL_REGISTRY.focus.fields.time_start = document.EditView.time_hour_start.value+time_separator+minute_start;
        }

        GLOBAL_REGISTRY.focus.fields.date_start = document.EditView.date_start.value;

        GLOBAL_REGISTRY.focus.fields.duration_hours = document.EditView.duration_hours.value;
        GLOBAL_REGISTRY.focus.fields.duration_minutes = document.EditView.duration_minutes.value;
        GLOBAL_REGISTRY.focus.fields.datetime_start = SugarDateTime.mysql2jsDateTime(GLOBAL_REGISTRY.focus.fields.date_start,GLOBAL_REGISTRY.focus.fields.time_start);
      // }

       this.timeslots = new Array();
       this.hours = 9;
       this.segments = 4;
       this.start_hours_before = 4;
       var minute_interval = 15;

       var dtstart =  GLOBAL_REGISTRY.focus.fields.datetime_start;

       // initialize first date in timeslots
       var curdate = new Date(dtstart.getFullYear(),dtstart.getMonth(),dtstart.getDate(),dtstart.getHours()-this.start_hours_before,0);

if ( typeof(GLOBAL_REGISTRY.focus.fields.duration_minutes) == 'undefined')
{
  GLOBAL_REGISTRY.focus.fields.duration_minutes = 0;
}
//alert(GLOBAL_REGISTRY.focus.fields.duration_hours+'='+GLOBAL_REGISTRY.focus.fields.duration_minutes);
       GLOBAL_REGISTRY.focus.fields.datetime_end = new Date(dtstart.getFullYear(),dtstart.getMonth(),dtstart.getDate(),dtstart.getHours()+parseInt(GLOBAL_REGISTRY.focus.fields.duration_hours),dtstart.getMinutes()+parseInt(GLOBAL_REGISTRY.focus.fields.duration_minutes),0);

       var has_start = false;
      var has_end = false;

       for(i=0;i < this.hours*this.segments; i++)
       {
         var hash = SugarDateTime.getUTCHash(curdate);
         var obj = {"hash":hash,"date_obj":curdate};
         if (has_start == false && GLOBAL_REGISTRY.focus.fields.datetime_start.getTime() <= curdate.getTime())
         {
           obj.is_start = true;
           has_start = true;
         }
         if (has_end == false && GLOBAL_REGISTRY.focus.fields.datetime_end.getTime() <= curdate.getTime())
         {
           obj.is_end = true;
           has_end = true;
         }
         this.timeslots.push(obj);

         curdate = new Date(curdate.getFullYear(),curdate.getMonth(),curdate.getDate(),curdate.getHours(),curdate.getMinutes()+minute_interval);
       }

    }

    SugarWidgetSchedulerAttendees.prototype.load = function (parentNode) {
      this.parentNode = parentNode;
      this.display();
    }



    SugarWidgetSchedulerAttendees.prototype.display = function() {
       var dtstart = GLOBAL_REGISTRY.focus.fields.datetime_start;
       var top_date = SugarDateTime.getFormattedDate(dtstart); 

       var html = '<div class="schedulerDiv"><table class="schedulerTable" border=0 cellpadding=0 cellspacing=0>';
       html += '<tr class="schedulerTopRow">';
       html += '<td height="20" align="center" class="schedulerTopDateCell" colspan="'+((this.hours*this.segments)+2)+'">'+ top_date +'</td>';
       html += '</tr>';
       html += '<tr class="schedulerTimeRow">';
       html += '<td class="schedulerAttendeeHeaderCell">&nbsp;</td>';


       for(var i=0;i < (this.timeslots.length/this.segments); i++)
       {
         var hours = this.timeslots[i*this.segments].date_obj.getHours();
         var am_pm = '';
	 if ( time_reg_format.indexOf('A') >= 0 || time_reg_format.indexOf('a') >= 0)
         {
		am_pm = "AM";
		if (hours > 12)
		{
			am_pm = "PM";
			hours -= 12;
		}
		if (hours == 12)
		{
			am_pm = "PM";
		}
		if (hours == 0)
		{
			 hours = 12;
			am_pm = "AM";
		}
	   	if ( time_reg_format.indexOf('a') >= 0)
		{
			am_pm = am_pm.toLowerCase();	
		}
		if (hours != 0 && hours != 12 && i != 0)
		{
			am_pm = "";
		}

         }
         var form_hours = hours+time_separator+"00";
         html += '<td colspan="'+this.segments+'" class="schedulerTimeCell">'+form_hours+am_pm+'</td>';
       }
       html += '<td class="schedulerDeleteHeaderCell">&nbsp;</td>';
       html += '</tr>';
       html += '</table></div>';
       this.parentNode.innerHTML = html;
       var thetable = this.parentNode.getElementsByTagName('tbody')[0];


       if ( typeof (GLOBAL_REGISTRY) == 'undefined')
       { return; }

       if (typeof (GLOBAL_REGISTRY.focus.users_arr) == 'undefined' || GLOBAL_REGISTRY.focus.users_arr.length == 0)
       {
          GLOBAL_REGISTRY.focus.users_arr = [ GLOBAL_REGISTRY.current_user];
       }

       if (typeof GLOBAL_REGISTRY.focus.users_arr_hash == 'undefined')
       {
         GLOBAL_REGISTRY.focus.users_arr_hash = new Object();
       }

/*
       if (typeof (GLOBAL_REGISTRY.focus.contacts_arr) == 'undefined' || GLOBAL_REGISTRY.focus.contacts_arr.length == 0)
       {
          GLOBAL_REGISTRY.focus.contacts_arr = new Array();
       }
       if (typeof GLOBAL_REGISTRY.focus.users_arr_hash == 'undefined')
       {
         GLOBAL_REGISTRY.focus.users_arr_hash = new Object();
       }
*/

       // append attendee rows
/*
var str = '';
for(n in GLOBAL_REGISTRY.focus.users_arr[0])
{
  str += "VAR:"+n+":"+GLOBAL_REGISTRY.focus.users_arr[0][n]+"\n";
}
*/

       for(var i=0;i < GLOBAL_REGISTRY.focus.users_arr.length;i++)
       {
         var row = new  SugarWidgetScheduleRow(this.timeslots);
         row.focus_bean = GLOBAL_REGISTRY.focus.users_arr[i];
         GLOBAL_REGISTRY.focus.users_arr_hash[ GLOBAL_REGISTRY.focus.users_arr[i]['fields']['id']] =  GLOBAL_REGISTRY.focus.users_arr[i];
         row.load(thetable);
       }

    }
    SugarWidgetSchedulerAttendees.form_add_attendee = function (list_row) {
         if ( typeof (GLOBAL_REGISTRY.result_list[list_row]) != 'undefined' && typeof( GLOBAL_REGISTRY.focus.users_arr_hash[ GLOBAL_REGISTRY.result_list[list_row].fields.id] ) == 'undefined')
         {
           GLOBAL_REGISTRY.focus.users_arr[ GLOBAL_REGISTRY.focus.users_arr.length ] = GLOBAL_REGISTRY.result_list[list_row];
         }
         GLOBAL_REGISTRY.scheduler_attendees_obj.display();
   
    }

//////////////////////////////////////////////////
// class: SugarWidgetScheduleRow
// widget to display each row in the scheduler
//
//////////////////////////////////////////////////

SugarClass.inherit("SugarWidgetScheduleRow","SugarClass");

function SugarWidgetScheduleRow(timeslots)
{
        this.init(timeslots);
}

    SugarWidgetScheduleRow.prototype.init = function(timeslots){
      this.timeslots = timeslots;
    }

    SugarWidgetScheduleRow.prototype.load = function (thetable) {

      this.thetable = thetable;
      this.display();
      var self = this;

    //  this.display();

      vcalClient =  new SugarVCalClient();
      if ( typeof ( GLOBAL_REGISTRY['freebusy']) == 'undefined' ||  typeof (GLOBAL_REGISTRY['freebusy'][this.focus_bean.fields.id]) == 'undefined')
      {
        global_request_registry[req_count] = [this,'display'];
        vcalClient.load(this.focus_bean.fields.id,req_count);
        req_count++;
      }
      else
      {
        this.display();
      }
    }

    SugarWidgetScheduleRow.prototype.display = function() {
      var self = this;
      var tr;
      if (typeof (this.element) != 'undefined')
      {
          this.thetable.deleteRow(this.element_index );

        tr = this.thetable.insertRow(this.element_index);
      }
      else
      {
        tr = this.thetable.insertRow(this.thetable.rows.length);
      }
      tr.className = "schedulerAttendeeRow";
      //var td = tr.insertCell(tr.cells.length);
      //td.className = 'scheduleAttendeeIconCell';
      //td.align="right";
      

      //var img = '<img src="'+GLOBAL_REGISTRY.config['site_url']+'/themes/Sugar/images/'+self.focus_bean.module+'s.gif"/>';
      //td.innerHTML = img;

      var td = tr.insertCell(tr.cells.length);
      td.className = 'schedulerAttendeeCell';
      var img = '<img align="absmiddle" src="'+GLOBAL_REGISTRY.config['site_url']+'/themes/'+GLOBAL_REGISTRY.current_user.theme+'/images/'+self.focus_bean.module+'s.gif"/>&nbsp;';
      td.innerHTML = img;
      td.appendChild(document.createTextNode(self.focus_bean.fields.first_name+' '+self.focus_bean.fields.last_name));

      // add freebusy tds here:
      this.add_freebusy_nodes(tr);

      var td = tr.insertCell(tr.cells.length);
      td.className = 'schedulerAttendeeDeleteCell';
      td.innerHTML = '<a class="listViewTdToolsS1" href="javascript:SugarWidgetScheduleRow.deleteRow(\''+self.focus_bean.fields.id+'\');">&nbsp;<img src="themes/'+GLOBAL_REGISTRY.current_user.theme+'/images/delete_inline.gif" align="absmiddle" alt="del" border="0"> del</a>';

      this.element = tr;
      this.element_index = this.thetable.rows.length - 1;

    }

    SugarWidgetScheduleRow.deleteRow = function(bean_id) {
      if (GLOBAL_REGISTRY.focus.users_arr.length == 1)
      {
       return;
      }
      for(var i=0;i<GLOBAL_REGISTRY.focus.users_arr.length;i++)
      {
         if(GLOBAL_REGISTRY.focus.users_arr[i]['fields']['id']==bean_id)
         {
           delete GLOBAL_REGISTRY.focus.users_arr_hash[GLOBAL_REGISTRY.focus.users_arr[i]['fields']['id']];
          GLOBAL_REGISTRY.focus.users_arr.splice(i,1);
           GLOBAL_REGISTRY.container.root_widget.display();
         }
      }
    }


function DL_GetElementLeft(eElement)
{
    if (!eElement && this)                       // if argument is invalid
    {                                            // (not specified, is null or is 0)
        eElement = this;                         // and function is a method
    }                                            // identify the element as the method owner
    
    var nLeftPos = eElement.offsetLeft;          // initialize var to store calculations
    var eParElement = eElement.offsetParent;     // identify first offset parent element  
    while (eParElement != null)
    {                                            // move up through element hierarchy
        nLeftPos += eParElement.offsetLeft;      // appending left offset of each parent
        eParElement = eParElement.offsetParent;  // until no more offset parents exist
    }
    return nLeftPos;                             // return the number calculated
}


function DL_GetElementTop(eElement)
{
    if (!eElement && this)
    {
        eElement = this;
    }

    var nTopPos = eElement.offsetTop;
    var eParElement = eElement.offsetParent;
    while (eParElement != null)
    {
        nTopPos += eParElement.offsetTop;
        eParElement = eParElement.offsetParent;
    }
    return nTopPos;
}


    //////////////////////////////////////////
    // adds the <td>s for freebusy display within a row
    SugarWidgetScheduleRow.prototype.add_freebusy_nodes = function(tr,attendee) {
      var hours = 9;
      var segments = 4;
      var html = '';
      var is_loaded = false;

      if ( typeof GLOBAL_REGISTRY['freebusy'] != 'undefined' && typeof GLOBAL_REGISTRY['freebusy'][this.focus_bean.fields.id] != 'undefined')
      {
          is_loaded = true;
      }

      var in_current = false;

      for(var i=0;i < this.timeslots.length; i++)
      {
        var cellclassname = 'schedulerSlotCellHour';
        var td = tr.insertCell(tr.cells.length);
        //td.getTrueXPosition = DL_GetElementLeft;
	//td.getTrueYPosition = DL_GetElementTop;

        if ( typeof(this.timeslots[i]['is_start']) != 'undefined')
        {
             in_current = true;
              cellclassname = 'schedulerSlotCellStartTime';
        }
        if ( typeof(this.timeslots[i]['is_end']) != 'undefined')
        {
             in_current = false;
                cellclassname = 'schedulerSlotCellEndTime';
        }

        td.className =cellclassname;

        if ( in_current)
        {
          td.style.backgroundColor="#ffffff";
        } 

        if ( is_loaded )
        {
          // if theres a freebusy stack in this slice
          if (  typeof( GLOBAL_REGISTRY['freebusy'][this.focus_bean.fields.id][this.timeslots[i].hash]) != 'undefined' )
          {
             td.style.backgroundColor="#4D5EAA";

             if ( in_current) 
             {
                 fb_limit = 1; 
                if (typeof(GLOBAL_REGISTRY.focus.orig_users_arr_hash) != 'undefined' && typeof(GLOBAL_REGISTRY.focus.orig_users_arr_hash[this.focus_bean.fields.id]) != 'undefined' )
                {
                 fb_limit = 2;
                }

                if (  GLOBAL_REGISTRY['freebusy'][this.focus_bean.fields.id][this.timeslots[i].hash] >= fb_limit )
                {
                  // divclassname = 'schedulerSlotConflictDiv';
                  td.style.backgroundColor="#AA4D4D";
                } else {
	          // divclassname = 'schedulerSlotScheduledDiv';
                  //td.style.backgroundColor="#75AF4F";
                  td.style.backgroundColor="#4D5EAA";
                }
            }
          }
         }
      }
    }

