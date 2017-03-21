<?php
require_once("FenixEduEntity.php");

class CanteenMenu extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /** Returns the DateTime of this menu.
     */
    public function getDate() {
        return $this->parseDateTime($this->data->day);
    }
    
    /** Returns a key-value array with the meals of this menu as keys and
     * arrays with the dishes as the value for each key.
     */
    public function getMeals() {
        $meals = array();
        foreach($this->data->meals as $meal) {
            $dishes = array();
            foreach($meal->info as $dish) {
                $dish->meal = $meal->type;
                $dishes[] = new Dish($this->fenixEdu, $dish);
            }
            $meals[$meal->type] = $dishes;
        }
        return $meals;
    }
}

class Dish extends FenixEduEntity {
    private $data;

    public function __construct($fenixEdu, $data) {
        parent::__construct($fenixEdu);
        $this->data = $data;
    }
    
    /* Returns the name of the menu this dish belong to.
     */
    public function getMenu() {
        return $this->data->menu;
    }
    
    /* Returns the name this dish.
     */
    public function getName() {
        return $this->data->name;
    }
    
    /* Returns the type this dish.
     */
    public function getType() {
        return $this->data->type;
    }
    
    /* Returns the name of the meal this dish belong to.
     */
    public function getMeal() {
        return $this->data->meal;
    }
}