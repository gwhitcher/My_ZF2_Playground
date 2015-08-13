<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\EventManager\EventManagerInterface;

class AdminController extends AbstractActionController
{
    public function __construct()
    {
        $layout = $this->layout();
        $layout->setTemplate('layout/admin');
    }

    public function indexAction() {
    }
}