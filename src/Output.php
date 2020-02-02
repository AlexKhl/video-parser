<?php

namespace Artec3D;

class Output
{

    public function stdOutput($result)
    {
        fwrite(STDOUT, $result);
    }
}