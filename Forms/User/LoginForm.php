<?php
namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Password,
Phalcon\Forms\Element\Submit,
Phalcon\Forms\Element\Check,
Phalcon\Forms\Element\Hidden,
Phalcon\Validation\Validator\PresenceOf,
Phalcon\Validation\Validator\Email,
Phalcon\Validation\Validator\Identical;

/**
 * Phalcon\UserPlugin\Forms\User\LoginForm
 */
class LoginForm extends Form
{
    public function initialize()
    {
        $translate = $this->getDi()->get('translate');

        //Email
        $email = new Text('email', array(
            'placeholder' => $translate['Email']
        ));

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => $translate['The e-mail is required']
            )),
            new Email(array(
                'message' => $translate['The e-mail is not valid']
            ))
        ));

        $this->add($email);

        //Password
        $password = new Password('password', array(
            'placeholder' => $translate['Password']
        ));

        $password->addValidator(
            new PresenceOf(array(
                'message' => $translate['The password is required']
            ))
        );

        $this->add($password);

        //Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));

        $remember->setLabel($translate['Remember me']);

        $this->add($remember);

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);

        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
    }
}
