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

//////////////////////////////////////////////////////////////////
// called on the return of a JSON-RPC async request,
// and calls the display() method on the widget registered
// in the registry at the request_id key returned by the server


//////////////////////////////////////////////////////////////////


function method_callback (request_id,rslt,e)
{
  if (rslt == null)
  {
    if ( typeof( e )== 'object' && typeof (e.error_msg) != 'undefined')
    {
    alert("Error from json server. Message:"+e.error_msg);
    } 
    else{
      alert("Error calling json server. Message:"+e);
    }
    return;
  }
  if ( typeof (global_request_registry[request_id]) != 'undefined')
  {

    widget = global_request_registry[request_id][0];
    method_name = global_request_registry[request_id][1];

    widget[method_name](rslt);
  }
}
                                                                                   

//////////////////////////////////////////////////
// class: SugarVCalClient
// async retrieval/parsing of vCal freebusy info 
// 
//////////////////////////////////////////////////

SugarClass.inherit("SugarVCalClient","SugarClass");

function SugarVCalClient()
{
        this.init();
}
  
    SugarVCalClient.prototype.init = function(){
      //this.urllib = importModule("urllib");
    }

    SugarVCalClient.prototype.load = function(user_id,request_id){

      this.user_id = user_id;

      // get content at url and declare the callback using anon function:
      urllib.getURL(  GLOBAL_REGISTRY.config['site_url']+'/vcal_server.php?type=vfb&source=outlook&user_id='+user_id,[["Content-Type", "text/plain"]], function (result) { 
                  if (typeof GLOBAL_REGISTRY.freebusy == 'undefined')
                  {
                     GLOBAL_REGISTRY.freebusy = new Object();
                  }
                  // parse vCal and put it in the registry using the user_id as a key:
                  GLOBAL_REGISTRY.freebusy[user_id] = SugarVCalClient.parseResults(result.responseText);
                  // now call the display() on the widget registered at request_id:
                  global_request_registry[request_id][0].display();
                  })
    }

    // parse vCal freebusy info and return object
    SugarVCalClient.prototype.parseResults = function(textResult){
      var match = /FREEBUSY.*?\:([\w]+)\/([\w]+)/g;
    //  datetime = new SugarDateTime();
      var result;
      var timehash = new Object();

      // loop thru all FREEBUSY matches
      while(((result= match.exec(textResult))) != null)
      {
        var startdate = SugarDateTime.parseUTCDate(result[1]);
        var enddate = SugarDateTime.parseUTCDate(result[2]);
        var startmins = startdate.getUTCMinutes();

        // pick the start slot based on the minutes
        if ( startmins >= 0 && startmins < 15) {
          startdate.setUTCMinutes(0);
        }
        else if ( startmins >= 15 && startmins < 30) {
          startdate.setUTCMinutes(15);
        }
        else if ( startmins >= 30 && startmins < 45) {
          startdate.setUTCMinutes(30);
        }
        else {
          startdate.setUTCMinutes(45);
        }
 
        // starting at startdate, create hash of each busy 15 min 
        // timeslot and store as a key
        for(var i=0;i<100;i++)
        {
          if (startdate.valueOf() < enddate.valueOf())
          {
            var hash = SugarDateTime.getUTCHash(startdate);
            if (typeof (timehash[hash]) == 'undefined')
            {
              timehash[hash] = 0;            
            }
            timehash[hash] += 1;            
            startdate = new Date(startdate.valueOf()+(15*60*1000));

          }
          else
          {
            break;
          }
        }
      }
      return timehash;  
    }
    SugarVCalClient.parseResults = SugarVCalClient.prototype.parseResults;
//    this.parseResults = publ.parseResults

//////////////////////////////////////////////////
// class: SugarRPCClient
// wrapper around async JSON-RPC client class
// 
//////////////////////////////////////////////////
SugarRPCClient.allowed_methods = ['retrieve','query','save','set_accept_status'];

SugarClass.inherit("SugarRPCClient","SugarClass");

function SugarRPCClient()
{
       this.init();
}

/*
 * PUT NEW METHODS IN THIS ARRAY:
 */
SugarRPCClient.prototype.allowed_methods = ['retrieve','query','save','set_accept_status'];

    SugarRPCClient.prototype.init = function(){
      this._serviceProxy;
      this._showError= function (e){ 
           alert("ERROR CONNECTING to: "+ GLOBAL_REGISTRY.config['site_url']+'/json_server.php, ERROR:'+e); 
      }
      this.serviceURL = GLOBAL_REGISTRY.config['site_url']+'/json_server.php';
       this._serviceProxy = new jsonrpc.ServiceProxy(this.serviceURL,this.allowed_methods);
    }

    SugarRPCClient.prototype.call_method = function(method,args){
      var self=this;

      try{ 
        //send text to server and retrieve the content of the chat.
         this._serviceProxy[method](args,method_callback);
         return this._serviceProxy.httpConn.request_id;
       }catch(e){//error before calling server
         this._showError(e);
       }
    }


var global_rpcClient =  new SugarRPCClient();
