<?php
require_once("FenixEduEntity.php");

class ContactSheet extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    
    /** Returns the name of the entity referred by this ContactSheet.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the fax number of this ContactSheet.
     */
    public function getFax() {
        return $this->data->fax;
    }
    
    /** Returns the phone number of this ContactSheet.
     */
    public function getPhone() {
        return $this->data->phone;
    }
    
    /** Returns the email address of this ContactSheet.
     */
    public function getEmail() {
        return $this->data->email;
    }
    
    /** Returns the address of this ContactSheet.
     */
    public function getAddress() {
        return $this->data->address;
    }
    
    /** Returns the postal code of this ContactSheet.
     */
    public function getPostalCode() {
        return $this->data->postalCode;
    }
    
    /** Returns the working hours of this ContactSheet.
     */
    public function getWorkingHours() {
        return $this->data->workingHours;
    }
}
