<?php

namespace Proyecto404\UtilBundle\Form;

use Symfony\Component\Form\FormInterface;

/**
 * Utility class for forms.
 *
 * @author Nicolas Bottarini <nicolasbottarini@gmail.com>
 */
class FormUtil
{
    /**
     * Gets an associative array with all the errors in a form and it's children.
     *
     * @param FormInterface $form
     *
     * @return array
     */
    public static function getFormErrors(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors['_form_'][] = $error->getMessage();
        }

        self::addChildErrors($form, $errors);

        return $errors;
    }

    private static function addChildErrors(FormInterface $parent, &$errors, $path = '')
    {
        if ($parent->count() == 0) {
            return;
        }

        foreach ($parent as $child) {
            if ($path == '') {
                $childPath = $child->getName();
            } else {
                $childPath = $path.'.'.$child->getName();
            }

            if ($child->isValid()) {
                continue;
            }

            $childErrors = array();
            foreach ($child->getErrors() as $key => $error) {
                $childErrors[$key] = $error->getMessage();
            }

            if (count($childErrors) > 0) {
                $errors[$childPath] = $childErrors;
            }

            self::addChildErrors($child, $errors, $childPath);
        }
    }
}
