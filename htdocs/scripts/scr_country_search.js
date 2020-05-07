function check_country() {
	if (document.forms.gisvolunteer.cocode.value != "256") {
		//	length = null; selected = false;
		document.forms.gisvolunteer.zipcode.value = '';
		document.forms.gisvolunteer.place.value = '';
		document.forms.gisvolunteer.state.value = '';
		document.forms.gisvolunteer.latitude.value = '';
		document.forms.gisvolunteer.longitude.value = '';
		document.forms.gisvolunteer.zipcode.className = "read_only";
		document.forms.gisvolunteer.zipcode.disabled = true;
	} else {
		document.forms.gisvolunteer.zipcode.className = "";
		document.forms.gisvolunteer.zipcode.disabled = false;
	}
	//	refer to check_select.2a.html: check compatible browser
	optionTest = true;
	lgth = document.forms.gisvolunteer.searchbuffer.options.length - 1;
	document.forms.gisvolunteer.searchbuffer.options[lgth] = null;
	if (document.forms.gisvolunteer.searchbuffer.options[lgth]) optionTest = false;
	//	create array
	var store = new Array();
	store[256] = new Array(
		'50 miles','1',
		'100 miles','2',
		'200 miles','3',
		'Inside United States','4',
		'Outside United States','5'
	);
	store[1] = new Array(
		'Inside United States','4',
		'Outside United States','5'
	);
	//	load array
	if (!optionTest) return;
	var box = document.forms.gisvolunteer.cocode;
	var number = box.options[box.selectedIndex].value;
	if (box.options[box.selectedIndex].value != 256) {
		var number = 1;
	}
	if (!number) return;
	var list = store[number];
	var box2 = document.forms.gisvolunteer.searchbuffer;
	box2.options.length = 0;
	for (i=0;i<list.length;i+=2) {
		box2.options[i/2] = new Option(list[i],list[i+1]);
	}
}