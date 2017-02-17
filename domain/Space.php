<?php
require_once("FenixEduEntity.php");

class Space extends FenixEduEntity {
    private $data;
    private $loadedDay;
    
    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
        $this->loadedDay = NULL;
    }
    
    private function loadSpace($day = NULL) {
        $this->data = $this->fenixEdu->getSpace($this->data->id, $day);
        $this->loadedDay = $day;
    }
    
    /** Returns the id of this Space.
     */
    public function getId() {
        return $this->data->id;
    }
    
    /** Returns the name of this Space.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the type of this Space.
     */
    public function getType() {
        return $this->data->type;
    }
    
    /** Returns the description of this Space.
     */
    public function getDescription() {
        if(!property_exists($this->data, 'description')) $this->loadSpace();
        if(!property_exists($this->data, 'description')) return NULL;
        return $this->data->description;
    }
    
    /** Returns the maximum capacity of this Space in normal circumstances.
     */
    public function getNormalCapacity() {
        if(!property_exists($this->data, 'capacity')) $this->loadSpace();
        if(!property_exists($this->data, 'capacity')) return NULL;
        return $this->data->capacity->normal;
    }
    
    /** Returns the maximum capacity of this Space for exams.
     */
    public function getExamCapacity() {
        if(!property_exists($this->data, 'capacity')) $this->loadSpace();
        if(!property_exists($this->data, 'capacity')) return NULL;
        return $this->data->capacity->exam;
    }
    
    /** Returns an array with the Events occurring in this Space.
     */
    public function getEvents($day = NULL) {
        if($this->loadedDay !== $day) $this->loadSpace($day);
        if(!property_exists($this->data, 'events')) return NULL;
        $events = array();
        foreach($this->data->events as $event) $events[] = new Event($this->fenixEdu, $event);
        return $events;
    }
    
    
    /** Returns the top level Space that contains this Space.
     */
    public function getTopLevelSpace() {
        if(!property_exists($this->data, 'topLevelSpace')) $this->loadSpace();
        if(!property_exists($this->data, 'topLevelSpace')) {
            if(!property_exists($this->data, 'parentSpace')) return NULL;
            $parent = new Space($this->fenixEdu, $this->data->parentSpace);
            return $parent->getTopLevelSpace();
        }
        return new Space($this->fenixEdu, $this->data->topLevelSpace);
    }
    
    /** Returns the Space that contains this Space.
     */
    public function getParentSpace() {
        if(!property_exists($this->data, 'parentSpace')) $this->loadSpace();
        if(!property_exists($this->data, 'parentSpace')) {
            if(!property_exists($this->data, 'topLevelSpace')) return NULL;
            return new Space($this->fenixEdu, $this->data->topLevelSpace);
        }
        return new Space($this->fenixEdu, $this->data->parentSpace);
    }
    
    /** Returns an array with the Spaces contained in this Space.
     */
    public function getSpaces() {
        if(!property_exists($this->data, 'containedSpaces')) $this->loadSpace();
        $spaces = array();
        foreach($this->data->containedSpaces as $space) $spaces[] = new Space($this->fenixEdu, $space);
        return $spaces;
    }
    
    /** Returns the image data for this Space's blueprint.
     * The format can be <i>"jpeg"</i> or <i>"dwg"</i>. Default is <i>"jpeg"</i>.
     */
    public function getBlueprint($format = "jpeg") {
        return $this->fenixEdu->getSpaceBlueprint($this->getId(), $format);
    }
}
