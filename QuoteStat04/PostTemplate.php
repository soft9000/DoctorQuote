<?php

include_once 'headers.php';


//print_r($_SERVER);
//print_r($_POST);
//print_r($_GET);
//print_r($_FILES);
//print_r($_REQUEST);


$data = new QuoteGBU_REQUEST();

if ($data->QuoteNumber === -1) {
    // CREATE
} else {
    // UPDATE
}

/*
  echo $data->Quote;
  echo "<br>";
  echo $data->QuoteGBU;
  echo "<br>";
  echo $data->QuoteNumber;
 */

echo '<div>';
global $COPYRIGHT;
echo $COPYRIGHT;
echo '</div>';

?>

