<?php
/* 
HealthCircle | Miramarmedicine.com
Refill Request Form 
Developer: Lee
Developer email: lee@thischick.com
(c) Copyright 2012 Miramarmedicine.com
*/

// Remove unwanted backslashes from the form
include('includes/corefuncs.php');
if (function_exists('nukeMagicQuotes')) {
  nukeMagicQuotes();
  }

  
//process the email 
if (array_key_exists('send',$_POST)){
	$to = 'refills@miramarmedicine.com'; // this is a test email, email is refills@miramarmedicine.com
	$subject = 'HealthCircle Rx Refill Form';
	
//list expected fields 
$expected = array ('lname', 'fname', 'minitial','month','day','year','suffix','address1','address2','city','state','zip','email','phone1','phone2','medication','strength','pillcount','directions','pharmacy','pharmphone','pharmfax','comments','strengthbox');

//set required fields

$required = array ('lname','fname','address1','city','state','email','zip','phone1','medication','strength','pillcount','directions','pharmacy','pharmphone','strengthbox');

// array for any missing fields

$missing = array ();

//assume that there is nothing suspect
$suspect = false;

//create a pattern to locate suspect phrases

$pattern = '/Content-Type:|Bcc:|Cc:/i';


//function to check for suspect phrases

function isSuspect($val,$pattern, &$suspect){
	//if the variable is an array, loop through each elements
	//and pass it recursively back to the same function
	if (is_array($val)){
		foreach ($val as $item) {
			isSuspect($item,$pattern,$suspect);
		}
	}
else {
	//if one of the suspect phrases is found, set Boolean to true<br />
if (preg_match($pattern, $val)) {
	$suspect = true;
}
}
}

if ($suspect) {
	$mailSent = false;
	unset($missing);
} 

else {
	
//prcoess the $_POST variables

foreach ($_POST as $key => $value) {

// assign temporary variable and remove whitespace if not an array 
$temp = is_array($value) ? $value : trim ($value);

// if empty and required, add to $missing array

if (empty($temp) && in_array($key, $required)){
array_push ($missing, $key);

}
// otherwise assign to a variable of the same name key
elseif (in_array($key, $expected)){
	${$key}= $temp;
}
}
}

// validate the email address 

if (empty($email)){
	//regex to ensure no illegal characters in email
	$checkEmail = '/^[^@]+@[^\s\r\n\'";,@%]+$/';
	
	//reject the email if it does not match
	if (!preg_match($checkEmail,$email)){
		array_push ($missing,'email');
	}
}


//start building the message if all required fields are okay

if (!$suspect && empty($missing)){

//set default value for variables that may not exist

$strength = isset ($strength) ? $strength : 'MG';
$minitial = isset ($minitial) ? $minitial : 'NA';

//build the email message

$message = "Last Name: $lname \n\n";
$message .= "First Name: $fname M.I: $minitial \n\n";
$message .= "Date of Birth: $month \\ $day \\ $year \n\n";
$message .= "Address: $address1 \n\n";
$message .= "Apt/Suite/Unit#: $address2 \n\n";
$message .= "State: $state \n\n";
$message .= "Zip: $zip \n\n";
$message .= "Email: $email \n\n";
$message .= "Daytime Phone: $phone1 \n\n";
$message .= "Evening Phone: $phone2 \n\n";
$message .= "Name of Medication: $medication \n\n";
$message .= "Strength: $strengthbox $strength \n\n";
$message .= "Pillcount: $pillcount \n\n";
$message .= "Directions: $directions \n\n";
$message .= "Pharmacy: $pharmacy \n\n";
$message .= "Pharmacy Phone: $pharmphone \n\n";
$message .= "Pharmacy Fax: $pharmfax \n\n";
$message .= "Comments: $comments \n\n";

//limit line length to 80

$message = wordwrap($message, 80);

$pillcount = wordwrap($message,3);

$zip = wordwrap($message,5);


//create additional headers

$additionalHeaders = "From: $lname $fname<refills@miramarmedicine.com> \r\n";
if (empty($email)){
	$additionalHeaders .= "\r\nReply-To: $email";
}

//send mail

$mailSent = mail($to, $subject, $message, $additionalHeaders);

if ($mailSent){
unset($missing);
}
}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MiramarMedicine.com Rx Refill Request Form<?php if (isset($title)) {echo "&#8212;{$title}";} ?></title>
<style type="text/css">
@import "style.css";
</style>
<link rel="shortcut icon" type="image/ico" href="favicon.ico" />

<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.maskedinput.js" type="text/javascript"></script>


<script type="text/javascript">

jQuery(function($){
  
   $("#phone1").mask("(999) 999-9999");
   $("#phone2").mask("(999) 999-9999");
   $("#pharmphone").mask("(999) 999-9999");
   $("#pharmfax").mask("(999) 999-9999");

});
</script>

</head>

<body>
<div id="container">
<div id="clipboard">
<div class="clipboardtop">
<div class="logo"><a href="http://miramarmedicine.com/" target="_blank" title="MiramarMedicine.com"><img src="images/logo.gif" /></a></div>
<div class="let_head">
<h2>Robert S. Tomchik M.D., P.A.</h2>
<p class="subtext">3161 Dykes Road, Miramar, FL 33027-4214 &nbsp;&nbsp; Phone: (954) 450-3550 &nbsp;&nbsp; Fax: (954) 450-3557</p>
<div class="intro"><h1>To request a prescription refill please fill out this form.</h1>
<p class="required">Fields marked with an asterisk (<abbr title="Required Field">*</abbr>) are required.</p>
</div>
</div>
<div class="leftclear"></div>
</div>
<div class="clipboard_content">
<p>




<?php

if ($_POST && isset($missing) && !empty($missing)){
	?>
    <div class="warningbold"><p>Sorry there was a problem submitting your Refill Request <?php echo $fname; ?>. Please complete the missing field(s) indicated below in red. </p></div>
    <?php 
}


// Check if mail was sent successfully, Boolean vaule goes here

elseif ($_POST && !$mailSent)
{
// display failure message
?>
<p class="warning">Sorry, there was a problem submitting your Refill Request Form. Please try again later</p>
<?php 	
}
elseif ($_POST && $mailSent) {
// display message that form was sent
?>

<p class="success">Thank you <?php echo $fname; ?> <?php echo $lname; ?> Your Refill Request Form to HealthCircle has been successfully sent. </p>

<?php }?>
<form id="refillrequest" method="post" action="">
<fieldset>



<?php 
##############################
# USERS CONTACT INFORMATION ##
##############################
?>

<div>
<label for="fname">First Name:* <?php 
if (isset($missing) && in_array ('fname',$missing)) { 
?>
<span class="warning">Please enter your first name</span><?php } ?>
</label>
<input name="fname" id="fname" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['fname']).'"';} ?>
                  />

