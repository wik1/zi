<?php
error_reporting (E_ALL ^ E_NOTICE);


$json='';

$host = '194.146.128.220/10333:demo';
$username = 'sysdba';
$password = 'sysmasu';

$db = ibase_connect($host, $username, $password, 'WIN1250') or die ("error in db connect");


$sql_c = 'SELECT count(*) FROM gm_asort a WHERE a.is_hiden=0';

$sql = 'SELECT kod, nazwa, indeks
        FROM gm_asort a
         WHERE
            a.is_hiden=0
        ORDER BY  nazwa  ASC';


$rs = ibase_query($db, $sql_c);  //count
$cc = ibase_fetch_row($rs)[0];	//count value $cc[0]
ibase_free_result($rs);


$rs = ibase_query($db, $sql);  //value list
$json = "[";

//$i<$cc 
for ($i=0; $i<$cc; $i++) {
	$tmp='';
	$row = ibase_fetch_row($rs);
	$json .= '{"id":"'. $i .'",';
	$json .= '"kod":"'. $row[0] .'",';
		$tmp_j = str_replace('"','\"',$row[1]);
	$json .= '"nazwa":"'. iconv('Windows-1250//TRANSLIT','UTF-8',$tmp_j) .'",';
		$tmp_j = str_replace('"','\"',$row[2]);
	$json .= '"indeks":"'. iconv('Windows-1250//TRANSLIT','UTF-8',$tmp_j) .'"}';
	if ($i<$cc-1) {
		$json .= ",\n";
	}

}
$json .= "]";

//echo $json;

ibase_free_result($rs);
ibase_close($db);

//zapis do pliku
$myfile = fopen("./cache/db_data.json", "w") or die("Unable to open file!");
fwrite($myfile, $json);
fclose($myfile);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Unifirma DX testing</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <script src="./dx/js/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./dx/css/dx.spa.css" />
    <link rel="stylesheet" type="text/css" href="./dx/css/dx.common.css" />
    <link rel="dx-theme" data-theme="generic.light" href="./dx/css/dx.light.css" />
    <script src="./dx/js/jszip.min.js"></script>
    <script src="./dx/js/dx.all.js"></script>
    <link rel="stylesheet" type ="text/css" href ="styles.css" />
    <script src="index.js"></script>
</head>

<body class="dx-viewport">
    <div class="demo-container">
        <div id="grid-container"></div>
        <div class="options">
            <div>
                <div id="column-lines"></div>
            </div>
            <div>
                <div id="row-lines"></div>
            </div>
            <div>
                <div id="show-borders"></div>
            </div>
            <div>
                <div id="row-alternation"></div>
            </div>
        </div>
    </div>
<br>
</body>
</html>
