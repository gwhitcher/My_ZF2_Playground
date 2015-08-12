<?php
namespace Blog\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class BlogForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
        ));
        $this->add(array(
            'name' => 'body',
            'type' => 'Text',
            'options' => array(
                'label' => 'Blog',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => 'required',
            ),
        ));
        $this->addElements();
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'class' => 'btn btn-success',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image');
        $file->setLabel('Image Upload')
            ->setAttribute('id', 'image');
        $this->add($file);
    }
}