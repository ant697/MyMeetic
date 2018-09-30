<?php
/**
 * File contains BDD Function Doc Comment.
 *
 * PHP Version 7.2.6
 *
 * @category BDD
 * @package  BDD
 * @author   Antoine Guerra <antoine.guerra@epitech.eu>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://PHP_My_Cinema/index.php
 */

ini_set("display_errors", "on");

/**
 * Connect my bdd
 *
 * @return \PDO
 */
class MyBdd
{
    public $bdd;

    /**
     * MyBdd constructor.
     *
     */
    public function __construct()
    {
        $this->bdd = $this->connectBdd();
    }

    private function connectBdd()
    {
        $host = "localhost";
        $database = "my_meetic";
        $user = "root";
        $pass = 'root';
        try {
            $connexion = "mysql:host=$host;dbname=$database;charset=utf8";
            $bdd = new PDO($connexion, $user, $pass);
        } catch (Exception $e) {
            die('<h1 class="text-warning">Erreur : ' . $e->getMessage() . "</h1>");
        }
        return $bdd;
    }

    public function __destruct()
    {
//        echo "unset bdd";
        unset($this->bdd);
    }
}