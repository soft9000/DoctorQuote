<?php

include_once "headers.php";

/**
 * Description of QuoteDatabase
 *
 * @author profnagy
 */
class QuoteDatabase {

    public static function OpenDatabase($nav) {
        $db = '';
        if ($nav->isAdmin()) {
            return QuoteDatabase::OpenAdminDatabase();
        } else {
            return QuoteDatabase::OpenPublicDatabase();
        }
    }

    public static function OpenTestDatabase() {
        $db = new DbQuotie();
        $db->open(true);
        return $db;
    }
    
    public static function OpenPublicDatabase() {
        $db = new DbPublic();
        $nav = new IpTracker();
        $db->open($nav->isLocal());
        return $db;
    }

    public static function OpenAdminDatabase() {
        $db = new DbQuotie();
        $nav = new IpTracker();
        $db->open($nav->isLocal());
        return $db;
    }

}
