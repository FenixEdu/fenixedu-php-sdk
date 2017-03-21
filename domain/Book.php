<?php
require_once("FenixEduEntity.php");

class Book extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the Book's author.
     */
    public function getAuthor() {
        return $this->data->author;
    }
    
    /** Returns the Book's publisher.
     */
    public function getPublisher() {
        return $this->data->reference;
    }
    
    /** Returns the Book's title.
     */
    public function getTitle() {
        return $this->data->title;
    }
    
    /** Returns the Book's year.
     */
    public function getYear() {
        return $this->data->year;
    }
    
    /** Returns the Book's relevance for the Course.
     */
    public function getRelevance() {
        return $this->data->type;
    }
    
    /** Returns the Book's URL.
     */
    public function getUrl() {
        return $this->data->url;
    }
}
