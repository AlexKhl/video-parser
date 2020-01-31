<?php

namespace Artec3D;

use \Artec3D\Logs as Logs;
use \Artec3D\destinations\Destination as Destination;
use \Artec3D\destinations\Dailymotion as Dailymotion;

class DestinationPool
{
    /**
     * @var mixed[]
     */
    private $destinations;

    public function __construct()
    {
        $this->destinations = [];
    }

    // https://habr.com/ru/post/64840/
    // https://metabox.io/object-pool-pattern/
    public function getDestination($name)
    {
        if (empty($this->destinations) || !in_array($name, $this->destinations[])){
            $destination = $this->_create_instance($name);
            $this->destinations[$name] = $destination;
        }else{
            $this->destinations[$name];
            // \Artec3D\Logs::setMessage("The instance of the $name class is already existed");
        }
        return $this->destinations[$name];
    }

    private function _create_instance($name) {
        $class_name = "\\Artec3D\\destinations\\".$name;
        if (class_exists($class_name)) {
            return $object = new $class_name($name);
        }else{
            \Artec3D\Logs::setMessage("Class $name does not exist in the system");exit(1);
        }
    }
}