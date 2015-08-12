<?php
namespace Blog\Model;

use Zend\Db\TableGateway\TableGateway;

class BlogTable
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

    public function getBlog($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBlog(Blog $blog)
    {
        if(!empty($blog->image['name'])) { // Image field is not empty inserting into DB...
            $data = array(
                'title' => $blog->title,
                'body'  => $blog->body,
                'image'  => $blog->image['name'],
            );
        } else { // Image field is empty ignore DB insert...
            $data = array(
                'title' => $blog->title,
                'body'  => $blog->body,
            );
        }

        $id = (int) $blog->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBlog($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Blog id does not exist');
            }
        }
    }

    public function deleteBlog($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}