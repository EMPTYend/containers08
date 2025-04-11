<?php

require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';


$tests = new TestFramework();

$tests->add('Database connection', function() {
    global $config;
    try {
        $db = new Database($config['db']['path']);
        return assertExpression($db !== null, 'Connected', 'No connection');
    } catch (Exception $e) {
        return assertExpression(false, 'Connected', $e->getMessage());
    }
});

$tests->add('Table count', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $count = $db->Count('page');
    return assertExpression($count >= 3, 'Count OK', 'Wrong count');
});

$tests->add('Create record', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $id = $db->Create('page', ['title' => 'Test', 'content' => 'Test content']);
    return assertExpression($id > 0, 'Created', 'Not created');
});

$tests->add('Read record', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $data = $db->Read('page', 1);
    return assertExpression(is_array($data) && isset($data['title']), 'Read OK', 'Read failed');
});

$tests->add('Update record', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $ok = $db->Update('page', 1, ['title' => 'Updated', 'content' => 'Updated content']);
    return assertExpression($ok, 'Update OK', 'Update failed');
});

$tests->add('Delete record', function() {
    global $config;
    $db = new Database($config['db']['path']);
    $id = $db->Create('page', ['title' => 'Temp', 'content' => '']);
    $ok = $db->Delete('page', $id);
    return assertExpression($ok, 'Delete OK', 'Delete failed');
});

$tests->run();
echo $tests->getResult();
