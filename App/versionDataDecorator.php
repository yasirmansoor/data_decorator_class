<?php

/*

Provides functionality to access serialised data within version data field

Example usage:
$decorator = new dataDecorator($serialsied_data);

//get top level item (can be a string or array)
$decorator->get('user');

//set top level item (if string)
$decorator->set('new_data', 'new value);

//get item of a top level array item
$decorator->get('user')->get('address');

//set item of a top level array item
$decorator->get('user')->set('address', 'new value);

//set item (of ID 5) of sub array item that belongs within a top level item, where ID is
//referenced by $identifier_field
$decorator->get('orders', 5)->set('date', 'new value);

*/

namespace App;

class versionDataDecorator implements versionDataDecoratorInterface {

    private $values = array();


    /**
     * @param string $data serialised string of array
     */
    public function __construct($data = null) {

        if ($data) {
            $this->hydrate($data);
        }
    }

    function __destruct() {
        unset($values);
    }

    
    /**
     * Converts serialised string to an accessible array. Consequent sub arrays will be
     * converted into versionDataDecorator objects held in each array item
     *
     * @param string $data serialised string of array
     */
    public function hydrate($data) {

        //reset values first
        $this->values = array();

        $data = unserialize($data);
        foreach($data as $key => $value) {

            if (is_array($value)) {
                $this->set($key, new versionDataDecorator(serialize($value)));
            } else {
                $this->set($key, $value);
            }
        }
    }

    /**
     * converts current array to serialised string
     *
     * @return string serialised array as string
     */
    public function dehydrate() {

        $data = $this->convertToArray($this->values);
        return serialize($data);
    }


    /**
     * iterates through array and converts any objects to arrays
     *
     * @param array $data
     * @return array
     */
    private function convertToArray(array $data) {

        foreach($data as $key => $value) {

            if ($data[$key] instanceof versionDataDecorator) {
                $data[$key] = $data[$key]->toArray();

                foreach($data as &$item) {
                    if ($item instanceof versionDataDecorator && $item->toArray()) {
                        $item = $this->convertToArray($item->toArray());
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param string $key first level array key
     * @param string $id optional reference ID of sub array item of first level array item
     * @return mixed
     */
    public function get($key, $id = null) {

        if (isset($this->values[$key])) {

            if ($id && count($this->values[$key])) {
                $items =  $this->values[$key];
                foreach(current($items) as $sub_item) {
                    if ($sub_item->get($this->identifier_field) == $id) {
                        return $sub_item;
                    }
                }
            } else {
                return $this->values[$key];
            }

        } else {
            return null;
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    /**
     * @return array
     */
    public function toArray() {
        if ($this->values) {
            $data = $this->convertToArray($this->values);
            return $data;
        }
    }

    /**
     * returns all keys of array
     * @return array
     */
    public function keys() {
        return array_keys($this->values);
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->values);
    }

}