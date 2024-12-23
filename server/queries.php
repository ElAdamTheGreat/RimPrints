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

function getTableNames() {
    global $pdo;
    $query = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';");
    $tables = $query->fetchAll(PDO::FETCH_ASSOC);
    return $tables;
}

function getAll() {
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


function getPrintById($id) {
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

function createPrint($title, $desc, $img, $content, $user_id) {
    global $pdo;
    $query = $pdo->prepare("INSERT INTO rimprints_Print (title, desc, img, content, user_id) VALUES (:title, :desc, :img, :content, :user_id);");
    $query->execute(['title' => $title, 'desc' => $desc, 'img' => $img, 'content' => $content, 'user_id' => $user_id]);
}
?>
