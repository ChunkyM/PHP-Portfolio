<?php

/**
 * Created by PhpStorm.
 * User: m026540e
 * Date: 14/11/2016
 * Time: 09:43
 */
require_once __DIR__ . '/../src/Database.php';
include __DIR__. '/../cfg/cfg.php';
class DatabaseTest extends PHPUnit_Framework_TestCase
{
   // public function testConnection(){
     //   $this->setExpectedException('Exeption','failed to connect' );
      //  $db = new Database();
    //}
    public function setUp() {
        global $cfg;
        try {
            $dbConn = new mysqli(
                $cfg['db']['host'],
                $cfg['db']['user'],
                $cfg['db']['pass'],
                $cfg['db']['db']
            );
        }catch (Exception $e){
            throw new Exception("connection error");
        }

        $sqlTable = <<<CREATETABLE
            CREATE TABLE IF NOT EXISTS `users` (
              `Id` int(4) NOT NULL AUTO_INCREMENT,
              `firstname` varchar(50) NOT NULL,
              `surname` varchar(50) NOT NULL,
              PRIMARY KEY (`Id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
CREATETABLE;

        $sqlInsert = <<<CREATEQUERY
            INSERT INTO `users` (`Id`, `firstname`, `surname`) VALUES
            (1, 'Philip', 'Windridge'),
            (2, 'Alastair', 'Dawes');
CREATEQUERY;
        //run query
        $dbConn->query($sqlTable);
        $dbConn->query($sqlInsert);
    

    }

    public function tearDown() {
        //set up connection to db
        global $cfg;
        try {
            $dbConn = new mysqli(
                $cfg['db']['host'],
                $cfg['db']['user'],
                $cfg['db']['pass'],
                $cfg['db']['db']
            );
        }catch (Exception $e){
            throw new Exception("connection error");
        }
        $sql = "DROP TABLE  `users`";
        //run sql query
        $dbConn->query($sql);
    }
    
    public  function testSimpleSelect(){
        $db = new  Database();

        $parameters = array(
          'fields'=>array('*'),
            'table'=>'users'
        );
        $expected = 'SELECT * FROM `users`'  ;
        $this->assertEquals($expected,$db->select($parameters) );

    }

    public  function testSimpleTwoFeild(){
            $db = new  Database();

            $parameters = array(
                'fields'=>array('firstname','surname'),
                'table'=>'users'
            );
            $expected = 'SELECT `firstname`,`surname` FROM `users`'  ;
            $this->assertEquals($expected,$db->select($parameters) );

    }

    public function testSelectWhereClause(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'conditions'=>array('firstname' =>'value1')
        );
        $expected = 'SELECT `firstname`,`surname` FROM `users` WHERE `firstname`=\'value1\''  ;
        $this->assertEquals($expected,$db->select($parameters) );

    }

    public function testSelectWhereStringNumber(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'conditions'=>array('firstname' =>'Philip', 'Id'=>1)
        );
        $expected = 'SELECT `firstname`,`surname` FROM `users` WHERE `firstname`=\'Philip\' AND `Id`=1'  ;
        $this->assertEquals($expected,$db->select($parameters) );

    }
    public function testSelectLike(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'like'=>array('firstname' =>'p%'),
            'LogicCondition'=>'LIKE'
        );
        $expected = 'SELECT `firstname`,`surname` FROM `users` WHERE `firstname` LIKE \'p%\''  ;
        $this->assertEquals($expected,$db->select($parameters) );


    }
    public function testSelectLessThan(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'like'=>array('Id' =>'1'),
            'LogicCondition'=>'<'

        );
        $expected = 'SELECT `firstname`,`surname` FROM `users` WHERE `Id` < 1'  ;
        $this->assertEquals($expected,$db->select($parameters) );


    }
    public function testSelectMoreThan(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'like'=>array('Id' =>'2'),
            'LogicCondition'=>'>'

        );
        $expected = 'SELECT `firstname`,`surname` FROM `users` WHERE `Id` > 2'  ;
        $this->assertEquals($expected,$db->select($parameters) );


    }
    public function testSelectAdvancedLike(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'like'=>array('firstname' =>'p%', 'surname'=>'w%'),
            'LogicCondition'=>'LIKE'
        );
        $expected = 'SELECT `firstname`,`surname` FROM `users` WHERE `firstname` LIKE \'p%\' AND `surname` LIKE \'w%\''  ;
        $this->assertEquals($expected,$db->select($parameters) );


    }
    public function testInsert(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2')

        );



        $expected = 1;
        $this->assertEquals($expected,$db->insert($parameters) );
        
    }
    public function testInsertMultiple(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('value1', 'value2','value3', 'value4' )

        );



        $expected = 2;
        $this->assertEquals($expected,$db->insert($parameters) );

    }
    public function testUpdate(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2')
            
        );



        $expected = 2;
        $this->assertEquals($expected,$db->update($parameters) );

    }

    public function testUpdateWhere(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2'),
            'conditions'=>array('firstname' =>'Philip', 'Id'=>1)

        );



        $expected = 1;
        $this->assertEquals($expected,$db->update($parameters) );

    }
    public function testDeleteWhere(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2'),
            'conditions'=>array('firstname' =>'Philip', 'Id'=>1)

        );



        $expected = 1;
        $this->assertEquals($expected,$db->delete($parameters) );

    }
    public function testDeleteLike(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2'),
            'like'=>array('firstname' =>'p%'),
            'LogicCondition'=>'LIKE'


        );



        $expected = 1;
        $this->assertEquals($expected,$db->delete($parameters) );

    }
    public function testDeleteMoreThan(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2'),
            'like'=>array('Id' =>'1'),
            'LogicCondition'=>'>'


        );



        $expected = 1;
        $this->assertEquals($expected,$db->delete($parameters) );

    }
    public function testDeleteLessThan(){
        $db = new Database();

        $parameters = array(
            'fields'=>array('firstname','surname'),
            'table'=>'users',
            'values'=>array('firstname' =>'value1', 'surname'=>'value2'),
            'like'=>array('Id' =>'2'),
            'LogicCondition'=>'<'


        );



        $expected = 1;
        $this->assertEquals($expected,$db->delete($parameters) );

    }
}
