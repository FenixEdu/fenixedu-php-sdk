<?php
require_once("FenixEduEntity.php");

class Parking extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /* Returns the address of this parking space.
     */
    public function getAddress() {
        return $this->data->address;
    }
    
    /* Returns the campus of this parking space.
     */
    public function getCampus() {
        return $this->data->campus;
    }
    
    /* Returns the description of this parking space.
     */
    public function getDescription() {
        return $this->data->description;
    }
    
    /* Returns the number of free slots in this parking space.
     */
    public function getFreeSlots() {
        return $this->data->freeSlots;
    }
    
    /* Returns the coordinates of this parking space.
     */
    public function getCoordinates() {
        return $this->data->latlng;
    }
    
    /* Returns the name of this parking space.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /* Returns the total number of slots in this parking space.
     */
    public function getCapacity() {
        return $this->data->total;
    }
    
    /* Returns the DateTime this Parking space's information was last updated.
     */
    public function getLastUpdateTime() {
        return $this->parseDateTime($this->data->updated);
    }
    
    /* Returns the working hours of this parking space.
     */
    public function getWorkingHours() {
        return $this->data->workingHours;
    }
    
    /* Returns the location name of this parking space.
     */
    public function getLocation() {
        return $this->data->location;
    }
}
