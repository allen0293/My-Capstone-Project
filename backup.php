<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database_name = "cooperativedb";

// Get connection object and set the charset
$conn = mysqli_connect($host, $username, $password, $database_name);
$conn->set_charset("utf8");


if(isset($_POST['export']))
{
$dir = dirname(__FILE__);
$dir = $dir;
$filename = "backup" . date("YmdHis") . ".sql.gz";

$db_host = "host";
$db_username = "username";
$db_password = "password";
$db_database = "database";

$cmd = "mysqldump -h {ost} -u {$username} --password={$password} {$database} | gzip > {$dir}{$filename}";
exec($cmd);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$filename\"");

passthru("cat {$dir}{$filename}");

    if(!$command){
        echo"not exported";
    }else{
        echo "exported";
    }

}

function IMPORT_TABLES($host,$user,$pass,$dbname, $sql_file_OR_content){
    set_time_limit(3000);
    $SQL_CONTENT = (strlen($sql_file_OR_content) > 300 ?  $sql_file_OR_content : file_get_contents($sql_file_OR_content)  );  
    $allLines = explode("\n",$SQL_CONTENT); 
    $mysqli = new mysqli($host, $user, $pass, $dbname); if (mysqli_connect_errno()){echo "Failed to connect to MySQL: " . mysqli_connect_error();} 
    $zzzzzz = $mysqli->query('SET foreign_key_checks = 0');         
    preg_match_all("/\nCREATE TABLE(.*?)\`(.*?)\`/si", "\n". $SQL_CONTENT, $target_tables); foreach ($target_tables[2] as $table){$mysqli->query('DROP TABLE IF EXISTS '.$table);}         $zzzzzz = $mysqli->query('SET foreign_key_checks = 1');    $mysqli->query("SET NAMES 'utf8'");   
    $templine = ''; // Temporary variable, used to store current query
    foreach ($allLines as $line)    {                                           // Loop through each line
        if (substr($line, 0, 2) != '--' && $line != '') {$templine .= $line;    // (if it is not a comment..) Add this line to the current segment
            if (substr(trim($line), -1, 1) == ';') {        // If it has a semicolon at the end, it's the end of the query
                if(!$mysqli->query($templine)){ print('Error performing query \'<strong>' . $templine . '\': ' . $mysqli->error . '<br /><br />');  }  $templine = ''; // set variable to empty, to start picking up the lines after ";"
            }
        }
    }   return 'Importing finished. Now, Delete the import file.';
} 

if(isset($_POST['restore']))
{       
    $file = $_POST['fileToUpload'];
    if(!$file){
        echo'<script>alert("Nodata imported")</script>';
        return;
    }else{
        
        IMPORT_TABLES("localhost","root","","cooperativedb", "$file"); 
        echo'<script>alert("data imported with successs")</script>';
    }
}
?>