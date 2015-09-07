<?php

namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

/**
 * Phalcon\UserPlugin\Forms\User\ChangePasswordForm.
 */
class ChangePasswordForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        //Current Password
        if (isset($options['must_change_password']) && $options['must_change_password'] == 1) {
        } else {
            $currentPassword = new Password('currentPassword');

            $currentPassword->addValidators(array(
                new PresenceOf(array(
                    'message' => 'Current password is required',
                )),
            ));

            $this->add($currentPassword);
        }

        $password = new Password('password');

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required',
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters',
            )),
            new Confirmation(array(
                'message' => 'Password doesn\'t match confirmation',
                'with' => 'confirmPassword',
            )),
        ));

        $this->add($password);

        //Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'The confirmation password is required',
            )),
        ));

        $this->add($confirmPassword);
    }
}
