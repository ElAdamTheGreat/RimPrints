<?php

$config = include('config.php');
include('models.php');

$dsn = "{$config['DB_DRIVER']}:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']};sslmode={$config['DB_SSLMODE']}";
$user = $config['DB_USERNAME'];
$password = $config['DB_PASSWORD'];

// Create PDO instance
$pdo = new PDO($dsn, $user, $password);

// Set error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// get queries (read)
function getAll(): array {
    global $pdo;
    $query = $pdo->query('SELECT p.*, u.id as user_id, u.username as user_username FROM "rimprints_Print" p JOIN "rimprints_User" u ON p."userId" = u.id;');
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $prints = [];
    foreach ($results as $row) {
        $user = new UserModel($row['user_id'], $row['user_username']);
        $prints[] = new PrintModel($row['id'], $row['title'], $row['desc'], $row['img'], $row['content'], $row['createdAt'], $row['updatedAt'], $user);
    }
    return $prints;
}

function getPrintById($id): PrintModel|null {
    global $pdo;
    $query = $pdo->prepare('SELECT p.*, u.id as user_id, u.username as user_username FROM "rimprints_Print" p JOIN "rimprints_User" u ON p."userId" = u.id WHERE p.id = :id;');
    $query->execute(['id' => $id]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user = new UserModel($row['user_id'], $row['user_username']);
        return new PrintModel($row['id'], $row['title'], $row['desc'], $row['img'], $row['content'], $row['createdAt'], $row['updatedAt'], $user);
    }
    return null;
}

function getPrintsByUserId($userId): array {
    global $pdo;
    $query = $pdo->prepare('SELECT p.*, u.id as user_id, u.username as user_username FROM "rimprints_Print" p JOIN "rimprints_User" u ON p."userId" = u.id WHERE p."userId" = :userId;');
    $query->execute(['userId' => $userId]);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $prints = [];
    foreach ($results as $row) {
        $user = new UserModel($row['user_id'], $row['user_username']);
        $prints[] = new PrintModel($row['id'], $row['title'], $row['desc'], $row['img'], $row['content'], $row['createdAt'], $row['updatedAt'], $user);
    }
    return $prints;
}

// Create, update, delete queries
function createPrint($title, $desc, $img, $content, $user_id): void {
    global $pdo;
    $query = $pdo->prepare("INSERT INTO rimprints_Print (title, desc, img, content, user_id) VALUES (:title, :desc, :img, :content, :user_id);");
    $query->execute(['title' => $title, 'desc' => $desc, 'img' => $img, 'content' => $content, 'user_id' => $user_id]);
}

function updatePrint($id, $title, $desc, $img, $content): void {
    global $pdo;
    $query = $pdo->prepare("UPDATE rimprints_Print SET title = :title, desc = :desc, img = :img, content = :content WHERE id = :id;");
    $query->execute(['id' => $id, 'title' => $title, 'desc' => $desc, 'img' => $img, 'content' => $content]);
}

function deletePrint($id): void {
    global $pdo;
    $query = $pdo->prepare("DELETE FROM rimprints_Print WHERE id = :id;");
    $query->execute(['id' => $id]);
}


// User queries
function createUser($username, $password): void {
    global $pdo;
    $query = $pdo->prepare("INSERT INTO rimprints_User (username, password) VALUES (:username, :password);");
    $query->execute(['username' => $username, 'password' => $password]);
}

function loginUser($username, $password): bool {
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM rimprints_User WHERE username = :username AND password = :password;');
    $query->execute(['username' => $username, 'password' => $password]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row !== false;
}

function getUserByUsername($username): UserModel|null {
    global $pdo;
    $query = $pdo->prepare('SELECT * FROM rimprints_User WHERE username = :username;');
    $query->execute(['username' => $username]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return new UserModel($row['id'], $row['username']);
    }
    return null;
}
?>