</div>

<div>

<label for="minitial">Middle Initial:</label>
<input name="minitial" id="minitial" type="text" 

<?php if (isset($missing)){
	echo 'value="'.htmlentities($_POST['minitial']).'"';} ?>
     />

</div>

<div>
<label for="lname">Last Name:* <?php 
if (isset($missing) && in_array ('lname',$missing)) { ?>
<span class="warning">Please enter your last name</span><?php } ?>
</label>
<input name="lname" id="lname" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['lname']).'"';} ?>
                  />

</div>

<?php 
##############################
# ------- START DETAILS --- ##
##############################
?>

<fieldset id="dob-section" class="group">
<legend><span>Date of Birth</span></legend>
<div>
<label for="month">Month</label>
<select name="month" id="month">
<option value="January"
<?php 
   if (isset ($missing)){
	echo 'value=" '.htmlentities ($_POST['month']).'"';}?>
 >Month
 </option>
    <?php echo dateMonth($month); ?>

  </select> 
  </div>
  
  <div>
  <label for="day" id="day">Day</label>
<select name="day" id="day">
<option value="1"
<?php 
   if (isset ($missing)){
	echo 'value=" '.htmlentities ($_POST['day']).'"';}?>
 >Day
 </option>
    <?php echo dateDay(); ?>
</select>
</div>

<div>
<label for="year" id="year">Year</label>
<select name="year" id="year">
<option value="2012"
<?php 
   if (isset ($missing)){
	echo 'value=" '.htmlentities ($_POST['year']).'"';}?>
 >Year
 </option>
    <?php echo dateYear(); ?>
</select>
</div>
<div class="leftclear"></div>
</fieldset>

<div>
<label for="address1">Address:*<?php 
if (isset($missing) && in_array ('address1',$missing)) { ?>
<span class="warning">Please enter your address</span>
<?php } ?>
 </label>
<input name="address1" id="address1" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['address1']).'"';} ?>
                  />
</div>

<div>
<label for="address2">Apt/Suite/Unit#:</label>
<input name="address2" id="address2" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['address2']).'"';} ?>
                  />
</div>

<div>
<label for="city">City:*<?php 
if (isset($missing) && in_array ('city',$missing)) { ?>
<span class="warning">Please enter your city</span>
<?php } ?>
 </label>
<input name="city" id="city" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['city']).'"';} ?>
                  />
</div>


<div>
<label for="state">State:</label>
<div class="selectstyle">
<select name="state" id="state">
    <option value="FL"
    <?php
	if (isset ($missing)){
		echo 'value=" '.htmlentities ($_POST['state']).'"';} ?>
    >Florida
    </option>
    <?php echo showOptionsDrop($states_arr); ?>
  
</select>
</div>
</div>

