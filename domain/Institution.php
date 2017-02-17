<?php
require_once("FenixEduEntity.php");

class Institution extends FenixEduEntity {
    private $info;
    
    public function __construct($fenixEdu) {
        parent::__construct($fenixEdu);
        $this->info = NULL;
    }
    
    
    //This block is for methods related with the getAbout endpoint
    
    /** Loads some basic information about the institution where the
     * application is deployed. It also returns a list of RSS feeds, the
     * current academic term, available languages and default language.
     */
    private function loadAbout() {
        if($this->info === NULL) $this->info = $this->fenixEdu->getAbout();
    }
    
    /** Returns the Institution's name.
     */
    public function getName() {
        $this->loadAbout();
        return $this->info->institutionName;
    }
    
    /** Returns the Institution's URL.
     */
    public function getUrl() {
        $this->loadAbout();
        return $this->info->institutionUrl;
    }
    
    /** Returns an array with the Institution's RSS feeds.
     * Each feed in the array has the properties <i>description</i> and <i>url</i>.
     */
    public function getRSSFeeds() {
        $this->loadAbout();
        return $this->info->rssFeeds;
    }
    
    /** Returns the specified RSS feed's URL.
     */
    public function getRSSFeed($name) {
        $this->loadAbout();
        return $this->info->rss[$name];
    }
    
    /** Returns the Institution's current academic term.
     */
    public function getCurrentAcademicTerm() {
        $this->loadAbout();
        return $this->info->currentAcademicTerm;
    }
    
    /** Returns the Institution's available languages.
     */
    public function getLanguages() {
        $this->loadAbout();
        return $this->info->languages;
    }
    
    /** Returns the Institution's default language.
     */
    public function getLanguage() {
        $this->loadAbout();
        return $this->info->language;
    }
    
    // End of getAbout block
    
    
    /** Returns an array with the Institution's academic terms available to be
     * used as the <i>academicTerm</i> parameter.
     */
    public function getAcademicTerms() {
        return $this->fenixEdu->getAcademicTerms();
    }
    
    /** Returns an array of ContactSheets with the Institution's contacts.
     */
    public function getContacts() {
        require_once("ContactSheet.php");
        $contacts = array();
        foreach($this->fenixEdu->getContacts() as $contact) $contacts[] = new ContactSheet($this->fenixEdu, $contact);
        return $contacts;
    }
    
    /** Returns the Course with the specified <i>id</i>.
     * The optional <i>academicTerm</i> parameter allows for restricting the
     * data to a specific academic term.
     */
    public function getCourse($id, $academicTerm = NULL) {
        require_once("Course.php");
        return new Course($this->fenixEdu, $this->fenixEdu->getCourse($id, $academicTerm));
    }

    /** Returns an array with the Degrees of this Institution.
     * The optional <i>academicTerm</i> parameter allows for restricting the
     * data to a specific academic term.
     */
    public function getDegrees($academicTerm = NULL) {
        require_once("Degree.php");
        $degrees = array();
        foreach($this->fenixEdu->getDegrees($academicTerm) as $degree) {
            $degrees[] = new Degree($this->fenixEdu, $degree);
        }
        return $degrees;
    }

    /** Returns the Degree with the specified <i>id</i>.
     * The optional <i>academicTerm</i> parameter allows for restricting the
     * data to a specific academic term.
     */
    public function getDegree($id, $academicTerm = NULL) {
        require_once("Degree.php");
        return new Degree($this->fenixEdu, $this->fenixEdu->getDegree($id, $academicTerm));
    }
    
    /** Returns data on the Shuttle service, including schedule and stations.
     */
    public function getShuttle() {
        //TODO Shuttle
    }
    
    /** Returns an array with this Institution's campi.
     */
    public function getSpaces() {
        require_once("Space.php");
        $spaces = array();
        foreach($this->fenixEdu->getSpaces() as $space) $spaces[] = new Space($this->fenixEdu, $space);
        return $spaces;
    }
}
