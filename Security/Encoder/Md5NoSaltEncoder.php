<?php

namespace Proyecto404\UtilBundle\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Encode a password in md5 without use the salt.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class Md5NoSaltEncoder implements PasswordEncoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        return hash('md5', $raw);
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }
}
