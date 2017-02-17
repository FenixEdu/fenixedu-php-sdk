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
    
    /** Returns the starting DateTime of the Event.
     */
    public function getStart() {
        return $this->parseDateTime($this->data->period->start);
    }
    
    /** Returns the ending DateTime of the Event.
     */
    public function getEnd() {
        return $this->parseDateTime($this->data->period->end);
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
    
    /** Returns the Course the Event belongs to or NULL if it has none.
     */
    public function getCourse() {
        if(!property_exists($this->data, 'course')) return NULL;
        require_once("Course.php");
        return new Course($this->fenixEdu, $this->fenixEdu->getCourse($this->data->course->id));
    }
}