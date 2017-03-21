<?php
require_once("FenixEduEntity.php");

class Group extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the Group's number.
     */
    public function getNumber() {
        return $this->data->groupNumber;
    }
    
    /** Returns the shift of this Group.
     */
    public function getShift() {
        return $this->data->shift;
    }
    
    /** Returns an array with the members of this Group.
     * Each member has the properties <i>name</i> and <i>username</i>.
     */
    public function getMembers() {
        return $this->data->members;
    }
    
    /** Returns the number of members in this Group.
     */
    public function getMemberCount() {
        return count($this->data->members);
    }
}