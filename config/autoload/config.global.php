<?php
/* These are custom global variables.  You can retrieve them by doing the below in any controller.
$config = $this->getServiceLocator()->get('Config');
echo $config['config']['title'];
*/
return array(
    'config' => array(
        'url' => 'http://localhost/zend/public/',
        'title' => 'Zend Skeleton Application',
    ),
);