
<?php
$zip = new ZipArchive();
#Only NARUTO get the latest chapters
$data=file_get_contents('https://www.mangaeden.com/api/manga/4e70ea03c092255ef70046f0/');
$data1=json_decode($data,true);
$a=[];
foreach ($data1["chapters"] as $key => $c) {
	# code...
	$a[]=[
	'a'=>$c[0],
	'b'=>$c[1],
	'c'=>$c[2],
	'd'=>$c[3]
	];

}
$zips = '/var/www/html/naruto/'.$a[0]['a'].'.zip';

$z = file_get_contents('https://www.mangaeden.com/api/chapter/'.$a[0]['d']);
$z1= json_decode($z,true);
$w = 0;
$q =[];
foreach ($z1["images"] as $key => $val) {
	$q[]=[
	'img' =>	'https://cdn.mangaeden.com/mangasimg/'.$z1["images"][$w][1]
	];
	$w++;
}
$i = 1;
foreach ($q as $v) {
	$source = $v["img"];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $source);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	$data = curl_exec ($ch);
	$error = curl_error($ch); 
	curl_close ($ch);

	$destination = "./naruto/".$i.".jpg";
	$file = fopen($destination, "w+");
	fputs($file, $data);
	fclose($file);
	
	$zip->open($zips, ZipArchive::CREATE);
	$zip->addFile("./naruto/".$i.".jpg", $i.".jpg");
	$zip->close();
	$i++;
}

?>
