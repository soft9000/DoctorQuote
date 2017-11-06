<?php

include_once 'headers.php';

function _GbuUpdate($nav, $quote) {
    $db = QuoteDatabase::OpenDatabase($nav);
    if ($db->isPublic()) {
        if ($db->update($quote) === true) {
            $nav->qnum = $quote->QuoteNumber;
            $nav->direction = 1; // Next!
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function ShowGbuReview() {
    $nav = new CodeGbuNav();
    $quote = new QuoteGBU();
    if ($nav->readFrom_REQUEST() === false) { // Got nav?
        // NO NAV - CHECK FOR QUOTE UPDATE
        if ($quote->readFrom_REQUEST() === true) { // Got quote?
            // QUOTE UPDATE ... PUBLIC CAN DO THIS, TOO!
            if (_GbuUpdate($nav, $quote) === true) {
                HtmlEcho("<hr>Quote " . $quote->QuoteNumber . " Updated<hr>");
            }
        } else {
            $nav->qnum = -1; // no data? Then make it null!
        }
    } else {
        // Got Nav Action - QNUM ONLY!
        $nav->procNav();
    }

    if ($nav->isNull() === true) {
        $nav->qnum = 1;
        $nav->direction = 0;
        echo "<b>Welcome Quotie!</b>";
    }

    // STEP: Restore the selection, IFF
    if ($nav->qnum > 1)
        $quote->QuoteNumber = $nav->qnum;

    // STEP: Process any QuoteStatus activity:
    switch ($nav->movement) {
        case 'KEEP':
            $quote->keep($nav);
            break;
        case 'OMIT':
            $quote->omit($nav);
            break;
        default:
        case 'NEXT':
            break;
    }

    // STEP: Locate the NEXT citation
    $db = QuoteDatabase::OpenDatabase($nav);

    HtmlDebug("<hr>Looking for Quote: " . $quote->QuoteNumber . "<hr>");
    if ($db->readRandom($quote->QuoteNumber, $quote) === false) {
        HtmlEcho("<hr>Error: Quote #" . $quote->QuoteNumber . " !<hr>");
        return;
    }
    $nav->qnum = $quote->QuoteNumber;

    echo "<table>";
    echo "<tr><td>";

    ShowHomeLink();

    echo '<table>';
    echo "<tr><td>"; // LEFT SIDE
    // STEP: Show the Nav:
    $okay = ShowFormNav("FormGbuReview.php", $nav, $quote);
    echo "</td></tr>";
    echo "<tr><td>";
    // STEP: Show the Quote:
    $okay = ShowFormDisplay("FormGbuReview.php", $quote);
    echo "</td></tr>";
    echo '</table>';

    echo '</td><td>'; // RIGHT SIDE
    // STEP: Show 'Da Rules
    $okay = ShowFormInfo();
    echo "</td></tr>";
    echo "</table>";

    global $COPYRIGHT;
    echo $COPYRIGHT;
}
?>


