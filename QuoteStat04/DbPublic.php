<?php

include_once "headers.php";

/**
 * The class for PUBLIC quote-review.
 *  The PUBLIC is limited to classifying 'stuff for their own IP address, only.
 */
class DbPublic {

    var $bpublic = true;
    var $db = NULL;

    function __construct() {
        
    }

    function open($isLocal) {
        if ($isLocal) {
            global $DBLOCAL;
            $this->db = new SQLite3($DBLOCAL);
        } else {
            global $DBFILE;
            $this->db = new SQLite3($DBFILE);
        }
    }

    function isPublic() {
        return $this->bpublic;
    }

    function track($nav, $quote) {
        if (is_a($nav, "CodeGbuNav") === false || is_a($quote, "QuoteGBU") === false) {
            return false;
        }

        if ($quote->QuoteNumber < 1) {
            return false;
        }

        if ($nav->movement != "OMIT" && $nav->movement != "KEEP") {
            return false;
        }

        $cmd = 'INSERT INTO DBTRACKER VALUES ( NULL, ' .
                '"' . $nav->ip . '", ' .
                '"' . $nav->time . '", ' .
                $quote->QuoteNumber . ', ' .
                '"' . $nav->movement . '"' .
                ');';
        return $this->db->exec($cmd);
    }

    function _append_pages($quote) {
        $cmd = 'SELECT * FROM DBPAGE WHERE QUOTE_ID = ' . $quote->QuoteNumber . ';';
        $results = $this->db->query($cmd);
        $quote->Quote = $quote->Quote . '<hr>Pages:<br/>';
        if ($results == false) {
            return;
        }
        while ($row = $results->fetchArray()) {
            $quote->Quote = $quote->Quote . '&nbsp;&nbsp;&nbsp;---' . $row['Page'] . '<br/>';
        }
    }

    function read($pkey, $quote) {
        HtmlDebug("<hr/>read(" . $pkey . "," . $quote->QuoteNumber . ")<hr/>");
        if (is_a($quote, "QuoteGBU") === false) {
            return false; // LEGACY
        }
        if ($pkey < 0) {
            return $this->readRandom($pkey, $quote);
        }
        $qs = new QuoteStatus();
        $results = $this->db->query('SELECT * FROM DBQUOTE WHERE ID = ' . $pkey . ' LIMIT 1;');
        $row = $results->fetchArray();
        if ($row != false) {
            $quote->QuoteNumber = $row["ID"];
            $quote->Quote = $row["Quote"];
            $quote->QuoteGBU = $qs->Encode($row["QuoteStatus"]);
            $this->_append_pages($quote);
            return true; // LEGACY
        }
        return false; // LEGACY
    }

    function getRandomReadSQL($pkey) {
        // $cmd = 'SELECT * FROM DBQUOTE AS Q JOIN DBTRACKER AS T WHERE Q.ID != ' . $pkey . ' AND Q.QuoteStatus = 0 AND Q.ID <> T.QUOTE_ID LIMIT 100;';
        $cmd = 'SELECT Q.ID FROM DBQUOTE AS Q JOIN DBTRACKER AS T WHERE Q.ID != ' . $pkey . ' AND Q.QuoteStatus = 0 AND Q.ID <> T.QUOTE_ID LIMIT 100;';

        return $cmd;
    }

    /**
     * Chooses a random, unclassified quote from a sample population.
     * @param type $pkey The ID to EXCLUDE from the population.
     * @param type $quote Where to place the results.
     * @return boolean TRUE if all went well, else FALSE.
     */
    function readRandom($pkey, $quote) {
        HtmlDebug("<hr/>readRandom(" . $pkey . "," . $quote->QuoteNumber . ")<hr/>");
        if (is_a($quote, "QuoteGBU") === false) {
            return false;
        }
        if ($pkey < 1) {
            $pkey = 1;
        }
        $cmd = $this->getRandomReadSQL($pkey);
        $results = $this->db->query($cmd);
        $which = mt_rand(1, 100);
        $count = 0;
        while ($row = $results->fetchArray()) {
            $count = $count + 1;
            if ($count == $which) {
                $qnum = $row[0];
                return $this->read($qnum, $quote);
            }
        }
        return false;
    }

    function countTrackedChanges() {
        $cmd = 'SELECT count(*) FROM DBTRACKER;';
        $rows = $this->db->query($cmd);
        if ($row = $rows->fetchArray()) {
            return $row[0];
        }
        return 0;
    }

    /**
     * Sacroscaint: Avoid the temptation  to change / share this outside of the class!
     * @param type $nav
     * @param type $total
     */
    private function _fixup($nav, $total) {
        // param-in fixup
        if ($nav->logical < 1) {
            // first page
            $nav->logical = 1;
        }
        if ($nav->logical + $nav->page_size > $total) {
            // last page
            $nav->logical = abs($total - $nav->page_size);
        }
    }

    /**
     * Pagination operations relative to the $nav information.
     * 
     * @param type $nav GbuNavHistory
     * @return boolean False on error, else the array of rows found.
     */
    function readNextNavSet($nav) {
        if (is_a($nav, "CodeHistoryNav") === false) {
            return false;
        }
        $total = $this->countTrackedChanges();
        if ($total == 0) {
            return false;
        }
        if ($nav->page_size < 1) {
            // sanity
            $nav->page_size = 25;
        }

        $dirFirst = '';
        $dirLast = '';
        $frame = 0;
        $this->_fixup($nav, $total);
        if ($nav->direction == 1) {
            $dirFirst = '>=';
            $dirLast = '<=';
            $frame = $nav->logical + $nav->page_size;
        } else {
            $dirFirst = '<=';
            $dirLast = '>=';
            $frame = abs($nav->logical - $nav->page_size);
        }
        $this->_fixup($nav, $total);

        $cmd = 'SELECT * FROM DBTRACKER AS T JOIN DBQUOTE AS Q WHERE (T.QUOTE_ID = Q.ID' .
                ' AND T.ID ' . $dirFirst . ' ' . $nav->logical .
                ' AND T.ID ' . $dirLast . ' ' . $frame .
                ' ) ORDER BY T.ID LIMIT ' . $nav->page_size . ';';
        HtmlDebug($cmd);
        $rows = $this->db->query($cmd);
        $result = array();
        while ($row = $rows->fetchArray()) {
            array_push($result, $row);
        }
        return $result;
    }

    function readQuote($quote) {
        if ($this->read($quote->QuoteNumber, $quote) === false)
            return false; // LEGACY
        return $quote; // LEGACY
    }

}
