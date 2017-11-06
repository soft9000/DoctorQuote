<?php

include_once 'headers.php';

class QuoteGBU {

    var $QuoteNumber = -1;
    var $QuoteGBU = "undefined";
    var $Quote = "undefined";

    function isNull() {
        return ($this->QuoteNumber === -1);
    }

    function keep($nav) {
        $db = QuoteDatabase::OpenDatabase($nav);
        $br = true;
        if ($db->isPublic() == false) {
            $br = $db->updateStatus($nav->qnum, 3); // "good"
        }
        if ($br) {
            return $db->track($nav, $this);
        }
        return $br;
    }

    function omit($nav) {
        $db = QuoteDatabase::OpenDatabase($nav);
        $br = true;
        if ($db->isPublic() == false) {
            $br = $db->updateStatus($nav->qnum, 1); // "bad"
        }
        if ($br) {
            return $db->track($nav, $this);
        }
        return $br;
    }

    function assign($quote) {
        if (is_a($quote, "QuoteGBU") === false)
            return false;
        $this->Quote = $quote->Quote;
        $this->QuoteGBU = $quote->QuoteGBU;
        $this->QuoteNumber = $quote->QuoteNumber;
        HtmlDebug("ASSIGN " . $this->Quote . " " . $quote->Quote . "!!!");
        return true;
    }

    function toArray() {
        $result = array();
        $result[0] = $this->QuoteGBU;
        $result[1] = $this->Quote;
        return $result;
    }

    function fromArray($array) {
        if (count($array) != 2) {
            return false;
        }
        $this->QuoteGBU = $array[0];
        $this->Quote = $array[1];
        return true;
    }

    function readFrom_REQUEST() {
        if (isset($_REQUEST['QuoteNumber']) === false) {
            HtmlDebug("Error 101 - readFrom_REQUEST");
            return false;
        }
        $tmp = $_REQUEST['QuoteNumber'];
        $this->QuoteNumber = trim($tmp);

        if (isset($_REQUEST['QuoteGBU']) === false) {
            HtmlDebug("Error 201 - readFrom_REQUEST");
            return false;
        }
        $tmp = $_REQUEST['QuoteGBU'];
        $this->QuoteGBU = trim($tmp);

        if (isset($_REQUEST['Quote']) === false) {
            HtmlDebug("Error 301 - readFrom_REQUEST");
            return false;
        }
        $tmp = $_REQUEST['Quote'];
        $this->Quote = trim($tmp);

        HtmlDebug("Success: readFrom_REQUEST - " . print_r($this));
        return true;
    }

    /**
     * Update THIS quote to the next in a RANDOM, unclassified, series.
     * @param type $logical The logical (0 based) record offset into the data file.
     * @return boolean Returns false on error, else updates ITSELF from the database.
     */
    public function readNextRandom($logical) {
        if ($logical < 1) {
            $logical = 1;
        }
        $db = QuoteDatabase::OpenPublicDatabase();
        if ($db->readRandom($logical, $this) === false) {
            HtmlDebug("readNextRandom, fail = " . $logical);
            return false;
        }
        HtmlDebug('<hr/>readNextRandom - Okay: #' . $this->QuoteNumber . ": " . $this->Quote . " GBU === " . $this->QuoteGBU . "<hr/>");
        return true;
    }

}

class QuoteGBU_REQUEST extends QuoteGBU {

    function __construct() {
        $this->readFrom_REQUEST();
    }

}

function ShowFormCreate($form) {
    ShowHomeLink();
    $quote = new QuoteGBU();
    echo '<form action="' . $form . '" id="formupdate" method="post">';
    echo '<table>';
    echo '    <tr><td><input type="submit" class="buttonmedium" value="Post Quote"><hr></td></tr>';
    echo '    <tr><td><input type="radio" name="QuoteGBU" value="best">BEST</td></tr>';
    echo '    <tr><td><input type="radio" name="QuoteGBU" value="good">GOOD</td></tr>';
    echo '    <tr><td><input type="radio" name="QuoteGBU" value="bad">BAD</td></tr>';
    echo '    <tr><td><input type="radio" name="QuoteGBU" value="ugly">UGLY</td></tr>';
    echo '    <tr><td><input type="radio" name="QuoteGBU" value="undefined" checked>UNDEFINED</td></tr>';
    echo '    <tr><td><textarea name="Quote" form="formupdate" rows="20" cols="50">' . $quote->Quote . '</textarea></td></tr>';
    echo '    <tr><td><b>Quote ID: <input type="text" class="qnum" name="QuoteNumber" value="' . $quote->QuoteNumber . '" readonly></b></td></tr>';
    echo '</table>';
    echo '</form>';
    return true;
}

