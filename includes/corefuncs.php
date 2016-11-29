<?php
function nukeMagicQuotes() {
  if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value) {
      $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
      return $value;
      }
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    }
  }
  
  
// array for states
$states_arr = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",  'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland", 'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma", 'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");

//array for months
$month_arr = array(1=>array('Jan','January'),2=>array('Feb','February'),3=>array('Mar','March'),4=>array('Apr','April'),5=>array('May','May'),6=>array('June','June'),7=>array('July','July'),8=>array('Aug','August'),9=>array('Sept','September'),10=>array('Oct','October'),11=>array('Nov','November'),12=>array('Dec','December'));

//array for days of the week

    $weekday_arr = array(0=>array('Sun','Sunday'),1=>array('Mon','Monday'),2=>array('Tues','Tuesday'),3=>array('Wed','Wednesday'),4=>array('Thurs','Thursday'),5=>array('Fri','Friday'),6=>array('Sat','Saturday'));


//functions for drop down

    function showOptionsDrop($array){
        $string = '';
        foreach($array as $k => $v){
            $string .= '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";
        }
         return $string;
    }
	
function dateMonth(){  

  
  $month = strtotime ('2011-01-01');
  $end = strtotime ('2012-01-01');
  while ($month < $end){
	  echo '<option value= " ' .date('F', $month). ' ">'.date('F',$month).'</option> '. "\n";
	  $month = strtotime("+1 month", $month);
  }
}

function dateDay() {
//Day  
  
for ($i = 1; $i <= 31; $i++) {  
echo '<option value=" '.$i. ' "> '.$i.' </option>' . "\n";
}  
  
}



function dateYear()
{
//Year  

for ($i=date("Y"); $i >= date('Y')-100; $i--) {
	echo '<option value=" '.$i. ' ">'.$i.'</option>' . "\n";
}

}  


function formatPhone($phone1)
{
//	return preg_replace('/\d{3}/','$0-',str_replace('.',null,trim($phone1)),2);
	//$phone1 = preg_replace("/[^0-9]/","",$phone1);
	
	if (strlen($phone1) == 7)
	return preg_replace("/([0-9]{3}) ([0-9]{4})/","$1-$2",$phone1);
	elseif (strlen($phone) == 10)
	return preg_replace("/([0-9]{3}) ([0-9]{4})/","($1) $2-$3",$phone1);
	else
	return $phone1;
}
?>
