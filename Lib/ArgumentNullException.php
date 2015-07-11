<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Class ArgumentNullException
 */
class ArgumentNullException extends ArgumentException
{
    /**
     * @param string     $argumentName
     * @param string     $message
     * @param \Exception $innerException
     */
    public function __construct($argumentName, $message = '', \Exception $innerException = null)
    {
        if ($message == '') {
            $message = $argumentName.' cannot be null';
        }

        parent::__construct($argumentName, $message, $innerException);
    }
}
