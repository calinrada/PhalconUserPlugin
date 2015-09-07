<?php

namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\Identical;

/**
 * Phalcon\UserPlugin\Forms\User\UserProfileForm.
 */
class UserProfileForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);
        $this->add(new Hidden('birth_date'));

        $this->add(new Select('gender', array(
            0 => 'Male',
            1 => 'Female',
        )));

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed',
            ))
        );

        $this->add($csrf);

        $this->add(new Submit('Save', array(
            'class' => 'btn btn-success',
        )));
    }
}
