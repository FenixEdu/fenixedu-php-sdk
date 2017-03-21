<?php
require_once("FenixEduEntity.php");

class Degree extends FenixEduEntity {
    private $data;
    
    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    
    private function loadDataFromDegree() {
        if(!property_exists($this->data, 'url') || !property_exists($this->data, 'info')) {
            $this->data = $this->fenixEdu->getDegree($this->data->id);
        }
    }
    
    
    /** Returns the <i>id</i> of this Degree.
     */
    public function getId() {
        return $this->data->id;
    }
    
    /** Returns the name of this Degree.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /** Returns the acronym of this Degree.
     */
    public function getAcronym() {
        $this->loadDataFromDegree();
        return $this->data->acronym;
    }
    
    /** Returns an array with this Degree's available academic terms.
     */
    public function getAcademicTerms() {
        $this->loadDataFromDegree();
        return $this->data->academicTerms;
    }
    
    /** Returns this instance's academic term.
     */
    public function getAcademicTerm() {
        $this->loadDataFromDegree();
        return $this->data->academicTerm;
    }
    
    /** Returns the type of this Degree.
     */
    public function getType() {
        $this->loadDataFromDegree();
        return $this->data->type;
    }
    
    /** Returns the detailed type of this Degree.
     */
    public function getTypeName() {
        $this->loadDataFromDegree();
        return $this->data->typeName;
    }
    
    /** Returns the URL of this Degree.
     */
    public function getUrl() {
        $this->loadDataFromDegree();
        return $this->data->url;
    }
    
    /** Returns the campi where this Degree is executed.
     */
    public function getCampi() {
        $this->loadDataFromDegree();
        return $this->data->campus;
    }
    
    /** Returns the description of this Degree.
     */
    public function getDescription() {
        $this->loadDataFromDegree();
        return $this->data->info->description;
    }
    
    /** Returns the objectives of this Degree.
     */
    public function getObjectives() {
        $this->loadDataFromDegree();
        return $this->data->info->objectives;
    }
    
    /** Returns a description of the people this Degree is designed for.
     */
    public function getDesignedFor() {
        $this->loadDataFromDegree();
        return $this->data->info->designFor;
    }
    
    /** Returns the requisites for enrolling on this Degree.
     */
    public function getRequisites() {
        $this->loadDataFromDegree();
        return $this->data->info->requisites;
    }
    
    /** Returns the professional exits this Degree may bring.
     */
    public function getProfessionalExits() {
        $this->loadDataFromDegree();
        return $this->data->info->profissionalExits;
    }
    
    /** Returns the history of this Degree.
     */
    public function getHistory() {
        $this->loadDataFromDegree();
        return $this->data->info->history;
    }
    
    /** Returns the operation regime of this Degree.
     */
    public function getOperationRegime() {
        $this->loadDataFromDegree();
        return $this->data->info->operationRegime;
    }
    
    /** Returns the gratuity for this Degree.
     */
    public function getGratuity() {
        $this->loadDataFromDegree();
        return $this->data->info->gratuity;
    }
    
    /** Returns links relevant for this Degree.
     */
    public function getLinks() {
        $this->loadDataFromDegree();
        return $this->data->info->links;
    }
    
    /** Returns an array with this Degree's Teachers.
     */
    public function getTeachers() {
        require_once("Teacher.php");
        $this->loadDataFromDegree();
        $teachers = array();
        foreach ($this->data->teachers as $teacher) $teachers[] = new Teacher($this->fenixEdu, $teacher); 
        return $teachers;
    }
    
    /** Returns an array with the Courses of this Degree.
     * The academic term of the Courses is the same as the Degree's.
     */
    public function getCourses() {
        require_once("Course.php");
        $this->loadDataFromDegree();
        $courses = array();
        foreach($this->fenixEdu->getDegreeCourses($this->getId(), $this->academicTermYear($this->getAcademicTerm())) as $course) {
            $courses[] = new Course($this->fenixEdu, $course);
        }
        return $courses;
    }
}
