<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Class ArgumentOutOfRangeException
 */
class ArgumentOutOfRangeException extends ArgumentException
{
    /**
     * @param string     $argumentName
     * @param string     $message
     * @param \Exception $innerException
     */
    public function __construct($argumentName, $message = '', \Exception $innerException = null)
    {
        if ($message == '') {
            $message = $argumentName.' is out of range';
        }

        parent::__construct($argumentName, $message, $innerException);
    }
}
