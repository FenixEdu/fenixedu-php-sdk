<?php
require_once("FenixEduEntity.php");

class Space extends FenixEduEntity {
    private $space;
    private $id;
    
    public function __construct($fenixEdu, $id) {
        parent::__construct($fenixEdu);
        $this->space = $fenixEdu->get("spaces/".$id);
        $this->id = $id;
    }
}
?>