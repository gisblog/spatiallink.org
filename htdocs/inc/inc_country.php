<?php
/*	http://cia.gov/cia/publications/factbook/fields/2011.html
	http://www.zeesource.net/
	http://civicspacelabs.org/home/zipcodedb
	http://www.fcc.gov/fcc-bin/convertDMS
	translation between minutes and decimal @ http://groups.google.com/group/Google-Maps-API/
	=>
	function LatLong(lat,latm,lon,lonm) {
		lat = lat + latm/60;
		lon = lon + lonm/60;
		return new GPoint(lon,lat);
	}
	var point = LatLong(60,23, 5,20);
	
	if you've got degrees, minutes and seconds then divide the seconds by 3600 */
//	include script: $varpage may NOT contain www
if ($varstristrpage == 'spatiallink.org/gistools/volunteer/gisvolunteer_profile.php') {
	?><script src="/scripts/scr_country_profile.js"></script><?php
} elseif ($varstristrpage == 'spatiallink.org/gistools/volunteer/gisvolunteer_search.php') {
	?><script src="/scripts/scr_country_search.js"></script><?php
} else {
}
?>
<select name="cocode" size="1" onchange="check_country()">
	<option selected="selected"></option>
	<option value = "256">United States</option>
	<option value = "1">Afghanistan</option>
	<option value = "2">Akrotiri</option>
	<option value = "3">Albania</option>
	<option value = "4">Algeria</option>
	<option value = "5">American Samoa</option>
	<option value = "6">Andorra</option>
	<option value = "7">Angola</option>
	<option value = "8">Anguilla</option>
	<option value = "9">Antarctica</option>
	<option value = "10">Antigua / Barbuda</option>
	<option value = "11">Arctic Ocean</option>
	<option value = "12">Argentina</option>
	<option value = "13">Armenia</option>
	<option value = "14">Aruba</option>
	<option value = "15">Ashmore / Cartier Islands</option>
	<option value = "16">Atlantic Ocean</option>
	<option value = "17">Australia</option>
	<option value = "18">Austria</option>
	<option value = "19">Azerbaijan</option>
	<option value = "20">Bahamas</option>
	<option value = "21">Bahrain</option>
	<option value = "22">Baker Island</option>
	<option value = "23">Bangladesh</option>
	<option value = "24">Barbados</option>
	<option value = "25">Bassas da India</option>
	<option value = "26">Belarus</option>
	<option value = "27">Belgium</option>
	<option value = "28">Belize</option>
	<option value = "29">Benin</option>
	<option value = "30">Bermuda</option>
	<option value = "31">Bhutan</option>
	<option value = "32">Bolivia</option>
	<option value = "33">Bosnia / Herzegovina</option>
	<option value = "34">Botswana</option>
	<option value = "35">Bouvet Island</option>
	<option value = "36">Brazil</option>
	<option value = "37">British Indian Ocean Territory</option>
	<option value = "38">British Virgin Islands</option>
	<option value = "39">Brunei</option>
	<option value = "40">Bulgaria</option>
	<option value = "41">Burkina Faso</option>
	<option value = "42">Burma</option>
	<option value = "43">Burundi</option>
	<option value = "44">Cambodia</option>
	<option value = "45">Cameroon</option>
	<option value = "46">Canada</option>
	<option value = "47">Cape Verde</option>
	<option value = "48">Cayman Islands</option>
	<option value = "49">Central African Republic</option>
	<option value = "50">Chad</option>
	<option value = "51">Chile</option>
	<option value = "52">China</option>
	<option value = "53">Christmas Island</option>
	<option value = "54">Clipperton Island</option>
	<option value = "55">Cocos Islands</option>
	<option value = "56">Colombia</option>
	<option value = "57">Comoros</option>
	<option value = "58">Democratic Republic of Congo</option>
	<option value = "59">Republic of Congo</option>
	<option value = "60">Cook Islands</option>
	<option value = "61">Coral Sea Islands</option>
	<option value = "62">Costa Rica</option>
	<option value = "63">Cote dIvoire</option>
	<option value = "64">Croatia</option>
	<option value = "65">Cuba</option>
	<option value = "66">Cyprus</option>
	<option value = "67">Czech Republic</option>
	<option value = "68">Denmark</option>
	<option value = "69">Dhekelia</option>
	<option value = "70">Djibouti</option>
	<option value = "71">Dominica</option>
	<option value = "72">Dominican Republic</option>
	<option value = "73">East Timor</option>
	<option value = "74">Ecuador</option>
	<option value = "75">Egypt</option>
	<option value = "76">El Salvador</option>
	<option value = "77">Equatorial Guinea</option>
	<option value = "78">Eritrea</option>
	<option value = "79">Estonia</option>
	<option value = "80">Ethiopia</option>
	<option value = "81">Europa Island</option>
	<option value = "82">Falkland Islands</option>
	<option value = "83">Faroe Islands</option>
	<option value = "84">Fiji</option>
	<option value = "85">Finland</option>
	<option value = "86">France</option>
	<option value = "87">French Guiana</option>
	<option value = "88">French Polynesia</option>
	<option value = "89">French Southern / Antarctic Lands</option>
	<option value = "90">Gabon</option>
	<option value = "91">Gambia</option>
	<option value = "92">Gaza Strip</option>
	<option value = "93">Georgia</option>
	<option value = "94">Germany</option>
	<option value = "95">Ghana</option>
	<option value = "96">Gibraltar</option>
	<option value = "97">Glorioso Islands</option>
	<option value = "98">Greece</option>
	<option value = "99">Greenland</option>
	<option value = "100">Grenada</option>
	<option value = "101">Guadeloupe</option>
	<option value = "102">Guam</option>
	<option value = "103">Guatemala</option>
	<option value = "104">Guernsey</option>
	<option value = "105">Guinea</option>
	<option value = "106">Guinea-Bissau</option>
	<option value = "107">Guyana</option>
	<option value = "108">Haiti</option>
	<option value = "109">Heard Island / McDonald Islands</option>
	<option value = "110">Holy See</option>
	<option value = "111">Honduras</option>
	<option value = "112">Hong Kong</option>
	<option value = "113">Howland Island</option>
	<option value = "114">Hungary</option>
	<option value = "115">Iceland</option>
	<option value = "116">India</option>
	<option value = "117">Indian Ocean</option>
	<option value = "118">Indonesia</option>
	<option value = "119">Iran</option>
	<option value = "120">Iraq</option>
	<option value = "121">Ireland</option>
	<option value = "122">Israel</option>
	<option value = "123">Italy</option>
	<option value = "124">Jamaica</option>
	<option value = "125">Jan Mayen</option>
	<option value = "126">Japan</option>
	<option value = "127">Jarvis Island</option>
	<option value = "128">Jersey</option>
	<option value = "129">Johnston Atoll</option>
	<option value = "130">Jordan</option>
	<option value = "131">Juan de Nova Island</option>
	<option value = "132">Kazakhstan</option>
	<option value = "133">Kenya</option>
	<option value = "134">Kingman Reef</option>
	<option value = "135">Kiribati</option>
	<option value = "136">North Korea</option>
	<option value = "137">South Korea</option>
	<option value = "138">Kuwait</option>
	<option value = "139">Kyrgyzstan</option>
	<option value = "140">Laos</option>
	<option value = "141">Latvia</option>
	<option value = "142">Lebanon</option>
	<option value = "143">Lesotho</option>
	<option value = "144">Liberia</option>
	<option value = "145">Libya</option>
	<option value = "146">Liechtenstein</option>
	<option value = "147">Lithuania</option>
	<option value = "148">Luxembourg</option>
	<option value = "149">Macau</option>
	<option value = "150">Macedonia</option>
	<option value = "151">Madagascar</option>
	<option value = "152">Malawi</option>
	<option value = "153">Malaysia</option>
	<option value = "154">Maldives</option>
	<option value = "155">Mali</option>
	<option value = "156">Malta</option>
	<option value = "157">Isle of Man</option>
	<option value = "158">Marshall Islands</option>
	<option value = "159">Martinique</option>
	<option value = "160">Mauritania</option>
	<option value = "161">Mauritius</option>
	<option value = "162">Mayotte</option>
	<option value = "163">Mexico</option>
	<option value = "164">Federated States of Micronesia</option>
	<option value = "165">Midway Islands</option>
	<option value = "166">Moldova</option>
	<option value = "167">Monaco</option>
	<option value = "168">Mongolia</option>
	<option value = "169">Montserrat</option>
	<option value = "170">Morocco</option>
	<option value = "171">Mozambique</option>
	<option value = "172">Namibia</option>
	<option value = "173">Nauru</option>
	<option value = "174">Navassa Island</option>
	<option value = "175">Nepal</option>
	<option value = "176">Netherlands</option>
	<option value = "177">Netherlands Antilles</option>
	<option value = "178">New Caledonia</option>
	<option value = "179">New Zealand</option>
	<option value = "180">Nicaragua</option>
	<option value = "181">Niger</option>
	<option value = "182">Nigeria</option>
	<option value = "183">Niue</option>
	<option value = "184">Norfolk Island</option>
	<option value = "185">Northern Mariana Islands</option>
	<option value = "186">Norway</option>
	<option value = "187">Oman</option>
	<option value = "188">Pacific Ocean</option>
	<option value = "189">Pakistan</option>
	<option value = "190">Palau</option>
	<option value = "191">Palmyra Atoll</option>
	<option value = "192">Panama</option>
	<option value = "193">Papua New Guinea</option>
	<option value = "194">Paracel Islands</option>
	<option value = "195">Paraguay</option>
	<option value = "196">Peru</option>
	<option value = "197">Philippines</option>
	<option value = "198">Pitcairn Islands</option>
	<option value = "199">Poland</option>
	<option value = "200">Portugal</option>
	<option value = "201">Puerto Rico</option>
	<option value = "202">Qatar</option>
	<option value = "203">Reunion</option>
	<option value = "204">Romania</option>
	<option value = "205">Russia</option>
	<option value = "206">Rwanda</option>
	<option value = "207">Saint Helena</option>
	<option value = "208">Saint Kitts / Nevis</option>
	<option value = "209">Saint Lucia</option>
	<option value = "210">Saint Pierre / Miquelon</option>
	<option value = "211">Saint Vincent / Grenadines</option>
	<option value = "212">Samoa</option>
	<option value = "213">San Marino</option>
	<option value = "214">Sao Tome / Principe</option>
	<option value = "215">Saudi Arabia</option>
	<option value = "216">Senegal</option>
	<option value = "217">Serbia / Montenegro</option>
	<option value = "218">Seychelles</option>
	<option value = "219">Sierra Leone</option>
	<option value = "220">Singapore</option>
	<option value = "221">Slovakia</option>
	<option value = "222">Slovenia</option>
	<option value = "223">Solomon Islands</option>
	<option value = "224">Somalia</option>
	<option value = "225">South Africa</option>
	<option value = "226">South Georgia / Sandwich Islands</option>
	<option value = "227">Southern Ocean</option>
	<option value = "228">Spain</option>
	<option value = "229">Spratly Islands</option>
	<option value = "230">Sri Lanka</option>
	<option value = "231">Sudan</option>
	<option value = "232">Suriname</option>
	<option value = "233">Svalbard</option>
	<option value = "234">Swaziland</option>
	<option value = "235">Sweden</option>
	<option value = "236">Switzerland</option>
	<option value = "237">Syria</option>
	<option value = "238">Taiwan</option>
	<option value = "239">Tajikistan</option>
	<option value = "240">Tanzania</option>
	<option value = "241">Thailand</option>
	<option value = "242">Togo</option>
	<option value = "243">Tokelau</option>
	<option value = "244">Tonga</option>
	<option value = "245">Trinidad / Tobago</option>
	<option value = "246">Tromelin Island</option>
	<option value = "247">Tunisia</option>
	<option value = "248">Turkey</option>
	<option value = "249">Turkmenistan</option>
	<option value = "250">Turks / Caicos Islands</option>
	<option value = "251">Tuvalu</option>
	<option value = "252">Uganda</option>
	<option value = "253">Ukraine</option>
	<option value = "254">United Arab Emirates</option>
	<option value = "255">United Kingdom</option>
	<option value = "257">Uruguay</option>
	<option value = "258">Uzbekistan</option>
	<option value = "259">Vanuatu</option>
	<option value = "260">Venezuela</option>
	<option value = "261">Vietnam</option>
	<option value = "262">Virgin Islands</option>
	<option value = "263">Wake Island</option>
	<option value = "264">Wallis / Futuna</option>
	<option value = "265">West Bank</option>
	<option value = "266">Western Sahara</option>
	<option value = "267">Yemen</option>
	<option value = "268">Zambia</option>
	<option value = "269">Zimbabwe</option>
</select>