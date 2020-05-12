<?php
try {
    $GLOBALS['dbh'] = new PDO(
        'mysql:host=10.56.0.2;dbname=asterisk',
        'eugen',
        'eugen');
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage();
    die();
}