<?php
namespace Zaek\Basics\Graph;
/**
 * Class Node
 * @package Zaek\Basics\Graph
 *
 */
class Node
{
    /**
     * @en Node ID
     * @es ID de nudo
     * @ru ID узла
     */
    private $_id;

    /**
     * @en Node constructor - set up a node ID
     * @es Constructor - assigna el ID del nudo
     * @ru Конструктор - устанавливает ID узла
     *
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->_id = $id;
    }

    /**
     * @en Returns node ID
     * @es Devuelve el ID del nudo
     * @ru Возвращает ID узла
     *
     * @return mixed
     */
    final public function getId()
    {
        return $this->_id;
    }

    public function __toString()
    {
        return (string)$this->_id;
    }
}