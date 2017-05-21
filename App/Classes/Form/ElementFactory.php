<?php

interface Factory {
    public function build($name, $args);
}
class ElementFactory implements Factory
{

    public function build($name)
    {

    }

    protected function getElementFromClassString()
    {

    }
}