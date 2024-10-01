<?php

namespace App\Http\Helpers;


class Utils {
    
    static function formatarnumero($numero) {
        if(isset($numero) && $numero != null) {
            return number_format($numero, 2, ',', '.');
        }        
    }

    static function formatanumerodb($numero) {
        if(isset($numero) && $numero != null) {
            return str_replace(',', '.', str_replace('.', '', $numero));
        }        
    }

    static function formatardata($data) {
        if(isset($data) && $data != null) {
            return date('d/m/Y', strtotime($data));
        }        
    }
}