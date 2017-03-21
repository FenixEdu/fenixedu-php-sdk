<?php
class Period extends FenixEduEntity {
    private $start;
    private $end;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->start = $this->parseDateTime($data->start);
        $this->end = $this->parseDateTime($data->end);
    }
    
    /** Returns the DateTime corresponding to the start of this Period.
     */
    public function getStart() {
        return $this->start;
    }
    
    /** Returns the DateTime corresponding to the end of this Period.
     */
    public function getEnd() {
        return $this->end;
    }
}