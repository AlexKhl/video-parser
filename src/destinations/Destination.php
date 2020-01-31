<?php


namespace Artec3D\destinations;

abstract class Destination
{
    private $name;
    private $credentials = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}