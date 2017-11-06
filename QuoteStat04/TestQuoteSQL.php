<?php

include_once 'headers.php';

// 109340
$quote = new QuoteGBU();
$db = QuoteDatabase::OpenTestDatabase();
$quote->QuoteNumber = 125855;
if ($db->readRandom($quote->QuoteNumber, $quote) === false) {
    HtmlDebug("Error");
} else {
    HtmlDebug("Okay");
}
return;
/*
  $foo = crc32("boo");
  print_r($foo);
  return;
 */

/*
  $quote = new QuoteGBU();
  $quote->Quote = "Please delete me, let me go!";
  global $DBFILE;
  $db = new SQLite3($DBFILE);
  $quote->QuoteNumber = crc32($quote->Quote);
  print_r("ID / HASH: " . $quote->QuoteNumber . "<br/>"); // ID is "2260077990"
  $cmd = 'INSERT INTO DBQUOTE VALUES ( ' .
  $quote->QuoteNumber . ", '" .
  $quote->Quote . "', " .
  QuoteStatus::Decode($quote->QuoteGBU) .
  ");";

  print_r("Insert: " . $db->exec($cmd) . "<hr>");

  $cmd = "DELETE FROM DBQUOTE WHERE ID = " . $quote->QuoteNumber . " LIMIT 1;";
  print_r("Delete: " . $db->exec($cmd) . "<hr>");

  exit();
 */

/*
 * global $DBFILE;
  $db = new SQLite3($DBFILE);
  $results = $db->query('SELECT * FROM DBQUOTE LIMIT 1;');
  while ($row = $results->fetchArray()) {
  //var_dump($row);
  print_r($row["ID"] . '<br/>');
  print_r($row["Quote"] . '<br/>');
  print_r($row["QuoteStatus"] . '<br/>');
  }
  $results = $db->query('SELECT * FROM DBQUOTE WHERE ID = -3 LIMIT 10;');
  if($results->numColumns() === 0) {
  print_r("Noda, really!");
  return;
  }
  $row = $results->fetchArray();
  if ($row === false)
  print_r("Nada!");
  else
  var_dump($row);
  return;
 */
HtmlEcho("STARTED " . print_r($quote));

$nav = new CodeGbuNav();
if ($nav->isNull() === false) {
    HtmlEcho("ERROR: Default NAV should be null!");
    exit();
}

$MESSAGE = "THIS TEST";

HtmlEcho("STEP 01");
$quote = new QuoteGBU();
if ($quote->isNull() === false) {
    HtmlEcho("ERROR: Default QUOTE should be null!");
    exit();
}

$quote->Quote = $MESSAGE;
$quote->QuoteGBU = "GBU_TEST";
HtmlEcho("STARTED " . print_r($quote));

HtmlEcho("STEP 02");
$db = QuoteDatabase::OpenAdminDatabase();

if ($db->append($quote) === false) {
    HtmlEcho("Error 101");
}

HtmlEcho("STEP 03 - PREP");
$logical = $quote->QuoteNumber;
$quote = new QuoteGBU();
$quote->QuoteNumber = $logical;

HtmlEcho("STEP 03");
$quote = $db->readQuote($quote);
if ($quote === false) {
    HtmlEcho("Error 201");
}

HtmlEcho("STEP 04");
if (strcmp($quote->Quote, $MESSAGE) !== 0) {
    HtmlEcho("Error 301" . print_r($quote));
}

HtmlEcho("Done.");
?>
