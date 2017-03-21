<?php
require_once("FenixEduEntity.php");

class Event extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the type of the Event.
     */
    public function getType() {
        return $this->data->type;
    }
    
    /** Returns the Period of the Event.
     */
    public function getPeriod() {
        require_once("Period.php");
        return new Period($this->fenixEdu, $this->data->period);
    }
    
    /** Returns the description of the Event or NULL if it has none.
     */
    public function getDescription() {
        if(!property_exists($this->data, 'description')) return NULL;
        return $this->data->description;
    }
    
    /** Returns the title of the Event or NULL if it has none.
     */
    public function getTitle() {
        if(!property_exists($this->data, 'title')) return NULL;
        return $this->data->title;
    }
    
    /** Returns information on the Event or NULL if it has none.
     */
    public function getInfo() {
        if(!property_exists($this->data, 'info')) return NULL;
        return $this->data->info;
    }
    
    /** Returns an array with the Spaces this Event will be in.
     */
    public function getLocations() {
        $locations = array();
        if(!property_exists($this->data, 'location')) return $locations;
        require_once("Space.php");
        foreach($this->location as $location) $locations[] = new Space($this->fenixEdu, $location);
        return $locations;
    }
    
    /** Returns the Course the Event belongs to or NULL if it has none.
     */
    public function getCourse() {
        if(!property_exists($this->data, 'course')) return NULL;
        require_once("Course.php");
        return new Course($this->fenixEdu, $this->fenixEdu->getCourse($this->data->course->id));
    }
}