function ShowFormInfo() {
    echo '<div class="notebox"><b>Guidelines:</b>';
    echo '<ol>';
    echo '    <li>Quotes should be short, insightfull sayings.</li>';
    echo '    <li>Selections should be fit for everyone to read.</li>';
    echo '    <li>Controversial thoughts &amp; opinions can make life interesting.</li>';
    echo '    <li>Assume the worst: Reject quotes shared in a foreigh language.</li>';
    echo '    <li>Simple majority-vote dictates ultimate citation status.</li>';
    echo '</ol>';
    echo '<b>Review Strategy:</b>';
    echo '<ul>';
    echo '    <li>This is a community effort.</li>';
    echo '    <li>Quotes are randomly selected.</li>';
    echo '    <li>Interface designed for highest possible review speed.</li>';
    echo '    <li><b>No Worry Policy:</b> <i>Errors will average out, over time!</i></li>';
    echo '</ul>';
    echo("<br/>");
    echo '<b>Results shared at <a href=https://sourceforge.net/projects/mightymaxims/>Might Maxims</a>.</b>';
    echo("<br/>");
    echo("<br/></div>");
    return true;
}

function ShowFormDisplay($form, $quote) {
    echo '<table class=quoteprint>';
    echo '    <tr><td><b>Quote ID: <input type="text" class="qnum" name="QuoteNumber" value="' . $quote->QuoteNumber . '" readonly></b></td></tr>';
    // echo '    <tr><td><textarea name="Quote" form="formupdate" rows="20" cols="50" readonly>' . $quote->Quote . '</textarea></td></tr>';
    echo '    <tr><td><div class=quotebox>' . $quote->Quote . '</div></td></tr>';
    echo '</table>';
    return true;
}

function ShowFormUpdate($form, $quote) {
    echo '<form action="' . $form . '" id="formupdate" method="post">';
    echo '<table>';
    echo '    <tr><td><input type="submit" value="Post Quote"><hr></td></tr>';
    if ($quote->QuoteGBU === 'best') {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="best" checked>BEST</td></tr>';
    } else {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="best">BEST</td></tr>';
    }
    if ($quote->QuoteGBU === 'good') {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="good" checked>GOOD</td></tr>';
    } else {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="good">GOOD</td></tr>';
    }
    if ($quote->QuoteGBU === 'bad') {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="bad" checked>BAD</td></tr>';
    } else {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="bad">BAD</td></tr>';
    }
    if ($quote->QuoteGBU === 'ugly') {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="ugly" checked>UGLY</td></tr>';
    } else {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="ugly">UGLY</td></tr>';
    }
    if ($quote->QuoteGBU === 'undefined') {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="undefined" checked>UNDEFINED</td></tr>';
    } else {
        echo '    <tr><td><input type="radio" name="QuoteGBU" value="undefined">UNDEFINED</td></tr>';
    }
    echo '    <tr><td><textarea name="Quote" form="formupdate" rows="20" cols="50">' . $quote->Quote . '</textarea></td></tr>';
    echo '    <tr><td><b>Quote ID: <input type="text" class="qnum" name="QuoteNumber" value="' . $quote->QuoteNumber . '" readonly></b></td></tr>';
    echo '</table>';
    echo '</form>';
    HtmlDebug("<" . $quote->Quote . ">");
    return true;
}

function ShowFormGBU($form, $logical) {
    $quote = new QuoteGBU();
    if ($quote->readNextRandom($logical) === false) {
        return false;
    }
    if ($quote->QuoteNumber === -1) {
        echo "Update " . "Error: Quote #" . $logical . " not found!";
        return false;
    }
    echo '<form action="' . $form . '" id="formGBU" method="post">';
    echo '<table>';
    echo '    <tr><td><input type="submit" class="buttonmedium" name="QuoteGBU" value="best"> </td></tr>';
    echo '    <tr><td><input type="submit" class="buttonmedium" name="QuoteGBU" value="good"> </td></tr>';
    echo '    <tr><td><input type="submit" class="buttonmedium" name="QuoteGBU" value="bad"> </td></tr>';
    echo '    <tr><td><input type="submit" class="buttonmedium" name="QuoteGBU" value="ugly"> </td></tr>';
    echo '    <tr><td><textarea name="Quote" form="formGBU" rows="20" cols="50">' . $quote->Quote . '</textarea></td></tr>';
    echo '    <tr><td><b>Quote ID: <input class="qnum" type="text" name="QuoteNumber" value="' . $quote->QuoteNumber . '" readonly></b></td></tr>';
    echo '</table>';
    echo '</form>';
    return true;
}

function ShowFormWelcome($form) {
    $style = 'class="buttonbig" '; // style="width:250px; height:50px;"';
    echo '<center>';
    echo '<form action="' . $form . '" id="formwelcome" method="post">';
    echo '<table>';
    //echo '    <tr><td><input type="submit" name="op" value="1. Create Quotations" ' . $style . '> </td></tr>';
    echo '    <tr><td><input type="submit" name="op" value="2. Review New Quotations" ' . $style . '> </td></tr>';
    echo '    <tr><td><input type="submit" name="op" value="3. Review Old Quotations" ' . $style . '> </td></tr>';
    echo '</table>';
    echo '</form>';
    echo '</center>';
    return true;
}

?>