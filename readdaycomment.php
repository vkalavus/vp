<?php
   require_once "../config.php";
   
    //loome andmebaasiühenduse
    $conn = new mysqli($server_host, $server_user_name, $server_password, $database);
	//määrame suhtlemisel kasutatava kooditabeli
	$conn->set_charset("utf8");
	//valmistame ette SQL keeles päringu
	$stmt = $conn->prepare("SELECT comment, grade, added FROM vp_daycomment");
	echo $conn->error;
	//seome loetavad andmed muutujaga
	$stmt->bind_result($comment_from_db, $grade_from_db, $added_from_db);
	//täidame käsu
	$stmt->execute();
	echo $stmt->error;
	//võtand andmed
	//kui on oodata vaid 1 võimalik kirje
	//if{$stmt->fetch();{
		//kõik ida teha
	//{
	$comments_html = null;
	//kui on oodata mitut, aga teadmata arv
	while($stmt->fetch()){
		// <p>komentaar, hinne päevale :x, lisatud YYY.</p>
		$comments_html .="<p>".$comment_from_db .", hinne päevale : ".$grade_from_db ."lisatud". $added_from_db .".</p> \n";
	}
	//sulgeme käsu
	$stmt->close();
	//sulgeme andmebaasühenduse
	$conn->close();
	
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>Vanessa Kalavus, veebiprogrammeerimine</title>
</head>
<body>
    <img src="pics/vp_banner_gs.png" alt="banner">
	<h1>Vanessa Kalavus, veebiprogrammeerimine</h1>
	 <p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot!</p>
	 <p>õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>
	 Digitehnoloogiate instituudis.</p>
	 <?php echo $comments_html; ?>
</body>
</html>