<?php
class UserModel {
    public $id;
    public $username;

    public function __construct($id, $username) {
        $this->id = $id;
        $this->username = $username;
    }
}

class PrintModel {
    public $id;
    public $title;
    public $desc;
    public $img;
    public $content;
    public $createdAt;
    public $updatedAt;
    public $user;

    public function __construct($id, $title, $desc, $img, $content, $createdAt, $updatedAt, $user) {
        $this->id = $id;
        $this->title = $title;
        $this->desc = $desc;
        $this->img = $img;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->user = $user;
    }
}


?>