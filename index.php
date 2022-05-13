<?php
// Datenbank Verbindungsparameter einlesen
include("includes/config.inc.php");
include("classes/Shoppinglist.php");

/*
	Hier können Sie die Verarbeitung der $_GET oder $_POST Variablen durchführen
	und auf die verschiedenen Aktionen reagieren.
	Auch das Lesen und Schreiben der Werte in die Datenbank-Tabelle kann hier oben stehen.
	Das Behandeln einer Aktion startet i.d.R. mit einer if-Abfrage, um festzustellen, was geklickt wurde.
	
	Sie benötigen folgende Aktionen:
	
	Lesen der Waren aus der Datenbank (wenn bereits Waren vorhanden sind). x
	Hinzufügen einer neuen Ware (wenn Formular ausgefüllt). x
	Markieren einer Ware (wenn Link "markieren" geklickt).
	Löschen  einer Ware (wenn Link "löschen" geklickt). x

*/

//Verbindung herstellen
$db = new Shoppinglist($DBHOST, $DBUSER, $DBPASS, $DBNAME);
$verbindung = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);

//Verbindung testen, Fehler werfen wenn nicht verbunden, ansonsten melden das die Verbindung steht
if(!$db && !$verbindung){
  die("Konnte keine Verbindung zur Datenbank aufbauen.");
} else {
  echo "Verbindung zur Datenbank hergestellt";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ISP - Gerüst der Einsendeaufgabe 3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="./css/main.css" />
</head>
<body>
  <header>
    <h2>EA3 - Einkaufsliste</h2>
  </header>
  <main>
    <ul id="todolist">
<?php
	/*
		Die beiden folgenden Listeneinträge <li></li> sind als Beispiel hier eingefügt.
		Sie müssen eine Schleife bauen, die alle vorhandenen Waren schreibt.
		In der Schleife ist dann nur ein Listeneintrag, den anderen können Sie löschen.
		Bei jedem Listeneintrag ist dann die ID der Ware anzugeben.
		Außerdem muss die Menge und der Name der Ware in den <span>-Tags ausgegeben werden.		
	*/
	// Schleife Beginn mit Bedingung einfügen
  
  //***Neue Artikel hinzufügen
   //Prüfen ob Daten eingegeben wurden. Wenn ja, neues element in der DB anlegen
   if(isset($_POST) && !empty($_POST)) {
   //Die eingaben in "Menge" und "Ware" eigenen Variablen zuordnen
   $Menge = $_POST["Menge"];
   $Artikel = $_POST["Ware"];
   //Funktion zum einfügen von Artikel aufrufen und Variablen übergeben
   $stmt = $db->insertArtikel($Artikel, $Menge);
   }
  
   //***Artikel Löschen
    //Prüfen ob gelöscht werden soll und wenn ja, welcher Artikel
    if(isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
    $id = $_GET["id"];
      //Löschen funktioniert leider nur, wenn ich die logik hier habe und nicht in der Klasse Shoppinglist
    $sql = "DELETE FROM einkaufsliste WHERE id='".$id."'"; 
		$verbindung->query($sql); 
    //der aufruf für die klasse Shoppinglist der leider nicht klappt.
    //$stmt = $db->deleteArtikelById($id);
    }

   //***Artikel Markieren
    //Prüfen ob markiert werden soll und wenn ja, welcher Artikel
   if(isset($_GET["action"]) && $_GET["action"] == "mark" && isset($_GET["id"])) {
    //die id in eigener Variable speichern
    $id = intval($_GET["id"]);
    //ein sql statement vorbereiten und in eine variable speichern, $id übergebn
	  $sql="select marked from messdaten where id=".$id;
    //dieses statement an die db übergeben
    $marked = $verbindung->query($sql);
    //var_dump($marked); 
    //den Wert für marked ändern
    if ($marked == false){
    $sql = 	"UPDATE `einkaufsliste` SET `marked` = 'true' WHERE `einkaufsliste`.`id` = '".$id."'";
  	$verbindung->query($sql); 
    }	if ($marked == true){
    $sql = 	"UPDATE `einkaufsliste` SET `marked` = 'false' WHERE `einkaufsliste`.`id` = '".$id."'";
    $verbindung->query($sql); 
    } 
    } 


  //Tabelle aufrufen
  $sql = "SELECT * FROM einkaufsliste";
  $res = $verbindung->query($sql);

  //Wenn Verbindung steht, mit einer while schleife durch die Tabelle
  if($res-> num_rows > 0){
  while($i = $res->fetch_assoc()){
 
?>
      <li>
        <a href="index.php?action=mark&id=<?php echo $i["id"]; ?>"class="done <?php if($i["marked"] == 1) echo "checked"; ?>" title="Ware als eingekauft markieren"></a>
        <span><?php echo $i["anzahl"];?></span>
        <span><?php echo $i["ware"];?></span>
        <a href="index.php?action=delete&id=<?php echo $i["id"]; ?>" class="delete" title="Ware aus Liste löschen">löschen</a>
      </li>
<?php
	// Schleife Ende
}
}
?>
    </ul>
    <div class="spacer"></div>
    <form id="add-todo" action="index.php" method="post">
      <input type="text" placeholder="Menge" name="Menge">
      <input type="text" placeholder="Text für neue Ware" name="Ware">
      <input type="submit" value="hinzufügen">
    </form>
  </main>
  <footer>
    <p>Müller, Hendrik - Hochschule Emden Leer</p>
  </footer>
</body>
</html>
