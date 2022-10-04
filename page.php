<?php
// loen sisse konfiguratsioonifaili
    require_once "../../config.php";
	//echo $server_user_name
//echo $stmt naitab errorit
    $author_name = "Vanessa Kalavus";
	//echo $author_name;
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	//echo $weekday_names_et[2];
	$weekday_now = date ("N");
	$hour_now = date("H");
	$part_of_day = "suvaline hetk";
	//  == on võrdne  != pole võrdne  <   >  <=  >=
	if($weekday_now <= 5){
		if($hour_now < 7 or $hour_now >= 23){
			$part_of_day = "uneaeg";
		}
		if($hour_now == 7){
			$part_of_day = "hommikune virgumise aeg";
		}
		//   and   or
		if($hour_now >= 8 and $hour_now < 18){
			$part_of_day = "koolipäev";
		}
		if($hour_now >= 18 and $hour_now < 21){
			$part_of_day = "tööaeg";
		}
	}
	if($weekday_now == 6){
		if($hour_now < 8){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 8 and $hour_now < 23){
			$part_of_day = "tööaeg";
		}
		if($hour_now >= 23){
			$part_of_day = "õhtused toimetused";
		}
	}
	if($weekday_now == 7){
		if($hour_now < 9){
			$part_of_day = "uneaeg";
		}
		if($hour_now >= 9 and $hour_now < 19){
			$part_of_day = "koristusaeg";
		}
		if($hour_now >= 19){
			$part_of_day = "vaba aeg";
		}
	}

	



	
	//vaatame semestri pikkust ja kulgemist
	$semester_begin = new dateTime("2022-09-05");
	$semester_end = new dateTime("2022-12-18");
	$semester_duration = $semester_begin->diff($semester_end);
	//echo $semester_duration;
	$semester_duration_days = $semester_duration->format("%r%a");
	$from_semester_begin = $semester_begin->diff(new DateTime("now"));
	$from_semester_begin_days = $from_semester_begin->format("%r%a");
	
	//loendan massiivi
	//echo count($weekday_names_et);
	//juhuslik arv
	//echo mt_rand(1, 9);
	//juhuslik element massiivist
	//echo $weekday_names_et[mt_rand(0, count($weekday_names_et) -1)];
	$old_wisdom_list = ["Hommik on õhtust targem.", "Amet ei küsi leiba.", "Kus viga näed laita, seal tule ja aita.", "Ilu nõuab ohvreid.", "haara härga sarvest, meest sõnast", "Kes teisele auku kaevab selle käes on labidas", "Targem annab järele.", "Kes kannatab see kaua elab.","julge hundi rind on karvane", "enne töö,siis lõbu"];
	//$random_wisdom = $old_wisdom_list[mt_rand(0, count($old_wisdom_list) - 1)];
	
	//loeme fotode kataloogi sisu
	$photo_dir = "photos/";
	//$all_files = scandir($photo_dir);
	//uus massiiv = array_slice(massiiv, mis kohast alates)
	$all_files = array_slice(scandir($photo_dir),2);
	//var_dump($all_files);
	
	//   <img src="kataloog/fail" alt="tekst">
	$photo_html = null;
	
	//tsükkel
	// muutuja väärtuse suurendamine $muutuja =$muutuja + 5
	//$muutuja += 5
	//kui suureneb 1 võrra $muutuja ++
	// on ka -=  --
	//näide
	/*for($i = 0;$i < count($all_files); $i ++){
		echo $all_files[$i]."\n";
	}*/
	/*{
	echo $file_name ." / ";
	}*/
	
	
	//loetlen lubatud failitüübid (jpg png)
	//   MINE
	$allowed_photo_types = ["image/jpeg", "image/png"];
	$photo_files =[];
	foreach($all_files as $file_name){
		$file_info = getimagesize($photo_dir .$file_name);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file_name);
			}
		}
	}
	//var_dump($photo_files);
	$photo_html = '<img src="' .$photo_dir .$photo_files[mt_rand(0,count($all_files) -1)]. '" alt="Tallinna pilt">';
	//vormi info kasutamine
	//$post
	//teen fotode rippmenüü
	//   <option value"0"tln.1.jpg</option>
	$select_html = '<option value="" selected disabled>Vali pilt</option>';
	for($i = 0;$i < count($photo_files); $i ++){
		$select_html .='<option value="' .$i .'">';
		$select_html .=$photo_files[$i];
		$select_html .="</option> \n";
	}
	if(isset($_POST["photo_select"]) and $_POST["photo_select"] >= 0){
		//kõik
	}
	
	$adjective_html=null;
	//eituseks hyyumark
	if(isset($_POST["todays_adjective_input"]) and !empty($_POST["todays_adjective_input"])){
	$adjective_html = "<p>Tänase kohta on arvatud: ". $_POST["todays_adjective_input"].
	".</p>";
	}
	$comment_error= null;
	$grade = 7;
	//tegeleme päevale antud hinde ja kommentaariga
	//var_dump($_POST);
	if(isset($_POST["comment_submit"])){
		if(isset($_POST["comment_inpit"]) and !empty($_POST["comment_unput"])){
		$comment = $_POST["comment_input"];
	}	else {
			$comment_error="Kommentaar on lisamata.";
	}
		$grade = $_POST["grade_input"];
		
		if(empty($comment_error)){
		
			//loome andmebaasi ühenduse
			//määrame suhtlemisel kasutatava kooditabeli
			$conn->set_charset("utf8");
			//valmistame ette SQL keeles päringu
			$stmt = $conn->prepare("INSERT INTO vp_daycomment (comment, grade) VALUES(?,?)");
			echo $conn->error;
			//seome SQL päringu päris andmetega i- täisarv, d- murdarev, s- tekst
			$stmt->bind_param("si",$comment, $grade);
			//täidame käsu
			if($stmt->execute()){
				$grade = 7;
				};
			echo $stmt->error;
			//sulgeme käsu
			$stmt->close();
			//sulgeme andmebaasühenduse
			$conn->close();
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrammeerimine</title>
</head>
<body>
    <img src="pics/vp_banner_gs.png" alt="banner">
	<h1><?php echo $author_name; ?>, veebiprogrammeerimine</h1>
	 <p>See leht on loodud õppetöö raames ja ei sisalda tõsist infot!</p>
	 <p>õppetöö toimus <a href="https://www.tlu.ee">Tallinna Ülikoolis</a>Digitehnoloogiate instituudis.
	 </p>
	 
	 <p>Praegu on <?php echo $part_of_day; ?>.</p>
	 <p>Semester edeneb: <?php echo $from_semester_begin_days ."/".
	 $semester_duration_days; ?></p>
	 <p> Leht on loodud esimesel õppeaastal ja on pidevas arenemises. Varem õppis veebilehe autor Kardioru Saksa Gümnaasiumis, loodusainete erialal. <p> 
	 
	 <a href="hhtps://www.tlu.ee">
	     <img src="pics/tlu_37.jpg" alt="Tallinna Ülikooli Astra Õppehoone">
		 </a>
 <!--siin on väike omadussõnade vorm-->
 <form method="POST">
      <label for="comment_input">Kommentaar tänase päeva kohta:</label>
	  <br>
	  <textarea id= "comment_input" name="comment_input" cols="70" rows"2" placeholder="kommentaar"></textarea>
	  <br>
	  <label for="grade_input">Hinne tänasele päevale(0...10):</label>
	  <input type = "number" id="grade_input" name="grade_input" min="0" max="10" step="1" value="<?php echo $grade; ?>">
      <br>
      <input type ="submit" id= "comment_submit" name="comment_submit" value= "salvesta">  
	 
	 </form>
	 <hr>
	  
	  
 <form method="POST">
      <input type="text" id="todays_adjective_input" name="todays_adjective_input"
	  placeholder="omadussõna tänase kohta">
	  <input type="submit" id="todays_adjective_input"name="todays_adjective_submit"
	  value="saada omadussõna">
      </form>
 <p>Omadussõna tänase kohta: sisestamata</p>
 <?php echo $adjective_html; ?>
 <hr>
 <form method="POST">
       <select id="photo_select" name="photo_select">    
	           <option value="0">tln_1.JPG</option> 
<option value="1">tln_1.JPG</option> 
<option value="2">tln_106.JPG</option> 
<option value="3">tln_13.JPG</option> 
<option value="4">tln_154.JPG</option> 
<option value="5">tln_170.JPG</option> 
<option value="6">tln_181.JPG</option> 
<option value="7">tln_62.JPG</option> 

	   </select>
       <input type="submit" id="photo_submit" name= "photo_submit"          value="OK">
	   <span> <?php echo $comment_error; ?>
 </form>
 <p>Väike tarkusetera: <?php echo $old_wisdom_list[mt_rand(0, count($old_wisdom_list) - 1)]; ?>
 <hr>
 <?php echo $photo_html; ?>
 <hr>
</body>

</html>