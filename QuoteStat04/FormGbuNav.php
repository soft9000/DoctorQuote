<?php

include_once 'headers.php';

function ShowQuoteNav($form, $nav, $quote) {
    if ($nav->isDebug()) {
        echo "\n";
    }
    echo '<div style="color:0xffffff;">';
    echo '<form action = "' . $form . '" id = "formnav" method = "post">';
    echo '<input hidden name = "GbuNav" form = "formnav" >';
    echo "&nbsp;&nbsp;";
    echo '<input type = "submit" class="buttonmedium" name = "movement" value = "NEXT">';
    echo "&nbsp;&nbsp;";
    echo '<input type = "submit" class="buttonmedium" name = "movement" value = "KEEP">';
    echo "&nbsp;&nbsp;";
    echo '<input type = "submit" class="buttonmedium" name = "movement" value = "OMIT">';
    echo "&nbsp;&nbsp;";
    if ($nav->isDebug()) {
        echo "\n";
    }
    if ($nav->isAdmin() == false) {
        echo '<input class="buttonlike" name = "admin" value = "' . $nav->admin . '">';
    } else {
        echo '<input type = "hidden" name = "admin" value = "' . $nav->admin . '">';
    }
    // TODO: echo '<input type = "submit" name = "movement" value = "PREV">';
    echo '<input type = "hidden" name = "logical" value = "' . $nav->logical . '">';
    echo '<input type = "hidden" name = "qnum" value = "' . $quote->QuoteNumber . '">';
    echo '</form>';
    if ($nav->isDebug()) {
        echo "\n";
    }
    return true;
}

/*
  function ShowQuoteNavSel($form, $quote, $bUseCat) {
  echo '<form action="' . $form . '" id="formnav" method="post">';
  echo '<input type="submit" class="buttonmedium" name="movement" value="<<">';

  if ($bUseCat === false) {
  echo '<input hidden name="GbuNav" form="formnav" >';
  } else {
  echo '<input name="GbuNav" form="formnav" >';
  }

  if ($quote->QuoteGBU === 'best') {
  echo '    <option value="best" selected>Best</option>';
  } else {
  echo '    <option value="best">Best</option>';
  }
  if ($quote->QuoteGBU === 'good') {
  echo '    <option value="good" selected>Good</option>';
  } else {
  echo '    <option value="good">Good</option>';
  }
  if ($quote->QuoteGBU === 'bad') {
  echo '    <option value="bad" selected>Bad</option>';
  } else {
  echo '    <option value="bad">Bad</option>';
  }
  if ($quote->QuoteGBU === 'ugly') {
  echo '    <option value="ugly" selected>Ugly</option>';
  } else {
  echo '    <option value="ugly">Ugly</option>';
  }
  if ($quote->QuoteGBU === 'undefined') {
  echo '    <option value="undefined" selected>Undefined</option>';
  } else {
  echo '    <option value="undefined">Undefined</option>';
  }
  echo '</select>';

  echo '<input type="hidden" name="logical" value="' . $quote->QuoteNumber . '">';
  echo '<input type="submit" class="buttonmedium" name="movement" value=">>">';
  echo '</form>';
  return true;
  }
 */

function ShowHomeLink() {
    global $WEBROOT;
    echo "\n";
    echo '<table class="logo"><tr><td>';
    echo "\n";
    echo '<img src="http://www.TheQuoteForToday.com/TheQuoteForToday.gif">';
    echo '</td><td class="menu">';
    echo '<a href="' . $WEBROOT . '">[Home]</a>';
    echo "\n";
    echo '</td></tr></table>';
    echo "\n";
}

function ShowFormNav($form, $nav, $quote) {
    return ShowQuoteNav($form, $nav, $quote);
}

?>
