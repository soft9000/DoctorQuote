<?php

include_once 'headers.php';

/**
 * Check to see if a browser request has a quote!
 * @return type Returns true if has a quote, else false.
 */
function HasValidQuote() {
    $op = $_REQUEST["QuoteGBU"];
    return isset($op);
}

/**
 * Populate & save the results of a PRE-VALIDATED browser request.
 * @return type Returns the number of the newly created record, else FALSE!
 */
function SaveValidatedQuote() {
    HtmlDebug("000");
    $quote = new QuoteGBU();
    if ($quote->readFrom_REQUEST() === false) {
        HtmlEcho("Error - SaveValidatedQuote!!!");
        return false;
    }
    $db = QuoteDatabase::OpenAdminDatabase();
    HtmlDebug("101");
    $br = $db->append($quote);
    HtmlDebug("201");
    if ($br === false) {
        HtmlEcho("Creation Error - SaveValidatedQuote");
    } else {
        HtmlEcho("Created " . $quote->QuoteNumber);
    }
    return $logical;
}

/**
 * Show the blank quote-creation form.
 */
function ShowCreateQuote() {
    ShowFormCreate("FormCreateQuote.php");
}
?>

