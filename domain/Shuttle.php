<?php
require_once("FenixEduEntity.php");

class Shuttle extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /* Returns the shuttle's stations.
     */
    public function getStations() {
        $stations = array();
        foreach($this->data->stations as $station) $stations[] = new ShuttleStation($this->fenixEdu, $station);
        return $stations;
    }
    
    /* Returns the shuttle's periods as a key-value array with the type of
     * each period as the key and the Period as its value.
     */
    public function getPeriods() {
        require_once("Period.php");
        $periods = array();
        foreach($this->data->date as $period) $periods[$period->type] = new Period($this->fenixEdu, $period);
        return $periods;
    }
    
    /* Returns the shuttle's timetable as a key-value array with the type of
     * each period as the key and an array with the TimetableStops as its value.
     */
    public function getTimetable() {
        $trips = array();
        $stations = array();
        foreach($this->data->stations as $station) $stations[$station->name] = $station;
        foreach($this->data->trips as $trip) {
            $stops = array();
            foreach($trip->stations as $stop) {
                if(is_string($stop->station)) $stop->station = $stations[$stop->station];
                $stops[] = new TimetableStop($this->fenixEdu, $stop);
            }
            $trips[$trip->type] = $stops;
        }
        return $trips;
    }
}

class ShuttleStation extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }

    /* Returns the name of this station.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /* Returns the address of this station.
     */
    public function getAddress() {
        return $this->data->address;
    }
    
    /* Returns the coordinates of this station.
     */
    public function getCoordinates() {
        return $this->data->latlng;
    }
}

class TimetableStop extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /* Returns the DateTime of this TimetableStop.
     */
    public function getTime() {
        return $this->parseDateTime($this->data->hour);
    }
    
    /* Returns the Station of this TimetableStop.
     */
    public function getStation() {
        return new ShuttleStation($this->fenixEdu, $this->data->station);
    }
}
