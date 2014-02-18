<?php
namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Hidden,
Phalcon\Forms\Element\Password,
Phalcon\Forms\Element\Submit,
Phalcon\Forms\Element\Select,
Phalcon\Forms\Element\Check,
Phalcon\Forms\Element\Date,
Phalcon\Validation\Validator\Identical,
Phalcon\Validation\Validator\PresenceOf,
Phalcon\Validation\Validator\Email;

/**
 * Phalcon\UserPlugin\Forms\User\UserProfileForm
 */
class UserProfileForm extends Form
{
    public function initialize($entity=null, $options=null)
    {
        $translate = $this->getDI()->get('translate');

        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);
        $this->add(new Hidden('birth_date'));

        $this->add(new Select('gender', array(
            0 => $translate['Male'],
            1 => $translate['Female']
        )));

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);

        $this->add(new Submit('Save', array(
            'class' => 'btn btn-success'
        )));
    }
}
