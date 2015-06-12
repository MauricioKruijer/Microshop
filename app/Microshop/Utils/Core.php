<?php
/**
 * Created by PhpStorm.
 * User: Mauricio
 * Date: 12/06/15
 * Time: 16:02
 */

namespace Microshop\Utils {

    class Core {
        protected $pdo;
        // Not sure how I can test this.. It's gonna slap me in the face.
        public function __construct(){
            $this->pdo = new \Aura\Sql\ExtendedPdo(
                'mysql:host=' . MYSQL_HOST . ';charset=utf8;dbname=' . MYSQL_DATABASE .';port=' . MYSQL_PORT,
                MYSQL_USER,
                MYSQL_PASSWORD,
                array(),
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
//        $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');

        }
    }
}