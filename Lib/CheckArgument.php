<?php

namespace Proyecto404\UtilBundle\Lib;

use ReflectionClass;

/**
 * Internal class to be called from Check::argument mehod.
 *
 * This class checks arguments implementing Design By Contract.}
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class CheckArgument
{
    private $argument;
    private $argumentName;

    /**
     * Constructor.
     *
     * @param string $argumentName Argument name to check
     * @param mixed  $argument     Argument value to check
     */
    public function __construct($argumentName, $argument)
    {
        $this->argument = $argument;
        $this->argumentName = $argumentName;
    }

    /**
     * Check that argument is not empty.
     *
     * @return CheckArgument
     *
     * @throws ArgumentException
     */
    public function isNotEmpty()
    {
        if ($this->argument === '' || $this->argument === null) {
            throw new ArgumentException($this->argumentName, $this->argumentName.' cannot be empty');
        }

        return $this;
    }

    /**
     * Checks that the argument is not out of length.
     *
     * @param int $maxLength Argument max length
     *
     * @return CheckArgument
     *
     * @throws ArgumentException
     */
    public function isNotOutOfLength($maxLength)
    {
        if (strlen($this->argument) > $maxLength) {
            throw new ArgumentException($this->argumentName, $this->argumentName.' cannot be more than '.$maxLength.' characters');
        }

        return $this;
    }

    /**
     * Checks that the argument is not null.
     *
     * @return CheckArgument
     *
     * @throws ArgumentNullException
     */
    public function isNotNull()
    {
        if ($this->argument === null) {
            throw new ArgumentNullException($this->argumentName);
        }

        return $this;
    }

    /**
     * Checks that the argument is not negative.
     *
     * @return CheckArgument
     *
     * @throws ArgumentOutOfRangeException
     */
    public function isNotNegative()
    {
        if ($this->argument < 0) {
            throw new ArgumentOutOfRangeException($this->argumentName);
        }

        return $this;
    }

    /**
     * Checks that the argument is not negative or zero.
     *
     * @return CheckArgument
     *
     * @throws ArgumentOutOfRangeException
     */
    public function isNotNegativeOrZero()
    {
        if ($this->argument <= 0) {
            throw new ArgumentOutOfRangeException($this->argumentName);
        }

        return $this;
    }

    /**
     * Checks that the argument is not out of range.
     *
     * @param int $min Min value
     * @param int $max Max value
     *
     * @return CheckArgument
     *
     * @throws ArgumentOutOfRangeException
     */
    public function isNotOutOfRange($min, $max)
    {
        if ($this->argument < $min || $this->argument > $max) {
            throw new ArgumentOutOfRangeException($this->argumentName);
        }

        return $this;
    }

    /**
     * Checks that the argument is numeric.
     *
     * @return CheckArgument
     *
     * @throws ArgumentException
     */
    public function isNumeric()
    {
        if (!is_numeric($this->argument)) {
            throw new ArgumentException($this->argumentName, $this->argumentName.' is not a numeric value');
        }

        return $this;
    }

    /**
     * Checks that the argument value is an instance of the provided enum class.
     *
     * @param string $enumClassName Enum class name to check
     *
     * @return CheckArgument
     *
     * @throws ArgumentException
     */
    public function isEnumValue($enumClassName)
    {
        $enumClass = new ReflectionClass($enumClassName);

        if (!$enumClass->isSubclassOf('\Proyecto404\UtilBundle\Lib\Enum')) {
            throw new \InvalidArgumentException($enumClassName.' must extend Enum');
        }

        $enumConstants = $enumClassName::getConstants();

        if (!in_array($this->argument, $enumConstants, true)) {
            throw new ArgumentException(
                $this->argumentName,
                $this->argumentName.' "'.$this->argument.'" is not a valid '.$enumClass->getName()
            );
        }

        return $this;
    }
}
