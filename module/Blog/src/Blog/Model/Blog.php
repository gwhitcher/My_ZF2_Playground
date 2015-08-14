<?php
namespace Blog\Model;

class Blog
{
    public $id;
    public $title;
    public $slug;
    public $body;
    public $image;
    public $published_date;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->slug = (isset($data['slug'])) ? $data['slug'] : null;
        $this->body  = (isset($data['body']))  ? $data['body']  : null;
        $this->image  = (isset($data['image']))  ? $data['image']  : null;
        $this->published_date  = (isset($data['published_date']))  ? $data['published_date']  : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}