<div>
<label for="city">Zip:*<?php 
if (isset($missing) && in_array ('city',$missing)) { ?>
<span class="warning">Please enter your zip code</span>
<?php } ?>
 </label>
<input name="zip" id="zip" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['zip']).'"';} ?>
                  />
</div>

<div>
<label for="email">E-mail:</label>
<input name="email" id="email" type="email" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['email']).'"';} ?>
                  />
</div>

<div>
<label for="phone1">Daytime Phone:*<?php 
if (isset($missing) && in_array ('phone1', $missing)) { ?>
<span class="warning">Please enter your daytime phone number</span>
<?php } ?>
</label>
<input name="phone1" id="phone1" type="text" 
<?php if (isset($missing)) {
				  echo formatPhone($phone1);} ?>
                  />
</div>

<div>
<label for="phone2">Evening Phone:</label>
<input name="phone2" id="phone2" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['phone2']).'"';} ?>
                  />
</div>


<div>
<label for="medication">Name of Medication:*<?php 
if (isset($missing) && in_array ('medication',$missing)) { ?>
<span class="warning">Please enter the name of the medication</span>
<?php } ?>
</label>
<input name="medication" id="medication" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['medication']).'"';} ?>
                  />
</div>
<div>
<fieldset id="strength">
<label for="strength">Strength:</label>

<input name="strengthbox" id="strengthbox" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['strengthbox']).'"';} ?>
                  />


<select name="strength" id="strength">
<option value="MG"
<?php
if (!$_POST || $_POST['strength'] == 'MG') {?>
selected="selected"
<?php } ?>
>MG</option>
<option value="MCG"
<?php
if (isset($missing) && $_POST['strength'] == 'MCG') {?>
selected="selected"
<?php }?>
>MCG</option>
</select>


<?php /*?><input name="strength" type="radio" value="MG" id="strength-mg"
<?php
$OK = isset ($_POST['strength']) ? true : false;
if ($OK && isset ($missing) && $_POST['strength'] == 'MG') { ?>
<?php } ?>
checked="checked" />
<label for="strength-mg" id="strength-mg"><span>mg</span></label>
<input name="strength" type="radio" value="MCG" id="strength-mcg"
<?php
 if ($OK && isset ($missing) && $_POST['strength'] == 'MCG') { ?>
 <?php } ?>
 />
<label for="strength-mcg" id="strength-mcg"><span>mcg</span></label>
<?php */?>
 </fieldset>
 </div>
<div class="clearfix"></div>
<div>
<label for="pillcount">Number of Pills Per Bottle:<?php 
if (isset($missing) && in_array ('pillcount',$missing)) { ?>
<span class="warning">Please select pill count</span>
<?php } ?>
</label>
<input name="pillcount" id="pillcount" type="number" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['pillcount']).'"';} ?>
                  />
</div>
<div class="clearfix"></div>
<div>
<label for="directions">Directions for Use:* <em>(please copy word-for-word from prescription bottle)<?php
if (isset($missing) && in_array ('directions',$missing)) { ?>
<span class="warning">Please enter the directions for use from the prescription bottle</span><?php } ?>
</em></label>
<textarea name="directions" id="directions"><?php
 if (isset($missing)) {
				  echo htmlentities($_POST['directions']);
				  } ?></textarea>
</div>

<div>
<label for="pharmacy">Name of Pharmacy:*<?php 
if (isset($missing) && in_array ('pharmacy',$missing)) { ?>
<span class="warning">Please enter the name of the pharmacy</span>
<?php } ?>
</label>
<input name="pharmacy" id="pharmacy" type="text" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['pharmacy']).'"';} ?>
                  />
</div>


<div>
<label for="pharmphone">Pharmacy Phone #:*<?php 
if (isset($missing) && in_array ('pharmphone',$missing)) { ?>
<span class="warning">Please enter the pharmacy phone #</span>
<?php } ?>
</label>
<input name="pharmphone" id="pharmphone" type="tel" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['pharmphone']).'"';} ?>
                  />
</div>

<div>
<label for="pharmfax">Pharmacy Fax #:</label>
<input name="pharmfax" id="pharmfax" type="tel" 
<?php if (isset($missing)) {
				  echo 'value="'.htmlentities($_POST['pharmfax']).'"';} ?>
                  />
</div>

<div>
<label for="comments">Comments:</label>
<textarea name="comments" id="comments"><?php
 if (isset($missing)) {
				  echo htmlentities($_POST['comments']);
				  } ?></textarea>
</div>
<input name="send" id="send" type="submit" value="Submit" />
<input name="clear" id="clear" type="reset" value="Clear" />
<div class="leftclear"></div>
</fieldset>
</form>



</p>

</div>
<div class="clipboard_footer"><?php include('includes/footer.inc.php'); ?></div>

</div>

</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-4227453-1");
pageTracker._trackPageview();
} catch(err) {}</script>

</body>
</html>