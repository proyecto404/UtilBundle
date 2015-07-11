<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Class ArgumentException
 */
class ArgumentException extends \Exception
{
    private $argumentName;

    /**
     * @param string     $argumentName
     * @param string     $message
     * @param \Exception $innerException
     */
    public function __construct($argumentName, $message = '', \Exception $innerException = null)
    {
        $this->argumentName = $argumentName;
        parent::__construct($message, 0, $innerException);
    }

    /**
     * @return string
     */
    public function getArgumentName()
    {
        return $this->argumentName;
    }
}
