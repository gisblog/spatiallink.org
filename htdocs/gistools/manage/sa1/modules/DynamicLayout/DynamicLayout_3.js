/**
 * Javascript file for Dynamic Layout
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

// $Id: DynamicLayout_3.js,v 1.1 2005/04/29 21:52:14 andrew Exp $

/******************************************
* Cross browser cursor trailer script- By Brian Caputo (bcaputo@icdc.com)
* Visit Dynamic Drive (http://www.dynamicdrive.com/) for full source code
* Modified Dec 31st, 02' by DD. This notice must stay intact for use
* Modified By SugarCRM on Dec 21st 04 
******************************************/

A=document.getElementById
B=document.all;
C=document.layers;


var offsetx=15//x offset of trail from mouse pointer
var offsety=0 //y offset of trail from mouse pointer

rate=50
ie5fix1=0;
ie5fix2=0;



function getXpos(N){
if (A)
return parseInt(document.getElementById(N).style.left)
else if (B)
return parseInt(B[N].style.left)
else
return C[N].left
}

function getYpos(N){
if (A)
return parseInt(document.getElementById(N).style.top)
else if (B)
return parseInt(B[N].style.top)
else
return C[N].top
}

function setDisplay(N){
if(N== 'hotswapcontainter'){
if(document.getElementById('form_hotswap').value == -1){
	document.getElementById(N).style.display='none';
}else{
	document.getElementById(N).style.display='inline';
	return true;
}
}else{

if(check_for_empty_string(document.getElementById(N).innerHTML) ||!document.getElementById('display_html_MSI').checked){
	document.getElementById(N).style.display='none';
}else{
	document.getElementById(N).style.display='inline';
	return true;
}

}
return false;
}
function moveContainer(e,N){
var posx = 0;
var posy = 0;


if(e != 'none'){
if (!e) var e = window.event;
if (e.pageX || e.pageY)
{
	posx = e.pageX;
	posy = e.pageY;
}
else if (e.clientX || e.clientY)
{
	posx = e.clientX + document.body.scrollLeft;
	posy = e.clientY + document.body.scrollTop;
}

c=(A)? document.getElementById(N).style : (B)? B[N].style : (C)? C[N] : "";

if(N== 'hotswapcontainter'){
c.left=posx + 15;
c.top=posy;
}else{
c.left=posx + 15;
c.top=posy + 20;
}
}
}


function newPos(e){
if(setDisplay('hotswapcontainter'))
moveContainer(e,'hotswapcontainter')
if(document.getElementById('display_html_MSI').checked && setDisplay('textcontainter'))
	moveContainer(e,'textcontainter')
}
function newDisplay(){
setDisplay('hotswapcontainter');

setDisplay('textcontainter');
}

function getedgesIE(){
rightedge=document.body.clientWidth
bottomedge=document.body.scrollHeight
}

if (B){
window.onload=getedgesIE
window.onresize=getedgesIE
}
var keyAction = 'none';
function setKeyAction(ev){
	if(document.layers){
		var key =  event.keyCode;
	}else var key =  ev.keyCode;
	if(key == 16){
		keyAction = 'shift';

	}
	
}

function unsetKeyAction(ev){
	if(document.layers){
		var key =  ev.keyCode;
	}else var key =  event.keyCode;
	if((key == 16) && keyAction == 'shift'){
		keyAction = 'none'
	}
	
}

function registerMouseMove(){
	if(document.layers)
		document.captureEvents(Event.MOUSEMOVE)
	else document.onmousemove=newPos
	if(document.layers)
		document.captureEvents(Event.KEYDOWN)
	else document.onkeydown = setKeyAction;
	if(document.layers)
		document.captureEvents(Event.KEYUP)
	else document.onkeyup = unsetKeyAction
	
	
}



