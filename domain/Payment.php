<?php
require_once("FenixEduEntity.php");

class Payment extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the id of this Payment.
     */
    public function getId() {
        return $this->data->id;
    }
    
    /** Returns the amount of this Payment.
     */
    public function getAmount() {
        return $this->data->amount;
    }
    
    /** Returns the description of this Payment.
     */
    public function getDescription() {
        return $this->data->description;
    }
    
    /** Returns how this Payment was paid.
     */
    public function getType() {
        if(!property_exists($this->data, 'type')) return NULL;
        return $this->data->type;
    }
    
    /** Returns the DateTime representing the date when this Payment was paid.
     */
    public function getDate() {
        if(!property_exists($this->data, 'date')) return NULL;
        return $this->parseDateTime($this->data->date);
    }
    
    /** Returns this Payment's Period.
     */
    public function getStart() {
        if(!property_exists($this->data, 'paymentPeriod')) return NULL;
        require_once("Period.php");
        return new Period($this->data->paymentPeriod);
    }
    
    /** Returns the entity responsible for this Payment.
     */
    public function getEntity() {
        if(!property_exists($this->data, 'entity')) return NULL;
        return $this->data->entity;
    }
    
    /** Returns the banking reference for this Payment.
     */
    public function getReference() {
        if(!property_exists($this->data, 'reference')) return NULL;
        return $this->data->reference;
    }
}