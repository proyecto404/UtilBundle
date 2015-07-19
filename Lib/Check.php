<?php

namespace Proyecto404\UtilBundle\Lib;

/**
 * Implements Design by contract checks.
 *
 * @see https://en.wikipedia.org/wiki/Design_by_contract
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class Check
{
    /**
     * Constructor.
     */
    private function __construct()
    {
    }

    /**
     * Checks that a precondition is met.
     *
     * @param bool       $assertion      The assertion to check
     * @param string     $message        The precondition message
     * @param \Exception $innerException An inner exception
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
     * Checks that a postcondition is met.
     *
     * @param bool       $assertion      The assertion to check
     * @param string     $message        The postcondition message
     * @param \Exception $innerException An inner exception
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
     * Checks that an invariant is met.
     *
     * @param bool       $assertion      The invariant to check
     * @param string     $message        The error message
     * @param \Exception $innerException An inner exception
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
     * Checks that an assertion is met.
     *
     * @param bool       $assertion      The assertion to check
     * @param string     $message        The error message
     * @param \Exception $innerException An inner exception
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
     * Fluent interface for checking arguments.
     *
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
