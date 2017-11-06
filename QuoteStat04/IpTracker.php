<?php

class IpTracker {

    var $ip = "0.0.0.0";
    var $time = 0;

    function __construct() {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $this->ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ips = array_map('trim', $ips);
            $this->ip = $ips[0];
        } else {
            $this->ip = $_SERVER['REMOTE_ADDR'];// ? '0.0.0.0';
        }

        $this->ip = filter_var($this->ip, FILTER_VALIDATE_IP);
        $this->ip = ($this->ip === false) ? '0.0.0.0' : $this->ip;
        $this->time = date(time()); // $_SERVER['REQUEST_TIME'];
    }
    
    function isLocal() {
        return ($this->ip === "127.0.0.1");
    }

}
