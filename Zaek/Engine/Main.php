<?php
namespace Zaek\Engine;

use Zaek\Data\Memory\Connector;
use Zaek\Kernel\Config;
use Zaek\Kernel\ConfigList;
use Zaek\Kernel\File;
use Zaek\Kernel\Request;
use Zaek\Kernel\Response;
use Zaek\Kernel\Router;

/**
 * Class Main
 * @package Zaek\Engine
 */
class Main
{
    /**
     * @var array
     */
    private $_objects;

    /**
     * Main constructor.
     * @param Config|null $conf
     */
    public function __construct(Config $conf = null)
    {
        $this->conf()->push($conf);
    }

    /**
     * Return an object
     *
     * @param $name
     * @param $class
     * @return mixed
     */
    protected function getObject($name, $class = null)
    {
        if(empty($this->_objects[$name])) {
            $this->_objects[$name] = new $class($this);
        }

        return $this->_objects[$name];
    }

    /**
     * @return ConfigList
     */
    public function conf()
    {
        return $this->getObject('conf', ConfigList::class);
    }
    /**
     * @return File
     */
    public function fs()
    {
        return $this->getObject('fs', File::class);
    }
    /**
     * @return Request
     */
    public function request()
    {
        return $this->getObject('request', Request::class);
    }
    /**
     * @return \Zaek\Data\Connector
     */
    public function data()
    {
        return $this->getObject('data', Connector::class);
    }
    /**
     * @return Router
     */
    public function router()
    {
        return $this->getObject('router', Router::class);
    }
    /**
     * @return Response
     */
    public function response()
    {
        return $this->getObject('response', Response::class);
    }

    /**
     * Include a file able to use app object from variable $this
     *
     * @param $file
     * @param bool $bRepeat
     * @return mixed
     */
    public function includeFile($file, $bRepeat = true)
    {
        if($this->fs()->checkRules($file, File::MODE_R)) {
            if ( $bRepeat ) {
                return include $file;
            } else {
                return include_once $file;
            }
        }
    }
}