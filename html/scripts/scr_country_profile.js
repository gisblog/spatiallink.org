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
}