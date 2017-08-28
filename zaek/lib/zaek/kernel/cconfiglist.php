<?php
namespace zaek\kernel;

use function PHPSTORM_META\type;
use zaek\basics\algorithm\COverride;

class CConfigList extends COverride
{
    /**
     * @en Return config param $opt from section $sec from configuration list
     * @es Devuelve el valor de opción $opt en sección $sec de la lista de configuraciones
     * @ru Возвращает конфигурационный параметр $opt из раздела $sec
     *
     * @param $sec
     * @param $opt
     * @return bool|mixed
     * @throws CException
     */
    public function get($sec, $opt)
    {
        $result = $this->rollMethod('getValue', array($sec, $opt));
        if ( $result !== null ) {
            return $result;
        } else {
            throw new CException('CONFIG_VALUE_NOT_SET ['.$sec.':'.$opt.']', 3);
        }

    }

    /**
     * Проверяет, определён ли параметр
     * @param $sec
     * @param $opt
     * @return bool|mixed|null
     */
    public function isDefined($sec, $opt)
    {
        return $this->rollMethod('isDefined', array($sec, $opt));
    }
    /**
     * Переопределение абстрактного метода из класса COverride
     * @param $result
     * @return bool
     */
    function check($result)
    {
        return ($result !== null);
    }

    /**
     * Переопределение метода из класса COverride
     *
     * @param $method_name
     * @param $params
     * @return bool|mixed
     */
    protected function callMethod($method_name, $params)
    {
        try {
            if ( $this->current()[2] == null ) {
                $this->initializeConfig();
            }

            if ( call_user_func_array(array($this->current()[2], 'isDefined'), $params)) {
                return call_user_func_array(array($this->current()[2], $method_name), $params);
            } else {
                return null;
            }
        } catch ( CException $e ) {
            return false;
        }
    }

    protected function initializeConfig()
    {
        $a = $this->current();

        switch ($a[0]) {
            case 'ini':
                $a[2] = new CConfig((array)@parse_ini_file($a[1], true));
                break;
        }

        $this[$this->key()] = $a;
    }

    public function addFile($path, $type)
    {
        parent::push([$type, $path, null]);
    }
    public function push($value)
    {
        if ( is_array($value) ) {
            parent::push([
                'array',
                '',
                new CConfig($value)
            ]);
        } else if ( is_object($value) && $value instanceof CConfig ) {
            parent::push([
                '',
                '',
                $value
            ]);
        }
    }
}