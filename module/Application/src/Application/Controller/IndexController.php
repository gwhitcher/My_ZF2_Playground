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
//View Model
use Zend\View\Model\ViewModel;
//use Application\Model\Page;
//Enable PDF
use ZendPdf\PdfDocument;
use ZendPdf\Page;
use ZendPdf\Font;
//Enable JSON
use Zend\View\Model\JsonModel;
//Writes XML
use XMLWriter;
//RSS
use Zend\Feed\Writer\Feed;
use Zend\View\Model\FeedModel;

class IndexController extends AbstractActionController
{
    protected $pageTable;

    public function indexAction()
    {
        //Gets info from config.  Works in controller only, to pass variable to view use the below.
        $config = $this->getServiceLocator()->get('Config');
        $title = $config['config']['title'];
        return new ViewModel(array('site_title' => $title));
    }

    public function aboutAction()
    {
        //This page is mostly used for testing
        return new ViewModel();
    }

    public function pdfAction()
    {
        //PdfDocument::load('path/to/pdf'); //Load pdf from file
        //Create PDF document
        $pdf = new PdfDocument();

        $pdf->pages[0] = new Page( Page::SIZE_A4 );
        $pdf->pages[0]->setFont( Font::fontWithName( Font::FONT_HELVETICA ), 24 );
        $pdf->pages[0]->drawText( 'Hello world!', 240, 400 );

        //Check zend root for below file.
        $pdf->save('example.pdf');

        //Flash Message (change setNamespace to error, info, default, or success)
        $this->flashMessenger()->setNamespace('success')->addMessage('PDF Created');

        //Redirect home
        return $this->redirect()->toRoute('home');
    }

    public function rssAction()
    {
        $feed = new Feed;
        $feed->setTitle('Paddy\'s Blog');
        $feed->setLink('http://www.example.com');
        $feed->setFeedLink('http://www.example.com/atom', 'atom');
        $feed->addAuthor(array(
            'name'  => 'Paddy',
            'email' => 'paddy@example.com',
            'uri'   => 'http://www.example.com',
        ));
        $feed->setDateModified(time());
        $feed->addHub('http://pubsubhubbub.appspot.com/');

        /**
         * Add one or more entries. Note that entries must
         * be manually added once created.
         */
        $entry = $feed->createEntry();
        $entry->setTitle('All Your Base Are Belong To Us');
        $entry->setLink('http://www.example.com/all-your-base-are-belong-to-us');
        $entry->addAuthor(array(
            'name'  => 'Paddy',
            'email' => 'paddy@example.com',
            'uri'   => 'http://www.example.com',
        ));
        $entry->setDateModified(time());
        $entry->setDateCreated(time());
        $entry->setDescription('Exposing the difficultly of porting games to English.');
        $entry->setContent(
            'I am not writing the article. The example is long enough as is ;).'
        );
        $feed->addEntry($entry);

        /**
         * Render the resulting feed to Atom 1.0 and assign to $out.
         * You can substitute "atom" with "rss" to generate an RSS 2.0 feed.
         */
        print $feed->export('atom');
    }

    public function jsonAction()
    {
        //Return JSON
        $matches[] = array('distance' => 10, 'playground' => 'a', 'id' => 1);
        $matches[] = array('distance' => 20, 'playground' => 'b', 'id' => 2);
        $matches[] = array('distance' => 30, 'playground' => 'c', 'id' => 3);

        $result = new JsonModel(array(
            'success'=>true,
            'results' => $matches,
        ));

        return $result;
    }

    public function ajaxAction(){
        $data = array(
            'var1' => 'var1Value',
            'var2' => 'var2Value',
        );

        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($data));

        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');

        return $response;
    }

    public function xmlAction()
    {
        //Generate XML file
        $rawXml = '<foo><bar>Baz</bar></foo>';

        $writer= new XmlWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->writeRaw($rawXml);
        $writer->endDocument();

        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $response->setContent($writer->outputMemory());
        return $response;
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
