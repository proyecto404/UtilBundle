<?php

namespace Proyecto404\UtilBundle\Lib;

use ReflectionClass;

/**
 * Base class for enum types.
 *
 * Usage:
 * <code>
 *     final class Genders extends Enum
 *     {
 *          const MALE   = 'male';
 *          const FEMALE = 'female';
 *     }
 * </code>
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
abstract class Enum
{
    /**
     * Return enum possible values.
     *
     * @return array
     */
    public static function getConstants()
    {
        $reflect = new ReflectionClass(get_called_class());

        return $reflect->getConstants();
    }

    /**
     * Checks if a name is valid instance for this enum.
     *
     * @param string $name   Name to check
     * @param bool   $strict Strict mode (case sensitive)
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
     * Checks if a value is part of the enum values.
     *
     * @param mixed $value Value to check
     *
     * @return bool
     */
    public static function isValidValue($value)
    {
        $values = array_values(self::getConstants());

        return in_array($value, $values, true);
    }
}
