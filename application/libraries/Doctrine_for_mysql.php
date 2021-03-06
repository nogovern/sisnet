<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Tools\Setup,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\DBAL\Logging\EchoSQLLogger;

use Doctrine\Common\EventManager;

class Doctrine {

    public $em = null;

    public function __construct()
    {
    // load database configuration from CodeIgniter
    require APPPATH.'config/database.php';

    // Database connection information
    $connectionOptions = array(
        // 'driver'    =>  $db[$active_group]['dbdriver'],
        'driver'    =>  'pdo_mysql',
        'user'      =>  $db[$active_group]['username'],
        'password'  =>  $db[$active_group]['password'],
        'host'      =>  "127.0.0.1",
        // 'port'      =>  $db[$active_group]['port'],   
        'dbname'    =>  $db[$active_group]['database'],
        'charset'   =>  $db[$active_group]['char_set']
    );

    // With this configuration, your model files need to be in application/models/Entity
    // e.g. Creating a new Entity\User loads the class from application/models/Entity/User.php
    $models_namespace = 'Entity';
    $models_path = APPPATH . 'models';
    $proxies_dir = APPPATH . 'models/proxies';
    $metadata_paths = array(APPPATH . 'models');

    // Set $dev_mode to TRUE to disable caching while you develop
    $config = Setup::createAnnotationMetadataConfiguration($metadata_paths, $dev_mode = true, $proxies_dir);

    ///////////////////
    // Set up logger //
    ///////////////////
    // $logger = new EchoSQLLogger;
    // $config->setSQLLogger($logger);

    /*
     oracle 에서 매우 중요  
     date / datetme 사용시 oracle 용 Doctrine Data Type 을 로드하지 못함.
     그러므로 강제로 등록함
     (배운점) 웹검색을 통한 것보다, debug를 통해서 해결하는게 더 도움됨. 웹검색은 hint 정도만...
     */
    
    ///////////////////
    // event manager //
    ///////////////////
    $evm = new EventManager;
    // $evm->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\OracleSessionInit);

    // Create EntityManager
    $this->em = EntityManager::create($connectionOptions, $config, $evm);

    $loader = new ClassLoader($models_namespace, $models_path);
    $loader->register();

    
    }
}
