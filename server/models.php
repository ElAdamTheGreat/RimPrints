<?php
/**
 * This file is the models file. It is used to store the models for the database tables.
 * @author Adam Gombos
 */

/**
 * The UserModel class represents a user in the database.
 * The MiniUserModel class represents a user in a smaller form.
 * The PrintModel class represents a print in the database.
 * The MiniPrintModel class represents a print in a smaller form.
 */
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
    public $content;
    public $createdAt;
    public $updatedAt;
    public $user;

    public function __construct($id, $title, $desc, $content, $createdAt, $updatedAt, $user) {
        $this->id = $id;
        $this->title = $title;
        $this->desc = $desc;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->user = $user;
    }
}

class MiniPrintModel {
    public $id;
    public $title;
    public $user;

    public function __construct($id, $title, $user) {
        $this->id = $id;
        $this->title = $title;
        $this->user = $user;
    }
}

?>