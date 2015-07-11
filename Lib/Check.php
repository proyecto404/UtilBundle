<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Class Check
 */
class Check
{
    private function __construct()
    {
    }

    /**
     * @param bool       $assertion
     * @param string     $message
     * @param \Exception $innerException
     *
     * @throws PreconditionException
     */
    public static function requires($assertion, $message = '', \Exception $innerException = null)
    {
        if (!$assertion) {
            throw new PreconditionException($message, 0, $innerException);
        }
    }

    /**
     * @param bool       $assertion
     * @param string     $message
     * @param \Exception $innerException
     *
     * @throws PostconditionException
     */
    public static function ensures($assertion, $message = '', \Exception $innerException = null)
    {
        if (!$assertion) {
            throw new PostconditionException($message, 0, $innerException);
        }
    }

    /**
     * @param bool       $assertion
     * @param string     $message
     * @param \Exception $innerException
     *
     * @throws InvariantException
     */
    public static function invariant($assertion, $message = '', \Exception $innerException = null)
    {
        if (!$assertion) {
            throw new InvariantException($message, 0, $innerException);
        }
    }

    /**
     * @param bool       $assertion
     * @param string     $message
     * @param \Exception $innerException
     *
     * @throws AssertException
     */
    public static function assert($assertion, $message = '', \Exception $innerException = null)
    {
        if (!$assertion) {
            throw new AssertException($message, 0, $innerException);
        }
    }

    /**
     * @param string $argumentName
     * @param mixed  $argument
     *
     * @return CheckArgument
     */
    public static function argument($argumentName, $argument)
    {
        return new CheckArgument($argumentName, $argument);
    }
}
