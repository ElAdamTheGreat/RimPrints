<?php
class UserModel {
    public $id;
    public $username;
    public $email;
    public $role;
    public $prints;

    public function __construct($id, $username, $email, $role, $prints) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->prints = $prints;
    }
}

class MiniUserModel {
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

class MiniPrintModel {
    public $id;
    public $title;
    public $img;
    public $user;

    public function __construct($id, $title, $img, $user) {
        $this->id = $id;
        $this->title = $title;
        $this->img = $img;
        $this->user = $user;
    }
}

?>