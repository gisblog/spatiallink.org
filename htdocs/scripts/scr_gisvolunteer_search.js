function checkgisvolunteer_search() {
    document.forms.gisvolunteer.javascript.value = 'enabled';
    if (document.forms.gisvolunteer.firstname.value.length <= 1) {
        alert('Please Complete Your First Name');
        document.forms.gisvolunteer.firstname.focus();
        document.forms.gisvolunteer.firstname.style.background = "#FFFFFF";
        return false
    } else if (!checkstring(document.forms.gisvolunteer.firstname.value)) {
        alert('Please Correct Your First Name\nAcceptable Characters:: qwertyuiopasdfghjklzxcvbnm\nNo Blank Space Accepted');
        document.forms.gisvolunteer.firstname.focus();
        document.forms.gisvolunteer.firstname.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.lastname.value.length <= 1) {
        alert('Please Complete Your Last Name');
        document.forms.gisvolunteer.lastname.focus();
        document.forms.gisvolunteer.lastname.style.background = "#FFFFFF";
        return false
    } else if (!checkstring(document.forms.gisvolunteer.lastname.value)) {
        alert('Please Correct Your Last Name\nAcceptable Characters:: qwertyuiopasdfghjklzxcvbnm\nNo Blank Space Accepted');
        document.forms.gisvolunteer.lastname.focus();
        document.forms.gisvolunteer.lastname.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.email.value.length <= 5) {
        alert('Please Complete Your Email');
        document.forms.gisvolunteer.email.focus();
        document.forms.gisvolunteer.email.style.background = "#FFFFFF";
        return false
    } else if (!checkemail(document.forms.gisvolunteer.email.value)) {
        alert('Please Correct Your Email\nAcceptable Format:: username@site.com\nNo Blank Space Accepted');
        document.forms.gisvolunteer.email.focus();
        document.forms.gisvolunteer.email.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.jobtitle.value.length <= 2) {
        alert('Please Complete Your Job Title');
        document.forms.gisvolunteer.jobtitle.focus();
        document.forms.gisvolunteer.jobtitle.style.background = "#FFFFFF";
        return false
    } else if (!checkstringcomplex_a(document.forms.gisvolunteer.jobtitle.value)) {
        alert('Please Correct Your Job Title\nAcceptable Characters:: q wertyuiopasdfghjklzxcvbnm');
        document.forms.gisvolunteer.jobtitle.focus();
        document.forms.gisvolunteer.jobtitle.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.officename.value.length <= 2) {
        alert('Please Complete Your Office Name');
        document.forms.gisvolunteer.officename.focus();
        document.forms.gisvolunteer.officename.style.background = "#FFFFFF";
        return false
    } else if (!checkstringcomplex_a(document.forms.gisvolunteer.officename.value)) {
        alert('Please Correct Your Office Name\nAcceptable Characters:: q wertyuiopasdfghjklzxcvbnm');
        document.forms.gisvolunteer.officename.focus();
        document.forms.gisvolunteer.officename.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.cocode.value == '') {
        alert('Please Select Your Location');
        document.forms.gisvolunteer.cocode.focus();
        document.forms.gisvolunteer.cocode.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.cocode.value == "256" && document.forms.gisvolunteer.zipcode.value.length <= 4) {
        alert('Please Complete Your Zipcode');
        document.forms.gisvolunteer.zipcode.focus();
        document.forms.gisvolunteer.zipcode.style.background = "#FFFFFF";
        return false
    } else if (document.forms.gisvolunteer.searchbuffer.value == '') {
        alert('Please Reselect Your Location');
        document.forms.gisvolunteer.cocode.focus();
        document.forms.gisvolunteer.cocode.style.background = "#FFFFFF";
        document.forms.gisvolunteer.cocode.value = '';
        return false
    } else if (document.forms.gisvolunteer.searchkeywords.value.length <= 3) {
        alert('Please Complete Keywords');
        document.forms.gisvolunteer.searchkeywords.focus();
        document.forms.gisvolunteer.searchkeywords.style.background = "#FFFFFF";
        return false
    } else if (!checkstringcomplex_c(document.forms.gisvolunteer.searchkeywords.value)) {
        alert('Please Correct Keywords\nAcceptable Characters:: q \"*-+wertyuiopasdfghjklzxcvbnm');
        document.forms.gisvolunteer.searchkeywords.focus();
        document.forms.gisvolunteer.searchkeywords.style.background = "#FFFFFF";
        return false
    }
    return true
}
