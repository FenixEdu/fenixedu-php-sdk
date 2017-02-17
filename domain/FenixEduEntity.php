<?php
class FenixEduEntity {
    protected $fenixEdu;
    
    public function __construct($api) {
        $this->fenixEdu = $api;
    }
    
    protected function parseDateTime($period) {
        if($period === NULL) return NULL;
        $len = mb_strlen($period);
        if($len === FALSE) return NULL;
        if($len <= 10) {
            if($len > 5) return DateTime::createFromFormat("d/m/Y", $period);
            else return DateTime::createFromFormat("H:i", $period);
        } else return DateTime::createFromFormat("d/m/Y H:i", $period);
    }
}