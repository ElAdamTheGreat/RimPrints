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
    $query = $pdo->query('SELECT p.id, p.title, u.id as user_id, u.username as user_username FROM "rimprints_Print" p JOIN "rimprints_User" u ON p."userId" = u.id;');
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $prints = [];
    foreach ($results as $row) {
        $user = new MiniUserModel($row['user_id'], $row['user_username']);
        $prints[] = new MiniPrintModel($row['id'], $row['title'],  $user);
    }
    return $prints;
}

function getPrintById($id): PrintModel|null {
    global $pdo;
    $query = $pdo->prepare('SELECT p.*, u.id as user_id, u.username as user_username FROM "rimprints_Print" p JOIN "rimprints_User" u ON p."userId" = u.id WHERE p.id = :id;');
    $query->execute(['id' => $id]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $user = new MiniUserModel($row['user_id'], $row['user_username']);
        return new PrintModel($row['id'], $row['title'], $row['desc'], $row['content'], $row['createdAt'], $row['updatedAt'], $user);
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
        $user = new MiniUserModel($row['user_id'], $row['user_username']);
        $prints[] = new PrintModel($row['id'], $row['title'], $row['desc'], $row['content'], $row['createdAt'], $row['updatedAt'], $user);
    }
    return $prints;
}

// Create, update, delete queries
function createPrint(string $title, string $desc, string $content, int $user_id): int {
    global $pdo;
    $query = $pdo->prepare('INSERT INTO "rimprints_Print" (title, "desc", content, "userId", "createdAt", "updatedAt") VALUES (:title, :desc, :content, :user_id, NOW(), NOW());');
    $query->execute(['title' => $title, 'desc' => $desc, 'content' => $content, 'user_id' => $user_id]);
    return (int)$pdo->lastInsertId();
}

function updatePrint($id, $title, $desc, $content): void {
    global $pdo;
    $query = $pdo->prepare('UPDATE "rimprints_Print" SET title = :title, desc = :desc, content = :content WHERE id = :id;');
    $query->execute(['id' => $id, 'title' => $title, 'desc' => $desc, 'content' => $content]);
}

function deletePrint(int $id): bool {
    global $pdo;
    $query = $pdo->prepare('DELETE FROM "rimprints_Print" WHERE id = :id;');
    $success = $query->execute(['id' => $id]);
    return $success;
}


// User queries
function createUser($username, $email, $password): void {
    global $pdo;
    $query = $pdo->prepare('INSERT INTO "rimprints_User" (username, email, password) VALUES (:username, :email, :password);');
    $query->execute(['username' => $username, 'email' => $email, 'password' => $password]);
}
function checkUserPass($username = null, $email = null): string|null  {
    global $pdo;
    $conditions = [];
    $params = [];

    if ($username !== null) {
        $conditions[] = 'username = :username';
        $params['username'] = $username;
    }

    if ($email !== null) {
        $conditions[] = 'email = :email';
        $params['email'] = $email;
    }

    if (empty($conditions)) {
        return null; // No conditions provided
    }

    $query = $pdo->prepare('SELECT "password" FROM "rimprints_User" WHERE ' . implode(' OR ', $conditions) . ';');
    $query->execute($params);
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['password'] : null;
}

function getUserByUsermail($username = null, $email = null): UserModel|null {
    global $pdo;
    $conditions = [];
    $params = [];

    if ($username !== null) {
        $conditions[] = 'u.username = :username';
        $params[':username'] = $username;
    }

    if ($email !== null) {
        $conditions[] = 'u.email = :email';
        $params[':email'] = $email;
    }

    if (empty($conditions)) {
        return null; // No conditions provided
    }

    $query = $pdo->prepare('SELECT u.id, u.username, u.email, u.role, COUNT(p.id) AS prints FROM "rimprints_User" u LEFT JOIN "rimprints_Print" p ON u.id = p."userId" WHERE ' . implode(' OR ', $conditions) . ' GROUP BY u.id, u.username, u.email, u.role;');
    $query->execute($params);
    $row = $query->fetch(mode: PDO::FETCH_ASSOC);
    
    if ($row) {
        return new UserModel($row['id'], $row['username'], $row['email'], $row['role'], $row['prints']);
    }
    return null;
}

