<?php
/**
 * DOC ME!
 */
class Shoppinglist {

    private $dbh;
	public $errorString;
	
	public function __construct($host, $user, $pass, $dbname) {
		if($this->dbh = new mysqli($host, $user, $pass, $dbname))
			return true;
		else
			return false;
	}

    public function close() {
		$this->dbh->close();
	}

    public function insertArtikel($Artikel, $Menge) {
		
		$sql = "insert into einkaufsliste set ware='".$Artikel."', anzahl='".$Menge."'";
		$this->loggeSql($sql);
		
		if($this->dbh->query($sql)) {		
			// ID des letzten Datensatzes holen und zurückgeben
			$id = $this->dbh->insert_id;
		} else {
			$id = false;
		}
		return $id;
	}
	//Diese Funktion soll Artikel anhand der Übergebenen ID löschen
    public function deleteArtikelById($id) {
		//obligatorisches ID überprüfen
		if(empty($id)) {
			$this->errorString = "id nicht gesetzt";
			return false;
		}
		if(!is_int($id)) {
			$this->errorString = "id muss integer sein";
			return false;
		}
		
		$sql = "DELETE FROM einkaufsliste WHERE id='".$id."'"; 
		$this->loggeSql($sql);
		$this->dbh->query($sql);
		
	}

	public function markArtikel($id){
		if(empty($id)) {
			$this->errorString = "id nicht gesetzt";
			return false;
		}
		if(!is_int($id)) {
			$this->errorString = "id muss integer sein";
			return false;
		}
	//Hier möchte ich den wert aus der spalte marked aus meiner DB, die Anzeigt das ein Artikel Markiert wurde, in einer Variablen speichern
	$marked = "SELECT marked FROM `einkaufsliste` WHERE `id`= '".$id."'";
		//Um dann hier dementsprechend den Wert zu Verändern
		if ($marked == 1){
			$sql = 	"UPDATE `einkaufsliste` SET `marked` = '0' WHERE `einkaufsliste`.`id` = '".$id."'";
			$this->loggeSql($sql);
			$this->dbh->query($sql);
		}	else
		{
			$sql = 	"UPDATE `einkaufsliste` SET `marked` = '1' WHERE `einkaufsliste`.`id` = '".$id."'";
			$this->loggeSql($sql);
			$this->dbh->query($sql);
		}
		}
	

    private function loggeSql($sql) {
		$fh = fopen("log.txt", "a");
		fputs($fh, "--- " . date("Y-m-d H:i:s") . "\n");
		fputs($fh, $sql . "\n");
		fclose($fh);
	}

}