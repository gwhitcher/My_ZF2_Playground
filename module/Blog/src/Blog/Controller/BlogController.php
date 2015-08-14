<?php
namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BlogController extends AbstractActionController
{
    protected $blogTable;

    public function indexAction()
    {
        /*
        return new ViewModel(array(
            'blogs' => $this->getBlogTable()->fetchAll()));
        */
        // grab the paginator from the AlbumTable
        $paginator = $this->getBlogTable()->fetchAll(true);
        // set the current page to what has been passed in query string, or to 1 if none set
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        // set the number of items per page to 10
        $paginator->setItemCountPerPage(10);

        return new ViewModel(array(
            'paginator' => $paginator
        ));
    }

    public function getBlogTable()
    {
        if (!$this->blogTable) {
            $sm = $this->getServiceLocator();
            $this->blogTable = $sm->get('Blog\Model\BlogTable');
        }
        return $this->blogTable;
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('blog', array(
                'action' => 'index'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $blog = $this->getBlogTable()->getBlog($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('blog', array(
                'action' => 'index'
            ));
        }

        return array(
            'blog' => $blog,
        );
    }

}