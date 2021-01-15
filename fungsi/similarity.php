<!DOCTYPE html>
<html lang="en">

  <head>

   <?php
        include "include/head.php";
    ?>
 
  </head>

  <body>
  	<style>
img {
    float: left;
}
</style>
<?php 

function preproses($teks) {
	include "koneksi.php";

	$teks = str_replace("'", " ", $teks);
	$teks = str_replace("-", " ", $teks);
	$teks = str_replace(")", " ", $teks);
	$teks = str_replace("(", " ", $teks);
	$teks = str_replace("\"", " ", $teks);
	$teks = str_replace("/", " ", $teks);
	$teks = str_replace("=", " ", $teks);
	$teks = str_replace(".", " ", $teks);
	$teks = str_replace(",", " ", $teks);
	$teks = str_replace(":", " ", $teks);
	$teks = str_replace(";", " ", $teks);
	$teks = str_replace("!", " ", $teks);
	$teks = str_replace("?", " ", $teks);
	$teks = str_replace("–", " ", $teks);
	$teks = str_replace("<", " ", $teks);
	$teks = str_replace(">", " ", $teks);
			
	$teks = strtolower(trim($teks));
			
	
	$restem = mysqli_query($con, "SELECT * FROM tbstem ORDER BY Id");

	while($rowstem = mysqli_fetch_array($restem)) {  			
  		$teks = str_replace($rowstem['Term'], $rowstem['Stem'], $teks);
  	}			 	
			

     $astoplist = array ("yang", "apapun", "antara", "juga", "rupa", "terhadap", "dari", "kami", "kamu", "ini", "sedang", "itu", "itulah", "jika", "akan", "karena", "atau", "dan", "tersebut", "pada", "dengan", "adalah", "yaitu", "mereka", "dapat", "untuk", "oleh", "lakukan", "aku", "bagaimana", "dalam");				
				

	foreach ($astoplist as $i => $value) {
   	$teks = str_replace($astoplist[$i], " ", $teks);
	}			 	
		
	$teks = strtolower(trim($teks));
	return $teks;
} 

function hitungsim($query) {
	include "koneksi.php";

	$resn = mysqli_query($con,"SELECT Count(*) as n FROM tbvektor");
	$rown = mysqli_fetch_array($resn);	
	$n = $rown['n']; //5
	
	$aquery = explode(" ", $query);
	$panjangQuery = 0;
	$aBobotQuery = array();
	
		for ($i=0; $i<count($aquery); $i++) {

			$resNTerm = mysqli_query($con,"SELECT Count(*) as N from tbindex WHERE Term = '$aquery[$i]'");
			$rowNTerm = mysqli_fetch_array($resNTerm);	
			$NTerm = $rowNTerm['N'] ;
			
			$idf = log10($n/$NTerm);
				
			$aBobotQuery[] = $idf;
			
			$panjangQuery = $panjangQuery + $idf * $idf;		
		}
		
	$panjangQuery = sqrt($panjangQuery);
	
	$jumlahmirip = 0;
	
	$resDocId = mysqli_query($con,"SELECT * FROM tbvektor ORDER BY DocId");
	while ($rowDocId = mysqli_fetch_array($resDocId)) {
	
		$dotproduct = 0;
			
		$docId = $rowDocId['DocId'];
		$panjangDocId = $rowDocId['Panjang'];
		
		$resTerm = mysqli_query($con,"SELECT * FROM tbindex WHERE DocId = $docId");
		while ($rowTerm = mysqli_fetch_array($resTerm)) {
			for ($i=0; $i<count($aquery); $i++) {
				
				if ($rowTerm['Term'] == $aquery[$i]) {
					$dotproduct = $dotproduct + $rowTerm['Bobot'] * $aBobotQuery[$i];				
				} 
			} 		
		} 
		
		if ($dotproduct > 0) {
			$sim = $dotproduct / ($panjangQuery * $panjangDocId);	
			
			$resInsertCache = mysqli_query($con,"INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', $docId, $sim)");
			$jumlahmirip++;
		} 
		
	} 

	if ($jumlahmirip == 0) {
		$resInsertCache = mysqli_query($con,"INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', 0, 0)");
	}	
		
} 
//--------------------------------------------------------------------------------------------
function ambilcache($keyword) {
	include "koneksi.php";
	$resCache = mysqli_query($con,"SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC");
	$num_rows = mysqli_num_rows($resCache);
		
	if ($num_rows >0) {
		while ($rowCache = mysqli_fetch_array($resCache)) {
			$docId = $rowCache['DocId'];
			$sim = $rowCache['Value'];
					
			if ($docId != 0) {					
				$resBerita = mysqli_query($con,"SELECT * FROM tbberita WHERE Id = $docId");
				$rowBerita = mysqli_fetch_array($resBerita);
					
				$judul = $rowBerita['Judul'];
				$berita = $rowBerita['Berita'];
					
				print("<img src='img/pdf.png'><font size=4px color=blue><b>" . $judul . "</b></font><br />"); 
				print(" <font size=3px> ".$berita."</font> <br> ");
				print("<small class='text-muted'><font size=2px color=red> Nilai similarity (" . $sim . ") </font> | <font size=2px color=red><a href='abstrak.php'>abstract</a> <br><br></font></small>  ");	
				
				echo "<br><br>";	
			} else {
				print("<b>Data tidak ditemukan... </b><hr />");
			}
		}
	}
	else {
		hitungsim($keyword);
		
	$resCache = mysqli_query($con,"SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC");
	$num_rows = mysqli_num_rows($resCache);
		
		while ($rowCache = mysqli_fetch_array($resCache)) {
			$docId = $rowCache['DocId'];
			$sim = $rowCache['Value'];
					
			if ($docId != 0) {					
				$resBerita = mysqli_query($con,"SELECT * FROM tbberita WHERE Id = $docId");
				$rowBerita = mysqli_fetch_array($resBerita);
					
				$judul = $rowBerita['Judul'];
				$berita = $rowBerita['Berita'];
					
				print("<img src='img/pdf.png'><font size=4px color=blue><b>" . $judul . "</b></font><br />"); 
				print(" <font size=3px> ".$berita."</font> <br> ");
				print("<small class='text-muted'><font size=2px color=red> Nilai similarity (" . $sim . ") </font> | <font size=2px color=red><a href='abstrak.php'>abstract</a> <br><br></font></small>  ");	
				
				echo "<br><br>";		
			} else {
				print("<b>Data tidak ditemukan... </b><hr />");
			}
		} 
	}
} 
?>
</body>
</html>