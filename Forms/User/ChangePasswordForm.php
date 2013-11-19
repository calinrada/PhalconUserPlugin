<?php
namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Password,
    Phalcon\Forms\Element\Submit,
    Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\StringLength,
    Phalcon\Validation\Validator\Confirmation;

/**
 * Phalcon\UserPlugin\Forms\User\ChangePasswordForm
 */
class ChangePasswordForm extends Form
{
    public function initialize()
    {
        //Current Password
        $currentPassword = new Password('currentPassword');

        $currentPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Current password is required'
            ))
        ));

        $this->add($currentPassword);

        $password = new Password('password');

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            )),
            new Confirmation(array(
                'message' => 'Password doesn\'t match confirmation',
                'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        //Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'The confirmation password is required'
            ))
        ));

        $this->add($confirmPassword);
    }
}
