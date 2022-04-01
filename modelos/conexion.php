<?php

class Conexion
{

    static public function getConexion()
    {
        $link = new PDO("mysql:host=bsimmquhj0iln1yncjyc-mysql.services.clever-cloud.com;dbname=bsimmquhj0iln1yncjyc","uzt7klkkb8xcsq83","KxltGZO6y2v7uKqF2KsG");
        $link->exec("set names utf8");
        return $link;
    }

}
