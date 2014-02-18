<?php
namespace Phalcon\UserPlugin\Forms\User;

use Phalcon\Forms\Form,
Phalcon\Forms\Element\Text,
Phalcon\Forms\Element\Hidden,
Phalcon\Forms\Element\Password,
Phalcon\Forms\Element\Submit,
Phalcon\Forms\Element\Select,
Phalcon\Forms\Element\Check,
Phalcon\Validation\Validator\PresenceOf,
Phalcon\Validation\Validator\Email;

use Phalcon\UserPlugin\Models\User\UserGroups;

/**
 * Phalcon\UserPlugin\Forms\User\UserForm
 */
class UserForm extends Form
{
    public function initialize($entity=null, $options=null)
    {
        $translate = $this->getDI()->get('translate');

        //In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);
        $this->add(new Text('name'));
        $this->add(new Text('email'));
        $this->add(new Select('group_id', UserGroups::find('active = 1'), array(
            'using' => array('id', 'name'),
            'useEmpty' => true,
            'emptyText' => '...',
            'emptyValue' => ''
        )));
        $this->add(new Select('banned', array(
            1 => $translate['Yes'],
            0 => $translate['No']
        )));
        $this->add(new Select('suspended', array(
            1 => $translate['Yes'],
            0 => $translate['No']
        )));
        $this->add(new Select('active', array(
            1 => $translate['Yes'],
            0 => $translate['No']
        )));
    }
}
