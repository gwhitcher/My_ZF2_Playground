<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Model\Page;
use Application\Model\PageTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //Insert role on create new user.
        $this->sm = $e->getApplication()->getServiceManager();

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        $zfcServiceEvents->attach('register.post', function($e) {

            /** @var \User\Entity\UserPdo $user */
            $user = $e->getParam('user');

            //This is the adapter that both bjyAuthorize and zfcuser use
            $adapter     = $this->sm->get('zfcuser_zend_db_adapter');

            //Build the insert statement
            $sql = new \Zend\Db\Sql\Sql($adapter);

            //bjyAuthorize uses a magic constant for the table name
            $insert = new \Zend\Db\Sql\Insert('user_role_linker');
            $insert->columns(array('user_id', 'role_id'));
            $insert->values(array('user_id' => $user->getId(), 'role_id' => 'guest'), $insert::VALUES_MERGE);

            //Execute the insert statement
            $adapter->query($sql->getSqlStringForSqlObject($insert), $adapter::QUERY_MODE_EXECUTE);
        });
        //End insert role on create new user
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Application\Model\PageTable' =>  function($sm) {
                    $tableGateway = $sm->get('PageTableGateway');
                    $table = new PageTable($tableGateway);
                    return $table;
                },
                'PageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Page());
                    return new TableGateway('page', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
