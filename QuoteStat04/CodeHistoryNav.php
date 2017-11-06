<?php

include_once 'headers.php';

class CodeHistoryNav extends CodeGbuNav {

    var $page_size = 25;

    function __construct() {
        parent::__construct();
        $this->qnum = 1;
        $this->direction = 1;
        $this->logical = 1;
    }

    function ShowNavBar($form) {
        echo '<div style="color:0xffffff;">';
        echo '<form action = "' . $form . '" id = "formnav" method = "post">';
        echo '<input hidden name = "GbuNav" form = "formnav" >';
        echo "&nbsp;&nbsp;";
        echo '<input type = "submit" class="buttonmedium" name = "movement" value = "PREV PAGE">';
        echo "&nbsp;&nbsp;";
        echo '<input type = "submit" class="buttonmedium" name = "movement" value = "NEXT PAGE">';

        echo "&nbsp;&nbsp;";
        if ($this->isDebug()) {
            echo "\n";
        }
        if ($this->isAdmin() == false) {
            echo '<input class="buttonlike" name = "admin" value = "' . $this->admin . '">';
        } else {
            echo '<input type = "hidden" name = "admin" value = "' . $this->admin . '">';
        }

        echo '<input type = "hidden" name = "logical" value = "' . $this->logical . '">';
        echo '<input type = "hidden" name = "direction" value = "' . $this->direction . '">';
        echo '<input type = "hidden" name = "filter" value = "' . $this->filter . '">';
        echo '<input type = "hidden" name = "qnum" value = "' . $this->qnum . '">';
        echo '</form>';
        echo '</div>';
    }

    function _ShowNavRow($row) {
        $stat = new QuoteStatus();
        $gb_class = $stat->Encode("" . $row['QuoteStatus']);
        $num = sprintf('%06d', $row[0]);
        $quote = str_replace('"', "'", $row['Quote']);
        if (strlen($quote) > 256) {
            $quote = substr($quote, 255) . " ...";
        }
        echo
        '<b class="smallprint"> ' . $num . ' </b>' .
        '<input class="' . $gb_class . '" name = "quotenum" value ="' . $gb_class .
        '"> <input class="quoteprint" name = "quote" value ="' . $quote .
        '"> ' .
        //'<form action="vote.php" id="' . $row[id] . 'zvote" method = "post">' .
        //'<input type="submit" name = "good" value = "Bad">' .
        //'<input type="submit" name = "bad" value = "Good">' .
        //'</form>' .
        '<b class="smallprint">#' . $row['QUOTE_ID'] . '</b>' .
        '<br/>';
    }

    function ShowNavRows() {
        $db = QuoteDatabase::OpenPublicDatabase();

        $rows = $db->readNextNavSet($this);
        $size = count($rows);
        HtmlEcho("Displaying " . $size);
        echo '<div class="box" width="790px"><br/>';
        for ($ss = 0; $ss < $size; ++$ss) {
            $this->_ShowNavRow($rows[$ss]);
        }
        echo '</div>';
    }

    function Status() {
        HtmlDebug("Direction: " . $this->direction);
        HtmlDebug("Logical: " . $this->logical);
        HtmlDebug("Quote ID: " . $this->qnum);
    }

}

function ShowHistoryReview() {
    $form = new CodeHistoryNav();
    $form->readFrom_REQUEST();
    $form->Status();
    switch ($form->movement) {
        case 'NEXT PAGE':
            $form->direction = 1;
            $form->logical = ($form->logical + $form->page_size);
            $db = QuoteDatabase::OpenPublicDatabase();
            $total = $db->countTrackedChanges();
            if ($form->logical > $total)
                $form->logical = $total;
            break;
        case 'PREV PAGE':
            $form->direction = -1;
            $form->logical = ($form->logical - $form->page_size);
            if ($form->logical < $form->page_size)
                $form->logical = $form->page_size;
            break;
    }

    ShowHomeLink();

    $form->Status();
    $form->ShowNavBar("FormHistoryNav.php");
    echo '<table><tr><td class="notebox" width="800px">';
    $form->ShowNavRows();
    echo '</td><td>';
    // STEP: Show 'Da Rules
    $okay = ShowFormInfo();
    echo '</td></tr></table>';
}

?>
