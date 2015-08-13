<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Application\Model\Page;
use ZendPdf\PdfDocument;
use ZendPdf\Page;
use ZendPdf\Font;

class IndexController extends AbstractActionController
{
    protected $pageTable;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function aboutAction()
    {
        //PdfDocument::load('path/to/pdf');
        $pdf = new PdfDocument();

        $pdf->pages[0] = new Page( Page::SIZE_A4 );
        $pdf->pages[0]->setFont( Font::fontWithName( Font::FONT_HELVETICA ), 24 );
        $pdf->pages[0]->drawText( 'Hello world!', 240, 400 );

        $pdf->save( 'example.pdf' );
        return new ViewModel();
    }

    public function pageAction()
    {
        $slug = $this->params()->fromRoute('slug');

        if (!$slug) {
            return $this->notFoundAction();
        }

        // Get the Page with the specified slug.  An exception is thrown
        // if it cannot be found, in which case go to the 404 page.
        try {
            $page = $this->getPageTable()->getPage($slug);
        }
        catch (\Exception $ex) {
            return $this->notFoundAction();
        }


        return array(
            'page' => $page,
        );
    }

    public function getPageTable()
    {
        if (!$this->pageTable) {
            $sm = $this->getServiceLocator();
            $this->pageTable = $sm->get('Application\Model\PageTable');
        }
        return $this->pageTable;
    }
}
