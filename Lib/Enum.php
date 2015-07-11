<?php

namespace Proyecto404\UtilBundle\Lib;

use ReflectionClass;

/**
 * Class Enum
 */
abstract class Enum
{
    /**
     * @return array
     */
    public static function getConstants()
    {
        $reflect = new ReflectionClass(get_called_class());

        return $reflect->getConstants();
    }

    /**
     * @param string $name
     * @param bool   $strict
     *
     * @return bool
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));

        return in_array(strtolower($name), $keys);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public static function isValidValue($value)
    {
        $values = array_values(self::getConstants());

        return in_array($value, $values, true);
    }
}
