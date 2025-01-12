<?php
/**
 * This file is the queries file. It is used to store the queries for the database.
 * @author Adam Gombos
 */

$config = include('config.php');
include('models.php');

$dsn = "{$config['DB_DRIVER']}:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']};sslmode={$config['DB_SSLMODE']}";
$user = $config['DB_USERNAME'];
$password = $config['DB_PASSWORD'];

// Create PDO instance
$pdo = new PDO($dsn, $user, $password);

// Set error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


/**
 * @param int $page The current page number.
 * @return array An array containing the prints and the total number of pages.
 */
function getAll(int $page): array {
    global $pdo;

    // Number of items per page
    $limit = 12;

    // Step 1: Get the total number of prints
    $totalQuery = $pdo->query('SELECT COUNT(*) as total FROM "rimprints_Print";');
    $totalResult = $totalQuery->fetch(PDO::FETCH_ASSOC);
    $totalPrints = (int) $totalResult['total'];
    $totalPages = ceil($totalPrints / $limit);

    // Step 2: Calculate the offset based on the current page
    $offset = ($page - 1) * $limit;

    // Step 3: Get the current page of prints
    $query = $pdo->prepare('
        SELECT 
            p.id, 
            p.title, 
            u.id as user_id, 
            u.username as user_username 
        FROM "rimprints_Print" p 
        JOIN "rimprints_User" u ON p."userId" = u.id
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset;
    ');

    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Step 4: Transform the results into models
    $prints = [];
    foreach ($results as $row) {
        $user = new MiniUserModel($row['user_id'], $row['user_username']);
        $prints[] = new MiniPrintModel($row['id'], $row['title'], $user);
    }

    // Step 5: Return prints along with total pages
    return [
        'prints' => $prints,
        'totalPages' => $totalPages,
    ];
}


/**
 * @param mixed $id The ID of the print.
 * @return PrintModel|null The print model if found, otherwise null. The print model also contains the MiniUsermodel.
 */
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

/**
 * @param string $title The title of the print. Up to 32 characters.
 * @param string $desc The description of the print. Up to 512 characters.
 * @param string $content The content of the print. Up to 1MB.
 * @param int $user_id The ID of the user.
 * @return int The ID of the newly created print.
 */
function createPrint(string $title, string $desc, string $content, int $user_id): int {
    global $pdo;
    $query = $pdo->prepare('INSERT INTO "rimprints_Print" (title, "desc", content, "userId", "createdAt", "updatedAt") VALUES (:title, :desc, :content, :user_id, NOW(), NOW());');
    $query->execute(['title' => $title, 'desc' => $desc, 'content' => $content, 'user_id' => $user_id]);
    return (int)$pdo->lastInsertId();
}

/**
 * @param int $id The ID of the print.
 * @param string $title The title of the print. Up to 32 characters.
 * @param string $desc The description of the print. Up to 512 characters.
 * @param string $content The content of the print. Up to 1MB.
 * @return void
 */
function updatePrint($id, $title, $desc, $content): void {
    global $pdo;
    $query = $pdo->prepare('UPDATE "rimprints_Print" SET title = :title, "desc" = :desc, content = :content, "updatedAt" = NOW() WHERE id = :id;');
    $query->execute(['id' => $id, 'title' => $title, 'desc' => $desc, 'content' => $content]);
}

/**
 * @param int $id The ID of the print.
 * @return bool True if the print was deleted, otherwise false.
 */
function deletePrint(int $id): bool {
    global $pdo;
    $query = $pdo->prepare('DELETE FROM "rimprints_Print" WHERE id = :id;');
    $success = $query->execute(['id' => $id]);
    return $success;
}


/**
 * @param string $username The username of the user.
 * @param string $email The email of the user.
 * @param string $password The password of the user.
 * @param string $role The role of the user. Can be 'user' or 'admin'.
 * @return int The ID of the newly created user, or 0 if the username or email already exists, or -1 if the user could not be created.
 */
function createUser($username, $email, $password, $role): int {
    global $pdo;

    // Check if the username or email already exists
    $checkQuery = $pdo->prepare('SELECT COUNT(*) FROM "rimprints_User" WHERE username = :username OR email = :email');
    $checkQuery->execute(['username' => $username, 'email' => $email]);
    $count = $checkQuery->fetchColumn();

    if ($count > 0) {
        // User with the same username or email already exists
        return 0;
    }

    // Insert the new user
    $query = $pdo->prepare('INSERT INTO "rimprints_User" (username, email, password, role) VALUES (:username, :email, :password, :role);');
    $success = $query->execute(['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role]);

    if ($success) {
        // Return the newly created user ID
        return (int) $pdo->lastInsertId();
    } else {
        return -1;
    }
}

/**
 * @param string $username The username of the user.
 * @return bool True if the username is taken, otherwise false.
 */
function isUsernameTaken(string $username): bool {
    global $pdo;
    $query = $pdo->prepare('SELECT COUNT(*) FROM "rimprints_User" WHERE username = :username;');
    $query->execute(['username' => $username]);
    $result = $query->fetch(PDO::FETCH_COLUMN);
    return (bool)$result;
}

/**
 * @param string $email The email of the user.
 * @return bool True if the email is taken, otherwise false.
 */
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

/**
 * @param string $username The username of the user.
 * @param string $email The email of the user.
 * @return UserModel|null The user model if found, otherwise null.
 */
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

/**
 * @param int $id The ID of the user.
 * @return UserModel|null The user model if found, otherwise null.
 */
function getAllUsers(): array {
    global $pdo;
    $query = $pdo->query('SELECT u.id, u.username, u.email, u.role, COUNT(p.id) AS prints FROM "rimprints_User" u LEFT JOIN "rimprints_Print" p ON u.id = p."userId" GROUP BY u.id, u.username, u.email, u.role ORDER BY u.username ASC;');
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach ($results as $row) {
        $users[] = new UserModel($row['id'], $row['username'], $row['email'], $row['role'], $row['prints']);
    }
    return $users;
}

/**
 * @param int $id The ID of the user.
 * @return bool True if the user was deleted, otherwise false.
 */
function deleteUser(int $id): bool {
    global $pdo;
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Delete prints associated with the user
        $deletePrintsQuery = $pdo->prepare('DELETE FROM "rimprints_Print" WHERE "userId" = :id;');
        $deletePrintsQuery->execute(['id' => $id]);

        // Delete the user
        $deleteUserQuery = $pdo->prepare('DELETE FROM "rimprints_User" WHERE id = :id;');
        $deleteUserSuccess = $deleteUserQuery->execute(['id' => $id]);

        // Commit transaction
        $pdo->commit();

        return $deleteUserSuccess;
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        return false;
    }
}

/**
 * @param int $id The ID of the user.
 * @param string $role The role of the user. Can be 'user' or 'admin'.
 * @return bool True if the user role was changed, otherwise false.
 */
function changeUserRole(int $id, string $role): bool {
    global $pdo;
    $query = $pdo->prepare('UPDATE "rimprints_User" SET role = :role WHERE id = :id;');
    return $query->execute(['role' => $role, 'id' => $id]);
}