/*
Add and remove dynamic field layout by SugarCRM

*/
var fields_MSI = new Array();
		var field_list_MSI = new Array();
		var field_count_MSI = 0;
		var last_id = '';
		
		
			
		function list_view_swap(count1, count2){
			midSlot = slotCount / 2;
			if(count1 >= midSlot){
				count1 = parseInt(count1) - midSlot;
				count2 = parseInt(count2) - midSlot;
			}else{
				count1 = parseInt(count1) + midSlot;
				count2 = parseInt(count2) + midSlot;
			}
			count1 = 'slot_' + count1;
			count2 = 'slot_' + count2;
			var counter = lookup_field('form_' + count1).value;
			var temp = lookup_field(count1).innerHTML;
			var add = lookup_field('add_' + count1).value;
			lookup_field(count1).innerHTML= lookup_field(count2).innerHTML;						lookup_field('form_' + count1).value = lookup_field('form_' + count2).value;
			lookup_field('add_' + count1).value = lookup_field('add_' + count2).value;
			
			lookup_field(count2).innerHTML= temp;
			lookup_field('form_' + count2).value = counter;			
			lookup_field('add_' + count2).value = add;
		}
		function swap_div(id){
			if(last_id != '' &&  keyAction == 'oneway'){
				alert('You may only remove items from this bin');
				keyAction = '';
				return;
			}
			if(file_type == 'list' && id.indexOf('slot_') > -1 ){
				
				if(last_id != '' && last_id.indexOf('slot_') > -1){
					id_count = id.substring(5, id.length);
					last_id_count = last_id.substring(5, last_id.length);
					midSlot = slotCount / 2 - .5;
					
					if((id_count - midSlot) * (last_id_count - midSlot) <  0 ){
						alert('Invalid Swap');
						cancel_swap_div();
						return;
					}else{
						list_view_swap(last_id_count,id_count );
					}
				}
			}
			var counter = lookup_field('form_' + id).value;
			var temp = lookup_field(id).innerHTML;
			var add = lookup_field('add_' + id).value;

			if(!((keyAction == 'shift' || keyAction == 'oneway') && last_id == '')){
				lookup_field(id).innerHTML= document.getElementById('hotswap').innerHTML;
				lookup_field('form_' + id).value = document.getElementById('form_hotswap').value;
				lookup_field('add_' + id).value = document.getElementById('add_hotswap').value;
				
			}
			if(last_id != '' && last_id != id){
				if(last_id.indexOf('dyn_field') == 0 && check_for_empty_string(temp)){
					remove_field_row_from_table(last_id, 'field_table_MSI');
					last_id = '';
					clear_hotswap();
					
				}else{
				lookup_field(last_id).innerHTML = temp;
				lookup_field('form_' + last_id).value = counter;
				lookup_field('add_' + last_id).value = add;
				last_id = '';
				clear_hotswap();
				}
				
			}else{
				document.getElementById('hotswap').innerHTML = temp;
				document.getElementById('form_hotswap').value = counter;
				document.getElementById('add_hotswap').value = add;
				if((keyAction == 'shift' || keyAction == 'oneway') && last_id == ''){
					if(keyAction == 'oneway'){
						remove_field_row_from_table(id, 'sugar_fields_MSI');
						remove_field_row_from_table(id, 'sugar_trash_MSI');
					}
					keyAction = 'none';
					new_id = add_field_row_to_table();
					last_id = new_id
					
				}else{
				if(last_id == id){
					last_id = '';
				}else{
				last_id = id;
				}
				}
			}
	newDisplay();

		}
		
		
		
		function swap_text(text){
			if(document.getElementById('display_html_MSI').checked){
	
		
				font_length = text.indexOf('>') + 1;
				text = text.substring(font_length, text.length);
				text = replaceAll(text, '>', '&gt;');
				text =replaceAll(text, '<', '<br>&lt;');
				
				document.getElementById('textcontainter').innerHTML = text;
				
			
			}
		}
		function clear_hotswap(){
			document.getElementById('hotswap').innerHTML = font_slot + '&nbsp;';
			document.getElementById('form_hotswap').value = -1;
			document.getElementById('add_hotswap').value = '';
		}
		function check_for_empty_id(id){
				cur_string = lookup_field(id).innerHTML;
				return check_for_empty_string(cur_string);
		}
		function check_for_empty_string(cur_string){
				
				font_length = trim(cur_string).indexOf('>') + 1;
				compare_string = trim(cur_string.substring(font_length, cur_string.length));
				return compare_string ==  '&nbsp;'|| compare_string ==  '&nbsp;&nbsp;' || compare_string == '';
		}
		function delete_div(){
			if(last_id != ''){

				if(last_id.indexOf('dyn_field') == 0 && check_for_empty_id(last_id)){
					//alert('Cannot delete a field that is already deleted');
					//cancel_swap_div();
				}else{
			
					if(check_for_empty_id('hotswap')){
						cancel_swap_div();
					}else{
					
					new_id = add_field_row_to_table();
					swap_div(new_id);
					}
				}
			}
		}
		
		
		function cancel_swap_div(){
			if(last_id != ''){
				swap_div(last_id);
			}
			last_id = '';
		}	
		function add_field_MSI(name){
			fields_MSI.push(name);
			field_list_MSI[name] = document.getElementById( name);
			field_list_MSI['add_' + name] = document.getElementById( 'add_' + name);
			field_list_MSI['form_' + name] = document.getElementById( 'form_' + name);
			document.getElementById('add_' + name ).value = document.getElementById( name ).innerHTML;
			document.getElementById( name).innerHTML = font_slot  + document.getElementById( name ).innerHTML;
		}
		function lookup_field(id){
			if(typeof(field_list_MSI[id]) != 'undefined'){
				return field_list_MSI[id];
			}
			return document.getElementById(id);
		}
		function add_field_row_to_table(){
			var name = 'dyn_field_' + field_count_MSI;
			var table = document.getElementById('field_table_MSI');
			var row = table.insertRow(table.rows.length);
			var cell = row.insertCell(0);
			var div = document.createElement('div');
			div.setAttribute('id', 'slot_' + name);
			div.style.display = 'inline';
			div.style.cursor = 'pointer';
			div.style.cursor = 'hand';
			div.onmousedown =  function(){swap_div(name);};
			
			
			//div.setAttribute('onclick', "swap_div('" + name + "')");
			var imageNode = document.createElement('img');
			imageNode.setAttribute('src', slot_path);
			//div.appendChild(imageNode);
			var textEl = document.createElement('input');
			textEl.setAttribute('type', 'hidden')
			textEl.setAttribute('name',  'add_' + name);
			textEl.setAttribute('id', 'add_' + name );
			field_list_MSI['add_' + name] = textEl;
			div.appendChild(textEl);
			var textEl = document.createElement('input');
			textEl.setAttribute('type', 'hidden')
			textEl.setAttribute('name',  'form_' + name );
			textEl.setAttribute('id', 'form_' + name  );
			textEl.setAttribute('value', '-1');
			field_list_MSI['form_' + name] = textEl;
			div.appendChild(textEl);
			var subdiv = document.createElement('div');
			subdiv.setAttribute('id',  name )
			subdiv.appendChild(imageNode);
			field_list_MSI[name] = subdiv;
			
			subdiv.onmouseover = function(){swap_text(this.innerHTML);}
			subdiv.onmouseout = function(){swap_text('&nbsp;');}

			div.appendChild(subdiv);
			cell.appendChild(div);
			field_count_MSI++;
			return name;
		
		}
		
		
		
		function add_new_field_row_to_table(name, html, table_id){
			html = replaceAll(html, "&qt;", '"');
			html = replaceAll(html, "&sqt;", "'");
			var table = document.getElementById(table_id);
			var row = table.insertRow(table.rows.length);
			var cell = row.insertCell(0);
			var div = document.createElement('div');
			div.setAttribute('id', 'slot_' + name);
			div.style.display = 'inline';
			div.style.cursor = 'pointer';
			div.style.cursor = 'hand';
			if(table_id != 'field_table_MSI'){
				div.onmousedown =  function(){keyAction='oneway'; swap_div(name);};
			}else{
				div.onmousedown =  function(){swap_div(name);};
			}
			
			//div.setAttribute('onclick', "swap_div('" + name + "')");
			var imageNode = document.createElement('img');
			imageNode.setAttribute('src', slot_path);
			//div.appendChild(imageNode);
			var textEl = document.createElement('input');
			textEl.setAttribute('type', 'hidden')
			textEl.setAttribute('name',  'add_' + name);
			textEl.setAttribute('id', 'add_' + name );
			textEl.setAttribute('value', html);
			field_list_MSI['add_' + name] = textEl;
			div.appendChild(textEl);
			var textEl = document.createElement('input');
			textEl.setAttribute('type', 'hidden')
			textEl.setAttribute('name',  'form_' + name );
			textEl.setAttribute('id', 'form_' + name  );
			textEl.setAttribute('value', '-66');
			field_list_MSI['form_' + name] = textEl;
			div.appendChild(textEl);
			var subdiv = document.createElement('div');
			subdiv.setAttribute('id',  name )
			subdiv.appendChild(imageNode);
			html = html.replace(/(<input)([^>]*)/g, '$1 disabled readonly $2');
			html = html.replace(/(<select)([^>]*)/g, '$1 disabled readonly $2');
			subdiv.innerHTML += html;
			field_list_MSI[name] = subdiv;
			
			
			subdiv.onmouseover = function(){swap_text(this.innerHTML);}
			subdiv.onmouseout = function(){swap_text('&nbsp;');}
			div.appendChild(subdiv);
			cell.appendChild(div);
			field_count_MSI++;
			
			return name;
		
		}
		function remove_field_row_from_table(field, table)
		{
				var table = document.getElementById(table);
				var rows = table.rows;
				for(i = 0 ; i < rows.length; i++){
					cells = rows[i].cells;
					for(j = 0; j < cells.length; j++){
						cell = rows[i].cells[j];
						children = cell.childNodes;
						for(k = 0; k < children.length; k++){
							child = children[k];
							if(child.nodeType == 1){
								
								if(child.getAttribute('id') == 'slot_' + field){
									table.deleteRow(i);
									return;
								}
							}
						}
					}
				}
		}
		


		function move_deleted_fields_to_form(form_name)
		{		var form = document.getElementById(form_name);
				var table = document.getElementById('field_table_MSI');
				var rows = table.rows;
				for(i = 0 ; i < rows.length; i++){
					cells = rows[i].cells;
					for(j = 0; j < cells.length; j++){
						cell = rows[i].cells[j];
						children = cell.childNodes;
						for(k = 0; k < children.length; k++){
							child = children[k];
							if(child.nodeType == 1){
								if(child.nodeName.toLowerCase() == 'div'){
									grandchildren = child.childNodes;
									for(l = 0; l < grandchildren.length; l++){
										grandchild = grandchildren[l];
										if(grandchild.nodeType == 1){
											if(grandchild.nodeName.toLowerCase() == 'div'){
												var textEl = document.createElement('input');
												textEl.setAttribute('type', 'hidden')
												textEl.setAttribute('name',  'delete_fields[]' );
												textEl.setAttribute('id', 'delete_fields[]'  );
												textEl.setAttribute('value', replaceAll(grandchild.innerHTML, font_slot, ''));
												form.appendChild(textEl);
												
												
											}
										}
									}
								}
							}
						}
					}
				}
			}
			
var module_name = '';
var file_type = '';
var slotCount = 0;

function setSlotCount(newCount){
	slotCount = newCount;
}

function setModuleName(newName){
	module_name = newName;	
}

function setFileType(newType){

	file_type = newType;	
	
}

function addFieldPOPUP()
{
	window.open("index.php?module=EditCustomFields&action=Popup&module_name="
		+ module_name +"&file_type=" + file_type + "&field_count="
		+ field_count_MSI,"test","width=600,height=250,resizable=1,scrollbars=1");
}

function editCustomFields()
{
	document.location = "index.php?module=EditCustomFields&action=index&module_select=" + module_name;
}

