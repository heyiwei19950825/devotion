<?php

namespace app\hejiang;

/**
 * Hejiang Application
 * 
 * @property \Raven_Client $sentry
 */
class Application extends \yii\web\Application
{
    /**
     * Client of sentry.
     *
     * @var \Raven_Client
     */
    protected $_sentry_client;

    public function __construct($configFile)
    {
        $this->loadDotEnv();
        $this->defineConstants();

        require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
        parent::__construct(require $configFile);

        $this->initializeSentry();
        if(!YII_DEBUG) {
            $this->installSentry();
        }
        $this->preQuery();
    }

    /**
     * Load .env file
     *
     * @return void
     */
    protected function loadDotEnv()
    {
        try {
            $dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
            $dotenv->load();
        } catch (\Dotenv\Exception\InvalidPathException $ex) {
        }
    }

    /**
     * Define some constants
     *
     * @return void
     */
    protected function defineConstants()
    {
        define_once('WE7_MODULE_NAME', 'zjhj_mall');
        define_once('IN_IA', true);
        $this->defineEnvConstants(['YII_DEBUG', 'YII_ENV']);
    }

    /**
     * Define some constants via `env()`
     *
     * @param array $names
     * @return void
     */
    protected function defineEnvConstants($names = [])
    {
        foreach ($names as $name) {
            if ((!defined($name)) && ($value = env($name))) {
                define($name, $value);
            }
        }
    }

    /**
     * Initialize sentry client and install error handlers
     *
     * @return void
     */
    protected function installSentry()
    {
        $error_handler = new ErrorHandler($this->sentry);
        $error_handler->registerExceptionHandler(true, ['\yii\web\HttpException']);
        $error_handler->registerErrorHandler();
        $error_handler->registerShutdownFunction();
    }

    /**
     * Create and initialize sentry client
     *
     * @return \Raven_Client
     */
    private function initializeSentry()
    {
        return $this->_sentry_client = new \Raven_Client(
            $this->params['sentry']['dsn']
        );
    }

    /**
     * Sentry getter
     *
     * @return \Raven_Client
     */
    public function getSentry()
    {
        return $this->_sentry_client;
    }

    /**
     * Run pre queries
     *
     * @return int
     */
    protected function preQuery()
    {
        return $this->db->createCommand(
            "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"
        )->execute();
    }
}