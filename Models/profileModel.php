<?php
    require_once __DIR__ . "/../Helpers/connDB.php";

    class ProfileModel {
        public $pokemonID;
        public $pokemonName;
        public $dexNumber;
        public $evolvesFrom = array();
        public $evolvesTo = array();
        public $HP;
        public $attack;
        public $defense;
        public $spAttack;
        public $spDefense;
        public $speed;
        public $eggGroups = array();
        public $typeImages = array();
        public $profileImage = array();
        public $bIsFavorited;

        public function __construct($pokemonID) {
            $dbh = db_connect_ro();

            $pokemonData = $this->getPokemonData ($dbh, $pokemonID);

            $this->pokemonID = $pokemonID;
            $this->pokemonName = $pokemonData["Name"];
            $this->dexNumber = $pokemonData["DexNumber"];
            $this->evolvesFrom = $this->getEvolvesFrom($dbh, $pokemonID);
            $this->evolvesTo = $this->getEvolvesTo($dbh, $pokemonID);
            $this->HP = $pokemonData["HP"];
            $this->attack = $pokemonData["Attack"];
            $this->defense = $pokemonData["Defense"];
            $this->spAttack = $pokemonData["SpAttack"];
            $this->spDefense = $pokemonData["SpDefense"];
            $this->speed = $pokemonData["Speed"];
            $this->typeImages = $this->getTypeImage($dbh, $pokemonID);
            $this->profileImage = $this->getProfileImage($dbh, $pokemonID);
            $this->isFavorited = $this->checkIfFavorite($dbh, $pokemonID);

            db_close($dbh);
        }

        private function getPokemonData ($dbh, $pokemonID) {

            $sth = $dbh -> prepare("SELECT *
                                    FROM Pokemon 
                                    WHERE PKID = :pokemonID");

            $sth->bindValue(":pokemonID", $pokemonID);
            $sth -> execute();
    
            return $sth -> fetch();
        }

        private function getEvolvesTo($dbh, $evolvesFrom) {
            
            $sth = $dbh -> prepare("SELECT EvolvesTo, Name
                                    FROM Evolutions, Pokemon
                                    WHERE EvolvesFrom = :evolvesFrom AND EvolvesTo = PKID");

            $sth->bindValue(":evolvesFrom", $evolvesFrom);
            $sth -> execute();
    
            return $sth -> fetch();
        }
        
        private function getEvolvesFrom($dbh, $evolvesTo) {
            $sth = $dbh -> prepare("SELECT EvolvesFrom, Name
                                    FROM Evolutions, Pokemon
                                    WHERE EvolvesTo = :evolvesTo AND EvolvesFrom = PKID");
            $sth->bindValue(":evolvesTo", $evolvesTo);
            $sth -> execute ();
            
            return $sth -> fetch ();
        }	

        private function getTypeImage($dbh, $pokemonID) {
            $rows = array();
    
            $sth = $dbh -> prepare("SELECT Image, Type
                                    FROM HasTypes, Types, TypeImages
                                    WHERE PokemonID = :pokemonID AND HasTypes.TypeID = Types.TypeID
                                    AND Types.TypeID = TypeImages.TypeImageID" );
            $sth->bindValue(":pokemonID", $pokemonID);
            $sth -> execute();
            
            while ($row = $sth -> fetch ()) {
                $rows[] = $row;
            }
            return $rows;
        }

        private function getProfileImage($dbh, $pokemonID) {
            $sth = $dbh->prepare("SELECT Image, Type
							  FROM PokemonImages
							  WHERE IID = :pokemonID");
		    $sth->bindValue(":pokemonID", $pokemonID);
		
		    $sth->execute();
	  
            return $sth->fetch();
        }

        private function checkIfFavorite ($dbh, $pokemonID) {
            $bFlag = false;

            if(!isset($_SESSION)) { 
                session_start(); 
            }
            
            if (isset ($_SESSION['userID'])) {
                $sth = $dbh -> prepare("SELECT *
                                        FROM HasFavorite, Pokemon  
                                        WHERE UID = :userID AND HasFavorite.PKID = :pokemonID");
            
                $sth->bindValue(":pokemonID", $pokemonID);
                $sth->bindValue(":userID", $_SESSION['userID']);
                $sth -> execute();

                $result = $sth -> fetch();
                
                if ($result) {
                    $bFlag = true;
                }
            }

            return $bFlag;
        }

    }
?>