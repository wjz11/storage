<?php
namespace inc\SingletonMedoo;
final class SingletonMedoo
{
    /**
     * @var $db
     */
    private static $db;

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getdb()
    {
        if (null === static::$db) {
            static::$db = new \Medoo\medoo(array(
            	'database_type' => 'mysql',
            	'database_name' => 'huizhuangbao',
            	'server' => 'localhost',
            	'port' => 3306,
            	'username' => 'root',
            	'password' => '',//eoner.com
            	'charset' => 'utf8',
            	'option' => array(
            		\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            	)
            ));
        }

        return static::$db;
    }

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead
     */
    private function __construct()
    {
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone()
    {
    }

    /**
     * prevent from being unserialized (which would create a second instance of it)
     */
    private function __wakeup()
    {
    }
}

