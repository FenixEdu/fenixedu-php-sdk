<?php
require_once("FenixEduEntity.php");

class Student extends FenixEduEntity {
    private $data;
    
    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the IST ID of the Student.
     */
    public function getIstId() {
        return $this->data->username;
    }
    
    /** Returns the Degree of the Student.
     */
    public function getDegree() {
        require_once("Degree.php");
        return new Degree($this->fenixEdu, $this->fenixEdu->getDegree($this->data->degree->id));
    }
}
