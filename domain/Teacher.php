<?php
require_once("FenixEduEntity.php");

class Teacher extends FenixEduEntity {
    private $data;
    
    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the Teacher's name.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the Teacher's IST ID.
     */
    public function getIstId() {
        return $this->data->istId;
    }
    
    /** Returns an array with the Teacher's e-mail addresses.
     */
    public function getEmails() {
        return $this->data->mails;
    }
    
    /** Returns an array with the Teacher's URLs.
     */
    public function getUrls() {
        return $this->data->urls;
    }
}
