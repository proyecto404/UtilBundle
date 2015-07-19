<?php

namespace Proyecto404\UtilBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * Password type that maintains value like other inputs.
 *
 * @see PasswordType
 */
class ValuedPasswordType extends PasswordType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // This method is empty overriding parent behaviour
    }
}
