<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuoteStatus {

    function Encode($int) {
        switch ($int) {
            default:
            case 0:
                return "undefined";
            case 1:
                return "bad";
            case 2:
                return "ugly";
            case 3:
                return "good";
            case 4:
                return "best";
            case 5:
                return "best"; // TODO? My_Favorite?
        }
    }

    function Decode($string) {
        switch ($string) {
            default:
            case "undefined":
                return 0;

            case "bad":
                return 1;

            case "ugly":
                return 2;

            case "good":
                return 3;

            case "best":
                return 4; // TODO? My_Favotite?
        }
    }

}
