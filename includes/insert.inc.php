<!-- 

     James Doyle
     Started 24/7/2021
     Finished 27/7/2021
     All code here is developed and created by James Doyle
     Contact: jamesmddoyle@gmail.com
              github.com/jmmd2000

-->
<?php

include_once 'dbh.inc.php';

$mN = $_REQUEST['mName'];
$oS = $_REQUEST['oScore'];
$tS = $_REQUEST['tScore'];
$cK = $_REQUEST['ckills'];
$cD = $_REQUEST['cdeaths'];
$jK = $_REQUEST['jkills'];
$jD = $_REQUEST['jdeaths'];
$l = $_REQUEST['left'];


$sql = "INSERT INTO `rounds`(`ID`, `mapName`, `ourScore`, `theirScore`, `cKills`, `cDeaths`, `jKills`, `jDeaths`, `leave`) VALUES ('','$mN', '$oS', '$tS', '$cK', '$cD', '$jK', '$jD', '$l')";
mysqli_query($conn, $sql);

header("Location: ../index.php");