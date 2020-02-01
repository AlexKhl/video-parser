<?php


namespace Artec3D;

use DOMDocument;
use DOMElement;


class Parser
{

    /**
     * Parser constructor.
     */
    public function __construct()
    {
    }

    // https://regexr.com/3anm9
    public function getYTLinks($url)
    {
        $page = file_get_contents($url);
//        $pattern = '/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/';
        $pattern = '/(?:youtube\.com\/\S*(?:(?:\/e(?:mbed))?\/|watch\?(?:\S*?&?v\=))|youtu\.be\/)([a-zA-Z0-9_-]{6,11})/';
        preg_match_all($pattern, $page, $matches);

        return $matches[1];
    }
}