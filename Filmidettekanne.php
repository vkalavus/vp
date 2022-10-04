<?php
require_once "../config.php";

	$conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	$conn->set_charset("utf8");

	$stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, lavastaja, zanr, tootja added FROM film");
	$stmt->bind_result($pealkiri_from_db, $aasta_from_db, $kestus_from_db, $lavastaja_from_db, $zanr_from_db, $tootja_from_db);
	$stmt->execute();
	$films_html = null;
	while($stmt->fetch()){
		$films_html .= "<h3>" .$pealkiri_from_db ."</h3>" ."<ul><li> Valmimisaasta: " .$aasta_from_db ."</li><li> Kestus: " .$kestus_from_db ." minutit.</li><li> Žanr: " .$zanr_from_db . "</li><li> Tootja: " .$tootja_from_db ."</li><li> Lavastaja: " .$lavastaja_from_db . "</li></ul>";
	}
?>

<!DOCTYPE html>
<html lang="et">

<head>
	<meta charset="utf-8">
	<title>Filmid</title>
</head>

<body>
<h1>FILMID</h1>

<?php echo $films_html; ?>
</body>

</html>