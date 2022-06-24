<?php

namespace EndpointerFramework\Service;

abstract class BasicService
{
    private $endPoint;

    public function setEndpoint(

        $endPoint

    ) {

        $t = $this;

        $t->endPoint = $endPoint;

        return $t;
    }

    public function getEndpoint()
    {

        $t = $this;

        return $t->endPoint;
    }
}
