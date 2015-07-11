<?php

namespace Proyecto404\UtilBundle\Lib;

use ReflectionClass;

/**
 * Class CheckArgument
 */
class CheckArgument
{
    private $argument;
    private $argumentName;

    /**
     * @param string $argumentName
     * @param mixed  $argument
     */
    public function __construct($argumentName, $argument)
    {
        $this->argument = $argument;
        $this->argumentName = $argumentName;
    }

    /**
     * @return CheckArgument
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
     * @param int $maxLength
     *
     * @return CheckArgument
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
     * @return CheckArgument
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
     * @return CheckArgument
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
     * @return CheckArgument
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
     * @param int $min
     * @param int $max
     *
     * @return CheckArgument
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
     * @return CheckArgument
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
     * @param string $enumClassName
     *
     * @return CheckArgument
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
