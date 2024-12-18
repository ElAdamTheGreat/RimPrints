<?php
try {
    $config = include('config.php');
    
    $dsn = "{$config['DB_DRIVER']}:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_DATABASE']};sslmode={$config['DB_SSLMODE']}";
    $user = $config['DB_USERNAME'];
    $password = $config['DB_PASSWORD'];

    // Create PDO instanc
    $pdo = new PDO($dsn, $user, $password);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Run a test query
    $query = $pdo->query("SELECT version() AS version;");
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Display result
    echo "Connected successfully!<br>";
    echo "PostgreSQL Version: " . $result['version'] . "<br>";

} catch (PDOException $e) {
    // Catch any errors
    echo "Failed to connect to PostgreSQL: " . $e->getMessage();
    exit();
}
?>
