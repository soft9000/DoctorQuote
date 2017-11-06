<?php

include_once 'headers.php';

class CodeGbuNav extends IpTracker {

    var $direction = 0; // -1 back, = 0 refresh, 1 = forward
    var $qnum = -1;     // quote primary key
    var $admin = "0";   // logical admin flag
    var $logical = 1;   // logical quote number
    var $filter = "undefined";
    var $movement = '';
    var $debug = false;

    function __construct() {
        parent::__construct();
        global $ZPUBLIC;
        $this->admin = $ZPUBLIC;
    }

    function isNull() {
        return ($this->qnum === -1);
    }

    function isDebug() {
        return $this->debug;
    }

    function isAdmin() {
        global $ZADMIN;
        return ($this->admin == $ZADMIN);
    }

    function readFrom_REQUEST() {
        // Do we have a NAV-FORM post?
        if (isset($_REQUEST["GbuNav"]) == true) {
            $tmp = $_REQUEST["GbuNav"];
            HtmlDebug("NAV GBU = [" . $tmp . "]");
            $this->filter = trim($tmp);
            if (isset($_REQUEST["logical"]) == true) {
                $tmp = $_REQUEST["logical"];
                HtmlDebug("NAV LOGICAL = " . $tmp);
                $this->logical = $tmp;
            }
            if (isset($_REQUEST["admin"]) == true) {
                $tmp = $_REQUEST["admin"];
                HtmlDebug("NAV ADMIN = " . $tmp);
                $this->admin = $tmp;
            } if (isset($_REQUEST["qnum"]) == true) {
                $tmp = $_REQUEST["qnum"];
                HtmlDebug("NAV QNUM = " . $tmp);
                $this->qnum = $tmp;
            } if (isset($_REQUEST["movement"]) == true) {
                $this->movement = $_REQUEST["movement"];
                HtmlDebug("NAV MOVEMENT = " . $this->movement);
            }
            return true;
        } else {
            HtmlDebug("NO NAV!");
        }
        return false;
    }

    function procNav() {
        switch ($this->direction === 1) {
            case 1:
                $this->logical += 1;
                return;
            case -1:
                $this->logical -= 1;
                return;
            case 0:
            default:
                return;
        }
    }

}
