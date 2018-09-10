<?php

$we7_config_file = __DIR__ . '/../../../../data/config.php';
$ind_db_file = __DIR__ . '/ind_db.php';

if (file_exists($ind_db_file)) {
    return include $ind_db_file;
} elseif (file_exists($we7_config_file)) {
    include $we7_config_file;
    if (!isset($config['db']['master'])) {
        $config['db']['master'] = [];
    }

    $config['db']['master']['host'] = $config['db']['master']['host'] ?: $config['db']['host'];
    $config['db']['master']['port'] = $config['db']['master']['port'] ?: $config['db']['port'];
    $config['db']['master']['database'] = $config['db']['master']['database'] ?: $config['db']['database'];
    $config['db']['master']['username'] = $config['db']['master']['username'] ?: $config['db']['username'];
    $config['db']['master']['password'] = $config['db']['master']['password'] ?: $config['db']['password'];
    
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=' . $config['db']['master']['host'] . ';port=' . $config['db']['master']['port'] . ';dbname=' . $config['db']['master']['database'],
        'username' => $config['db']['master']['username'],
        'password' => $config['db']['master']['password'],
        'charset' => 'utf8',
        'tablePrefix' => 'hjmall_',
    ];

} else {
    throw new \Exception('cannot find db config file');
}
