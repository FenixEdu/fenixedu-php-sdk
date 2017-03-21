<?php
class CourseLoad extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }

    /** Returns the type of this CourseLoad.
     */
    public function getType() {
        return $this->data->type;
    }

    /** Returns the total quantity of this CourseLoad.
     */
    public function getTotalQuantity() {
        return $this->data->totalQuantity;
    }

    /** Returns the unit quantity of this CourseLoad.
     */
    public function getUnitQuantity() {
        return $this->data->unitQuantity;
    }
}
