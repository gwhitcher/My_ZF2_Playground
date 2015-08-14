<?php
namespace Admin\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class BlogTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated=false)
    {
        /* OLD
        $resultSet = $this->tableGateway->select();
        return $resultSet;
        */
        if ($paginated) {
            // create a new Select object for the table album
            $select = new Select('blog');
            // create a new result set based on the Album entity
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Blog());
            // create a new pagination adapter object
            $paginatorAdapter = new DbSelect(
            // our configured select object
                $select,
                // the adapter to run it against
                $this->tableGateway->getAdapter(),
                // the result set to hydrate
                $resultSetPrototype
            );
            $paginator = new Paginator($paginatorAdapter);
            return $paginator;
        }
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
        $published_date = date('Y-m-d H:i:s');
        if(!empty($blog->image['name'])) { // Image field is not empty inserting into DB...
            $data = array(
                'title' => $blog->title,
                'slug' => $this->fixForUri($blog->title),
                'body'  => $blog->body,
                'image'  => $blog->image['name'],
                'published_date' => $published_date,
            );
        } else { // Image field is empty ignore DB insert...
            $data = array(
                'title' => $blog->title,
                'slug' => $this->fixForUri($blog->title),
                'body'  => $blog->body,
                'published_date' => $published_date,
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

    public function fixForUri($string){
        $slug = trim($string); // trim the string
        $slug= preg_replace('/[^a-zA-Z0-9 -]/','',$slug ); // only take alphanumerical characters, but keep the spaces and dashes too...
        $slug= str_replace(' ','-', $slug); // replace spaces by dashes
        $slug= strtolower($slug);  // make it lowercase
        return $slug;
    }
}