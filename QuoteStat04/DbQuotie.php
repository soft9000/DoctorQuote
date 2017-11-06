<?php

include_once 'headers.php';

class DbQuotie extends DbPublic {

    function __construct() {
        parent::__construct();
        $this->bpublic = false;
    }

    
    function getRandomReadSQL($pkey) {
        $cmd = 'SELECT ID FROM DBQUOTE WHERE ID != ' . $pkey . ' AND QuoteStatus = 0 LIMIT 100;';
        return $cmd;
    }
    
    function append($quote) {
        if (is_a($quote, "QuoteGBU") === false) {
            return false;
        }
        $qs = new QuoteStatus();
        $quote->QuoteNumber = crc32($quote->Quote);
        $cmd = 'INSERT INTO DBQUOTE VALUES ( ' .
                $quote->QuoteNumber . ", '" .
                $quote->Quote . "', " .
                $qs->Decode($quote->QuoteGBU) .
                ");";
        return $this->db->exec($cmd);
    }

    function updateStatus($id, $status) {
        $cmd = 'UPDATE DBQUOTE SET QuoteStatus = ' .
                $status .
                " WHERE ID = " . $id . ";";
        return $this->db->exec($cmd);
    }

    function update($quote) {
        if (is_a($quote, "QuoteGBU") === false || $quote->QuoteNumber < 0) {
            HtmlDebug("Db Update - TYPE / NUMBER ERROR!");
            return false;
        }
        // Official logic: Updated quotes DO NOT remove olde - preserves page associations!  
        $znum = crc32($quote->Quote);
        $qs = new QuoteStatus();
        if ($znum === $quote->QuoteNumber) {
            $cmd = 'UPDATE DBQUOTE SET Quote = "' .
                    $quote->Quote . "', QuoteStatus = " .
                    $qs->Decode($quote->QuoteGBU) .
                    " WHERE ID = " . $quote->QuoteNumber . " LIMIT 1;";
            return $this->db->exec($cmd);
        } else {
            $quote->QuoteNumber = $znum;
            return append($quote);
        }
    }

    function delete($quote) {
        if (is_a($quote, "QuoteGBU") == false || $quote->QuoteNumber < 0) {
            return false;
        }
        $quote->QuoteNumber = crc32($quote->Quote);
        $cmd = 'DELETE FROM DBQUOTE WHERE ID = ' . $quote->QuoteNumber . ");";
        return $this->db->exec($cmd);
    }

}

?>