<?php


namespace Artec3D\destinations;

abstract class Destination
{
    private $name;

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

    abstract protected function setCredentials();

    abstract public function upload($file);

    abstract public function setStream();
}