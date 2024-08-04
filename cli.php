<?php

// $driver = "mysql";
// // http_build_query a été introduit pour créer des URL, mais il fonctionne pour faire des DSN strings !
// $config = http_build_query(data: [
//     'host' => 'localhost',
//     'port' => 3306,
//     'dbname' => 'phpiggy'
// ], arg_separator: ';');

// $dsn = "{$driver}:{$config}";

// $username = 'root';
// $password = '';

// try {
//     $db = new PDO($dsn, $username, $password);
// } catch (PDOException $e) {
//     die("Unable to connect to database");
// }

include __DIR__ . "/src/Framework/Database.php";

use Framework\Database;

$db = new Database('mysql', [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'phpiggy'
], 'root', '');

// try {
//     $db->connection->beginTransaction();    // Créer une transaction, toutes les query qui suivront seront groupés dedans.

//     $db->connection->query("INSERT INTO products VALUES(99,'Gloves')");

//     $search = "Hats' OR 1=1 --";
//     // $query = "SELECT * FROM products WHERE name=?";
//     $query = "SELECT * FROM products WHERE name=:name";

//     //D'abord on créé la query (string), puis on récupère un statement, et enfin on peut accéder aux résultats avec le PDOStatement class return par la query.
//     $stmt = $db->connection->prepare($query); //On appel le résultat d'une query un "statement".

//     $stmt->bindValue('name', $search, PDO::PARAM_STR);

//     $stmt->execute();

//     var_dump($stmt->fetchAll(PDO::FETCH_OBJ));    // Notre query récupère tous les row de notre table. fetchAll() récupère la liste complète des résultats.

//     $db->connection->commit();  // Si les queries avant cette methode sont successful, commit() va sauvegarder les changements dans la db et terminer la transaction.
// } catch (Exception $error) {
//     if ($db->connection->inTransaction()) {
//         $db->connection->rollback();
//     }

//     echo "Transaction faild!";
// }

$sqlFile = file_get_contents("./database.sql");

$db->connection->query($sqlFile);
