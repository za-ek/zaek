<?php
use PHPUnit\Framework\TestCase;

class dataTest extends TestCase
{
    /**
     * @var \zaek\engine\CMain
     */
    public $_app;
    public function setUp()
    {
        $this->_app = new class extends \zaek\engine\CMain
        {
            public function fs()
            {
                if ( is_null($this->_fs) ) {
                    $app = $this;
                    return new class ($app) extends \zaek\kernel\CFile
                    {
                        public function getContent($file) {
                            return "
                            fields='id;login;password'
                            line[]='1;root;qwerty1'
                            line[]='2;admin;qwerty'                            
                            ";
                        }
                    };
                }
                return $this->_fs;
            }
        };
    }
    public function testSelect()
    {
        $list = $this->_app->data()->select('users');

        $this->assertTrue(arrays_are_similar(
            [
                "1", 'root', 'qwerty1'
            ],
            $list->fetch()
        ));
        $this->assertTrue(arrays_are_similar(
            [
                "2", 'admin', 'qwerty'
            ],
            $list->fetch()
        ));

        $this->assertFalse($list->fetch());
    }
    public function testSelectFilter()
    {
        $list = $this->_app->data()->select('users', ['id' => 1]);

        $this->assertTrue(arrays_are_similar(
            [
                "1", 'root', 'qwerty1'
            ],
            $list->fetch()
        ));

        $this->assertFalse($list->fetch());
    }
    public function testSelectRange()
    {
        $list = $this->_app->data()->select('users', [], ['login', 'code']);

        $this->assertTrue(arrays_are_similar(['root'],$list->fetch()));
        $this->assertTrue(arrays_are_similar(['admin'],$list->fetch()));

        $this->assertFalse($list->fetch());
    }
    public function testOrder()
    {
        $list = $this->_app->data()->select('users', [], ['login'], ['login' => 'ASC']);

        $this->assertTrue(arrays_are_similar(['admin'],$list->fetch()));
        $this->assertTrue(arrays_are_similar(['root'],$list->fetch()));

        $this->assertFalse($list->fetch());
    }
    public function testLimit()
    {
        $list = $this->_app->data()->select('users', [], ['login'], [], [1]);

        $this->assertTrue(arrays_are_similar(['root'],$list->fetch()));

        $this->assertFalse($list->fetch());
    }
}