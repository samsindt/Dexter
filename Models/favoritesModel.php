<?php
    require_once __DIR__ . "/../Helpers/connDB.php";

    class FavoritesModel {
        public $pokemonArray = array();
        public $spritesArray = array();

        public function __construct () {
            if(!isset($_SESSION)) { 
                session_start(); 
            } 
            $dbh = db_connect_ro();
                $this->pokemonArray = $this->getFavorites ($dbh);
                $this->spritesArray = $this->getSprites($dbh);  
            db_close($dbh);
        }

        private function getFavorites ($dbh) {
            $rows = array();
            if (isset ($_SESSION['userID'])) {
                $_SESSION['userID'];
                
                $sth = $dbh -> prepare("SELECT Name, Pokemon.PKID
                                        FROM HasFavorite, Pokemon  
                                        WHERE UID = :userID
                                        AND HasFavorite.PKID = Pokemon.PKID");

                $sth->bindValue(":userID", $_SESSION['userID']);
                $sth -> execute();
                
                while ($row = $sth -> fetch ()) {
                    $rows[] = $row;
                }
            }
            return $rows;
        }

        private function getSprites($dbh) {
            $rows = array();
            foreach ($this->pokemonArray as $pokemon) {
                $sth = $dbh->prepare("SELECT Image, Type
                                      FROM PokemonSprites
                                      WHERE SID = :pokemonID");
                $sth->bindValue (":pokemonID", $pokemon["PKID"]);
                $sth->execute();
                $rows[] = $sth -> fetch ();

            }
            
            return $rows;
        }
    }