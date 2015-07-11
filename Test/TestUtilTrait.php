<?php

namespace Proyecto404\UtilBundle\Test;

trait TestUtilTrait
{
    protected function getMockWithoutConstructor($class)
    {
        return $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    protected function getMockWithMagicMethods($class, array $magicMethods)
    {
        return $this->getMock($class, array_merge(get_class_methods($class), $magicMethods));
    }
}
