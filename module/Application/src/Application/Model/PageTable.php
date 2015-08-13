<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class PageTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getPage($slug)
    {
        $rowset = $this->tableGateway->select(array('slug' => $slug));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $slug");
        }
        return $row;
    }

    public function savePage(Page $page)
    {
        if (!empty($page->image['name'])) { // Image field is not empty inserting into DB...
            $data = array(
                'title' => $page->title,
                'body' => $page->body,
                'image' => $page->image['name'],
            );
        } else { // Image field is empty ignore DB insert...
            $data = array(
                'title' => $page->title,
                'body' => $page->body,
            );
        }

        $id = (int)$page->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Page id does not exist');
            }
        }
    }

    public function deletePage($id)
    {
        $this->tableGateway->delete(array('id' => (int)$id));
    }
}