<!-- 

     James Doyle
     Started 24/7/2021
     Finished 27/7/2021
     All code here is developed and created by James Doyle
     Contact: jamesmddoyle@gmail.com
              github.com/jmmd2000

     Code for table sorting (sort-table.js) retrieved from here: https://github.com/stationer/SortTable

-->




<?php
include_once 'includes/dbh.inc.php';
?>
<!DOCTYPE html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="includes/pw.js"></script>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/ico" href="assets/icon.ico">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<script src="sort-table.js"></script>

<html>
    <head>
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>
        <title>Gunfight Scoresheet</title>
</head>
<body>

<!-- 3 button navbar at the top -->
<ul id="navbar">
  <li id="homeButton" class="active">Home</li>
  <li id="playersButton">Players</li>
  <li id="mapsButton">Maps</li>
</ul>

<!-- Home screen wih table of results -->
<div id="home">

<div id="teamStats">
<table id="statTable">
  <tr>
    <td>Wins</td>
    <td>Losses</td>
    <td>Win/Loss</td>
    <td>Win %</td>
    <td>Camerons K/D</td>
    <td>Jamies K/D</td>
    <td>Longest Winstreak</td>
    <td>Current Winstreak</td>
    <td># of 6-0s</td>
  </tr>
  <tr>
    <td id="wins"></td>
    <td id="losses"></td>
    <td id="wlRatio"></td>
    <td id="winPercentage"></td>
    <td id="camKD"></td>
    <td id="jamKD"></td>
    <td id="longestStreak"></td>
    <td id="currentStreak"></td>
    <td id="sixOhs"></td>
  </tr>
</table>
</div>

<!-- Start of form -->
<form id="newGame">

<h2>Insert New Data</h2>
<!-- Map choice -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Map Name</label>
  <div class="col-md-4">
  <select name="mName" id="maps">
  <option selected="selected">Choose Map</option>
  <option value="Diesel">Diesel</option>
  <option value="Nuketown 84">Nuketown 84</option>
  <option value="Gameshow">Gameshow</option>
  <option value="ICBM">ICBM</option>
  <option value="U-Bahn">U-Bahn</option>
  <option value="Amsterdam">Amsterdam</option>
  <option value="KGB">KGB</option>
  <option value="Mansion">Mansion</option>
</select>
  </div>
</div>

<!-- Our score -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Our Team Score</label>
  <div class="col-md-4">
  <select name="oScore" id="oscore">
  <option selected="selected">Our Score</option>
  <option value="0">0</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
</select>
  </div>
</div>

<!-- Enemy score -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">Enemy Team Score</label>
  <div class="col-md-4">
  <select name="tScore" id="tscore">
  <option selected="selected">Enemy Score</option>
  <option value="0">0</option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
</select>
  </div>
</div>

<!-- Camerons kill count -->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Camerons Kills</label>  
  <div class="col-md-4">
  <input type="text" name="ckills"> 
  </div>
</div>

<!-- Camerons death count -->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Camerons Deaths</label>  
  <div class="col-md-4">
  <input type="text" name="cdeaths">
  </div>
</div>

<!-- My kill count -->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Jamies Kills</label>  
  <div class="col-md-4">
  <input type="text" name="jkills">
  </div>
</div>

<!-- My death count -->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Jamies Deaths</label>  
  <div class="col-md-4">
  <input type="text" name="jdeaths"> 
  </div>
</div>

<!-- How many enemies left the game? -->
<div class="form-group">
  <label class="col-md-4 control-label" for="selectbasic">How many enemies left the game?</label>
  <div class="col-md-4">
  <select name="left" id="left">
  <option selected="selected">How many left?</option>
  <option value="0">0</option>
  <option value="1">1</option>
  <option value="2">2</option>
</select>
  </div>
</div>

<!-- Submit button -->
<button type="submit" name="submit" formaction="includes/insert.inc.php">Submit</button>
<button id="closeNewGame">Close</button>

</form>

<?php

    // Retreives all the data
    $sql = "SELECT * FROM rounds";
    $result = mysqli_query($conn, $sql);
    
    // Echo the start of the table
    echo "<table id='mainSticky'>";
    echo "<tr><td>Map Name</td><td>Our Score</td><td>Their Score</td><td>Camerons Kills</td><td>Camerons Deaths</td><td>Jamies Kills</td><td>Jamies Deaths</td><td>How many left?</td>";
    echo "<table id='main'>";
    
    // While there are still rows available, add a new row to the table with the data
    while($row = mysqli_fetch_array($result)){   
echo "<tr><td>" . $row['mapName'] . "</td><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
}

echo "</table>"; //Close the table in HTML
 
// This gets the total number of won games from the database
$score = "SELECT * FROM rounds WHERE ourScore = 6";
$scoreQuery = mysqli_query($conn, $score);

$winCount = mysqli_num_rows($scoreQuery);

// This gets the amount of times we "Six Oh'ed" the enemy team
// (Won 6 rounds straight)
$sixo = "SELECT * FROM rounds WHERE ourScore = 6 AND theirScore = 0";
$resultSixo = mysqli_query($conn, $sixo);

$sixoCount = mysqli_num_rows($resultSixo);


// This gets the total number of rows in the database
$rowQuery = "SELECT COUNT(1) FROM rounds";
$rowCount = mysqli_query($conn, $rowQuery);
$numRows = mysqli_fetch_array($rowCount);

$total = $numRows[0];

// This finds the latest loss conceded to help determine the current winstreak
// This breaks the table when there are no losses recorded. **FIX**
$latestLossID = "SELECT * FROM   rounds WHERE  theirScore = 6 ORDER  BY ID DESC LIMIT  1;";
$latestRoundID = "SELECT * FROM rounds WHERE ID=(SELECT max(id) FROM rounds)";
$latestLossQuery = mysqli_query($conn, $latestLossID);
$latestRoundQuery = mysqli_query($conn, $latestRoundID);

while($row = mysqli_fetch_array($latestLossQuery)){ 
  $var1 = $row['ID'];
}

while($row1 = mysqli_fetch_array($latestRoundQuery)){ 
  $var2 = $row1['ID']; 
}


?>

<!-- Table at the bottom of the screen totalling the entire main table -->
<table id="totalTable">
  <tr>
    <td>Total</td>
    <td id="totalOWINS"></td>
    <td id="totalTWINS"></td>
    <td id="totalCKILLS"></td>
    <td id="totalCDEATHS"></td>
    <td id="totalJKILLS"></td>
    <td id="totalJDEATHS"></td>
    <td id="totalLEFT"></td>
  </tr>
</table>

<!-- Buttons to input the password to allow the user to add new data, and also the button that adds new data -->
<button id="newButton">Add New Data</button>
<button id="passButton">Enter Password</button>
<button id="scrollTop"><i class="fas fa-arrow-up"></i></button>
<button id="scrollBottom"><i class="fas fa-arrow-down"></i></button>

<!-- Box that the user enters the password into -->
<div id="passEntry">
  <h2 style="text-align:center">Enter password to allow data entry</h2>
    <input type="password" id="passwordInput">
    <input type="checkbox" onclick="showHide()" id="showHideBox"><label for="showHideBox">Show Password</label>
    <button id="enterPassButton" onclick="checkPassword()">Enter</button>
</div>

</div>











<!-- Player data tab-->
<div id="players">

<!-- The div that holds all of Camerons data -->
<div id="camDiv">
<img src="assets/av.png" class="avatar">
<h1>Cameron</h1>
<!-- Camerons main stats -->
<table id="camStats">
  <tr>
    <td>Total Kills</td>
    <td>Total Deaths</td>
    <td>K/D</td>
    <td>Most Kills</td>
    <td>Most Deaths</td>
    <td>Best Game K/D</td>
  </tr>
  <tr>
    <td id="totalCamKills">0</td>
    <td id="totalCamDeaths">0</td>
    <td id="camKDS">0</td>
    <td id="mostCamKills">0</td>
    <td id="mostCamDeaths">0</td>
    <td id="bestCamKD">0</td>
  </tr>
</table>
<!-- Camerons best and worst maps -->
<table id="camMapsBW">
  <tr>
    <td>&nbsp;&nbsp;Best Map&nbsp;&nbsp;</td>
    <td>Worst Map</td>
  </tr>
  <tr>
    <td id="camBestMap"><img src="assets/am.jpg" id="camBestMapImage" title=" "></td>
    <td id="camWorstMap"><img src="assets/nuke.jpg" id="camWorstMapImage" title=" "></td>
  </tr>
</table>

<!-- Camerons stats for each map -->
<table id="camMapStats" class="js-sort-table">
<tr>
    <th class="js-sort-string">Map Name</th>
    <th class="js-sort-number">Total Kills</th>
    <th class="js-sort-number">Total Deaths</th>
    <th class="js-sort-number">K/D</th>
  </tr>
  <tr>
    <td id="ANameCam">Amsterdam</td>
    <td id="AKillsCam"></td>
    <td id="ADeathsCam"></td>
    <td id="AKDCam"></td>
  </tr>
  <tr>
    <td id="DNameCam">Diesel</td>
    <td id="DKillsCam"></td>
    <td id="DDeathsCam"></td>
    <td id="DKDCam"></td>
  </tr>
  <tr>
    <td id="GNameCam">Gameshow</td>
    <td id="GKillsCam"></td>
    <td id="GDeathsCam"></td>
    <td id="GKDCam"></td>
  </tr>
  <tr>
    <td id="INameCam">ICBM</td>
    <td id="IKillsCam"></td>
    <td id="IDeathsCam"></td>
    <td id="IKDCam"></td>
  </tr>
  <tr>
    <td id="KNameCam">KGB</td>
    <td id="KKillsCam"></td>
    <td id="KDeathsCam"></td>
    <td id="KKDCam"></td>
  </tr>
  <tr>
    <td id="MNameCam">Mansion</td>
    <td id="MKillsCam"></td>
    <td id="MDeathsCam"></td>
    <td id="MKDCam"></td>
  </tr>
  <tr>
    <td id="NNameCam">Nuketown 84</td>
    <td id="NKillsCam"></td>
    <td id="NDeathsCam"></td>
    <td id="NKDCam"></td>
  </tr>
  <tr>
    <td id="UNameCam">U-Bahn</td>
    <td id="UKillsCam"></td>
    <td id="UDeathsCam"></td>
    <td id="UKDCam"></td>
  </tr>
</table>

</div>



<!-- The div that holds all of my data-->
<div id="jamDiv">

<img src="assets/av.png" class="avatar">

<h1>Jamie</h1>
<!-- My main stats -->
<table id="jamStats">
  <tr>
    <td >Total Kills</td>
    <td>Total Deaths</td>
    <td>K/D</td>
    <td>Most Kills</td>
    <td>Most Deaths</td>
    <td>Best Game K/D</td>
  </tr>
  <tr>
    <td id="totalJamKills">0</td>
    <td id="totalJamDeaths">0</td>
    <td id="jamKDS">0</td>
    <td id="mostJamKills">0</td>
    <td id="mostJamDeaths">0</td>
    <td id="bestJamKD">0</td>
  </tr>
</table>
<!-- My best and worst maps -->
<table id="jamMapsBW">
  <tr>
    <td>&nbsp;&nbsp;Best Map&nbsp;&nbsp;</td>
    <td>Worst Map</td>
  </tr>
  <tr>
    <td id="jamBestMap"><img src="assets/diesel.jpg" id="jamBestMapImage" title=" "></td>
    <td id="jamWorstMap"><img src="assets/icbm.jpg" id="jamWorstMapImage" title=" "></td>
  </tr>
</table>
<!-- My stats for each map -->
<table id="jamMapStats" class="js-sort-table">
<tr>
    <th class="js-sort-string">Map Name</th>
    <th class="js-sort-number">Total Kills</th>
    <th class="js-sort-number">Total Deaths</th>
    <th class="js-sort-number">K/D</th>
  </tr>
  <tr>
    <td id="ANameJam">Amsterdam</td>
    <td id="AKillsJam"></td>
    <td id="ADeathsJam"></td>
    <td id="AKDJam"></td>
  </tr>
  <tr>
    <td id="DNameJam">Diesel</td>
    <td id="DKillsJam"></td>
    <td id="DDeathsJam"></td>
    <td id="DKDJam"></td>
  </tr>
  <tr>
    <td id="GNameJam">Gameshow</td>
    <td id="GKillsJam"></td>
    <td id="GDeathsJam"></td>
    <td id="GKDJam"></td>
  </tr>
  <tr>
    <td id="INameJam">ICBM</td>
    <td id="IKillsJam"></td>
    <td id="IDeathsJam"></td>
    <td id="IKDJam"></td>
  </tr>
  <tr>
    <td id="KNameJam">KGB</td>
    <td id="KKillsJam"></td>
    <td id="KDeathsJam"></td>
    <td id="KKDJam"></td>
  </tr>
  <tr>
    <td id="MNameJam">Mansion</td>
    <td id="MKillsJam"></td>
    <td id="MDeathsJam"></td>
    <td id="MKDJam"></td>
  </tr>
  <tr>
    <td id="NNameJam">Nuketown 84</td>
    <td id="NKillsJam"></td>
    <td id="NDeathsJam"></td>
    <td id="NKDJam"></td>
  </tr>
  <tr>
    <td id="UNameJam">U-Bahn</td>
    <td id="UKillsJam"></td>
    <td id="UDeathsJam"></td>
    <td id="UKDJam"></td>
  </tr>
</table>

</div>

</div>


<?php

    // Get the specific data from each map
    $ams = "SELECT * FROM rounds WHERE mapName = 'Amsterdam'";
    $resultAms = mysqli_query($conn, $ams);

    $die = "SELECT * FROM rounds WHERE mapName = 'Diesel'";
    $dieResult = mysqli_query($conn, $die);

    $game = "SELECT * FROM rounds WHERE mapName = 'Gameshow'";
    $gameResult = mysqli_query($conn, $game);

    $icbm = "SELECT * FROM rounds WHERE mapName = 'ICBM'";
    $icbmResult = mysqli_query($conn, $icbm);

    $kgb = "SELECT * FROM rounds WHERE mapName = 'KGB'";
    $kgbResult = mysqli_query($conn, $kgb);

    $man = "SELECT * FROM rounds WHERE mapName = 'Mansion'";
    $manResult = mysqli_query($conn, $man);

    $nuke = "SELECT * FROM rounds WHERE mapName = 'Nuketown 84'";
    $nukeResult = mysqli_query($conn, $nuke);

    $u = "SELECT * FROM rounds WHERE mapName = 'U-Bahn'";
    $uResult = mysqli_query($conn, $u);

    
    // Echo the start of the maps section table
    echo "<div id='mapsSection'>";
    
    echo "<div id='amsterdamDiv' class='mapDiv'>";
    echo "<h2>Amsterdam</h2>";
    echo "<img src='assets/am.jpg'>";

    echo "<table id='amsterdamTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='amsterdamWins'></td><td id='amsterdamLosses'></td><td id='amsterdamWinPercentage'></td><td id='amsterdamCamKD'></td><td id='amsterdamJamKD'></td>";
    echo "</table>";

    echo "<table id='amsterdamTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($resultAms)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='dieselDiv' class='mapDiv'>";
    echo "<h2>Diesel</h2>";
    echo "<img src='assets/diesel.jpg'>";

    echo "<table id='dieselTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='dieselWins'></td><td id='dieselLosses'></td><td id='dieselWinPercentage'></td><td id='dieselCamKD'></td><td id='dieselJamKD'></td>";
    echo "</table>";

    echo "<table id='dieselTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($dieResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='gameshowDiv' class='mapDiv'>";
    echo "<h2>Gameshow</h2>";
    echo "<img src='assets/game.jpg'>";

    echo "<table id='gameTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='gameWins'></td><td id='gameLosses'></td><td id='gameWinPercentage'></td><td id='gameCamKD'></td><td id='gameJamKD'></td>";
    echo "</table>";

    echo "<table id='gameTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($gameResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='icbmDiv' class='mapDiv'>";
    echo "<h2>ICBM</h2>";
    echo "<img src='assets/icbm.jpg'>";

    echo "<table id='icbmTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win Percentage</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='icbmWins'></td><td id='icbmLosses'></td><td id='icbmWinPercentage'></td><td id='icbmCamKD'></td><td id='icbmJamKD'></td>";
    echo "</table>";

    echo "<table id='icbmTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($icbmResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='kgbDiv' class='mapDiv'>";
    echo "<h2>KGB</h2>";
    echo "<img src='assets/kgb.jpg'>";

    echo "<table id='kgbTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='kgbWins'></td><td id='kgbLosses'></td><td id='kgbWinPercentage'></td><td id='kgbCamKD'></td><td id='kgbJamKD'></td>";
    echo "</table>";

    echo "<table id='kgbTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($kgbResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='mansionDiv' class='mapDiv'>";
    echo "<h2>Mansion</h2>";
    echo "<img src='assets/mansion.jpg'>";

    echo "<table id='mansionTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='mansionWins'></td><td id='mansionLosses'></td><td id='mansionWinPercentage'></td><td id='mansionCamKD'></td><td id='mansionJamKD'></td>";
    echo "</table>";

    echo "<table id='manTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($manResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='nuketownDiv' class='mapDiv'>";
    echo "<h2>Nuketown 84</h2>";
    echo "<img src='assets/nuke.jpg'>";

    echo "<table id='nukeTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='nukeWins'></td><td id='nukeLosses'></td><td id='nukeWinPercentage'></td><td id='nukeCamKD'></td><td id='nukeJamKD'></td>";
    echo "</table>";

    echo "<table id='nukeTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($nukeResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "<div id='ubahnDiv' class='mapDiv'>";
    echo "<h2>U-Bahn</h2>";
    echo "<img src='assets/ubahn.jpg'>";

    echo "<table id='ubahnTableStats' class='mapTable1'>";
    echo "<tr><td>Wins</td><td>Losses</td><td>Win %</td><td>Cam K/D</td><td>Jam K/D</td>";
    echo "<tr><td id='ubahnWins'></td><td id='ubahnLosses'></td><td id='ubahnWinPercentage'></td><td id='ubahnCamKD'></td><td id='ubahnJamKD'></td>";
    echo "</table>";

    echo "<table id='uTable' class='mapTable'>";
    echo "<tr><th>Our Score</th><th>Their Score</th><th>Cams Kills</th><th>Cams Deaths</th><th>Jams Kills</th><th>Jams Deaths</th><th>How many left?</th>";
    
    while($row = mysqli_fetch_array($uResult)){
      echo "<tr><td>" . $row['ourScore'] . "</td><td>" . $row['theirScore'] . "</td><td>" . $row['cKills'] . "</td><td>" . $row['cDeaths'] . "</td><td>" . $row['jKills'] . "</td><td>" . $row['jDeaths'] . "</td><td>" . $row['leave'] . "</td></tr>";  //$row['index'] the index here is a field name
    }
    echo "</table>";
    echo "</div>";



    echo "</div>";

  ?>

</body>

<script>
    
    // All of the data gets stored in their respective arrays
    var ourSCORE = [];
    var theirSCORE = [];
    var cKILLS = [];
    var cDEATHS = [];
    var jKILLS = [];
    var jDEATHS = [];
    var enLEFT = [];

        var ourscore;
        var theirscore;
        var camkills;
        var camdeaths;
        var jamkills;
        var jamdeaths;
        var enemiesleft;
    
    // Access the main table and each column
    var table = $("#main");
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(1).text();
        theirscore = $tds.eq(2).text();
        camkills = $tds.eq(3).text();
        camdeaths = $tds.eq(4).text();
        jamkills = $tds.eq(5).text();
        jamdeaths = $tds.eq(6).text();
        enemiesleft = $tds.eq(7).text();

        // If its a win, colour the row green, otherwise, colour it red
        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
        }else if(theirscore == "6"){
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

        // If the data value is a number, add it to its array
        if(!isNaN(+ourscore)){
            ourSCORE.push(parseInt(ourscore));
        }

        if(!isNaN(+theirscore)){
            theirSCORE.push(parseInt(theirscore));
        }

        if(!isNaN(+camkills)){
            cKILLS.push(parseInt(camkills));
        }

        if(!isNaN(+camdeaths)){
            cDEATHS.push(parseInt(camdeaths));
        }

        if(!isNaN(+jamkills)){
            jKILLS.push(parseInt(jamkills));
        }

        if(!isNaN(+jamdeaths)){
            jDEATHS.push(parseInt(jamdeaths));
        }

        if(!isNaN(+enemiesleft)){
            enLEFT.push(parseInt(enemiesleft));
        }
      });


    // Add up all the columns
    var x1 = calcTotal(ourSCORE);
    var x2 = calcTotal(theirSCORE);
    var x3 = calcTotal(cKILLS);
    var x4 = calcTotal(cDEATHS);
    var x5 = calcTotal(jKILLS);
    var x6 = calcTotal(jDEATHS);
    var x7 = calcTotal(enLEFT);

    // Win/Loss ratio calculation
    var wins = "<?php echo $winCount; ?>";
    var totalGames = "<?php echo $total; ?>";
    var sixoh = "<?php echo $sixoCount; ?>";
    var losses = totalGames - wins;
    
    var wlRatio = calcRatio(wins,losses);
    var winPercent = (wins / totalGames) * 100
    var camKD = calcRatio(x3,x4);
    var jamKD = calcRatio(x5,x6);
    $("#wins").text(wins);
    $("#losses").text(losses);
    $("#wlRatio").text(round(wlRatio));
    $("#winPercentage").text(round(winPercent));
    $("#sixOhs").text(sixoh);

    // Camerons K/D ratio calculation
    $("#camKD").text(round(camKD));

    // My K/D ratio calculation
    $("#jamKD").text(round(jamKD));

    // Gets the last loss ID and the latest game ID, the difference between these two is the current winstreak
    var latestLoss = "<?php echo $var1; ?>";
    var latestGame = "<?php echo $var2; ?>";
    var winstreak = latestGame - latestLoss;
    $("#currentStreak").text(winstreak);
    
    // All of this ugly code is simply filling data into various tables
    $("#totalOWINS").text(x1);
    $("#totalTWINS").text(x2);
    $("#totalCKILLS").text(x3);
    $("#totalCDEATHS").text(x4);
    $("#totalJKILLS").text(x5);
    $("#totalJDEATHS").text(x6);
    $("#totalLEFT").text(x7);

    $("#totalCamKills").text(x3);
    $("#totalCamDeaths").text(x4);
    $("#camKDS").text(Math.round((camKD + Number.EPSILON) * 100) / 100);
    $("#mostCamKills").text(highestInColumn(3));
    $("#mostCamDeaths").text(highestInColumn(4));
    $("#bestCamKD").text(round(bestRatio(3,4)));

    $("#AKillsCam").text(mapStats("Amsterdam",3));
    $("#ADeathsCam").text(mapStats("Amsterdam",4));
    $("#AKDCam").text(round(calcRatio(mapStats("Amsterdam",3),mapStats("Amsterdam",4))));
    $("#DKillsCam").text(mapStats("Diesel",3));
    $("#DDeathsCam").text(mapStats("Diesel",4));
    $("#DKDCam").text(round(calcRatio(mapStats("Diesel",3),mapStats("Diesel",4))));
    $("#GKillsCam").text(mapStats("Gameshow",3));
    $("#GDeathsCam").text(mapStats("Gameshow",4));
    $("#GKDCam").text(round(calcRatio(mapStats("Gameshow",3),mapStats("Gameshow",4))));
    $("#IKillsCam").text(mapStats("ICBM",3));
    $("#IDeathsCam").text(mapStats("ICBM",4));
    $("#IKDCam").text(round(calcRatio(mapStats("ICBM",3),mapStats("ICBM",4))));
    $("#KKillsCam").text(mapStats("KGB",3));
    $("#KDeathsCam").text(mapStats("KGB",4));
    $("#KKDCam").text(round(calcRatio(mapStats("KGB",3),mapStats("KGB",4))));
    $("#MKillsCam").text(mapStats("Mansion",3));
    $("#MDeathsCam").text(mapStats("Mansion",4));
    $("#MKDCam").text(round(calcRatio(mapStats("Mansion",3),mapStats("Mansion",4))));
    $("#NKillsCam").text(mapStats("Nuketown 84",3));
    $("#NDeathsCam").text(mapStats("Nuketown 84",4));
    $("#NKDCam").text(round(calcRatio(mapStats("Nuketown 84",3),mapStats("Nuketown 84",4))));
    $("#UKillsCam").text(mapStats("U-Bahn",3));
    $("#UDeathsCam").text(mapStats("U-Bahn",4));
    $("#UKDCam").text(round(calcRatio(mapStats("U-Bahn",3),mapStats("U-Bahn",4))));


    $("#totalJamKills").text(x5);
    $("#totalJamDeaths").text(x6);
    $("#jamKDS").text(round(jamKD));
    $("#mostJamKills").text(highestInColumn(5));
    $("#mostJamDeaths").text(highestInColumn(6));
    $("#bestJamKD").text(bestRatio(5,6));

    $("#AKillsJam").text(mapStats("Amsterdam",5));
    $("#ADeathsJam").text(mapStats("Amsterdam",6));
    $("#AKDJam").text(round(calcRatio(mapStats("Amsterdam",5),mapStats("Amsterdam",6))));
    $("#DKillsJam").text(mapStats("Diesel",5));
    $("#DDeathsJam").text(mapStats("Diesel",6));
    $("#DKDJam").text(round(calcRatio(mapStats("Diesel",5),mapStats("Diesel",6))));
    $("#GKillsJam").text(mapStats("Gameshow",5));
    $("#GDeathsJam").text(mapStats("Gameshow",6));
    $("#GKDJam").text(round(calcRatio(mapStats("Gameshow",5),mapStats("Gameshow",6))));
    $("#IKillsJam").text(mapStats("ICBM",5));
    $("#IDeathsJam").text(mapStats("ICBM",6));
    $("#IKDJam").text(round(calcRatio(mapStats("ICBM",5),mapStats("ICBM",6))));
    $("#KKillsJam").text(mapStats("KGB",5));
    $("#KDeathsJam").text(mapStats("KGB",6));
    $("#KKDJam").text(round(calcRatio(mapStats("KGB",5),mapStats("KGB",6))));
    $("#MKillsJam").text(mapStats("Mansion",5));
    $("#MDeathsJam").text(mapStats("Mansion",6));
    $("#MKDJam").text(round(calcRatio(mapStats("Mansion",5),mapStats("Mansion",6))));
    $("#NKillsJam").text(mapStats("Nuketown 84",5));
    $("#NDeathsJam").text(mapStats("Nuketown 84",6));
    $("#NKDJam").text(round(calcRatio(mapStats("Nuketown 84",5),mapStats("Nuketown 84",6))));
    $("#UKillsJam").text(mapStats("U-Bahn",5));
    $("#UDeathsJam").text(mapStats("U-Bahn",6));
    $("#UKDJam").text(round(calcRatio(mapStats("U-Bahn",5),mapStats("U-Bahn",6))));

    $("#amsterdamCamKD").text(round(calcRatio(mapStats("Amsterdam",3),mapStats("Amsterdam",4))));
    $("#dieselCamKD").text(round(calcRatio(mapStats("Diesel",3),mapStats("Diesel",4))));
    $("#gameCamKD").text(round(calcRatio(mapStats("Gameshow",3),mapStats("Gameshow",4))));
    $("#icbmCamKD").text(round(calcRatio(mapStats("ICBM",3),mapStats("ICBM",4))));
    $("#kgbCamKD").text(round(calcRatio(mapStats("KGB",3),mapStats("KGB",4))));
    $("#mansionCamKD").text(round(calcRatio(mapStats("Mansion",3),mapStats("Mansion",4))));
    $("#nukeCamKD").text(round(calcRatio(mapStats("Nuketown 84",3),mapStats("Nuketown 84",4))));
    $("#ubahnCamKD").text(round(calcRatio(mapStats("U-Bahn",3),mapStats("U-Bahn",4))));

    $("#amsterdamJamKD").text(round(calcRatio(mapStats("Amsterdam",5),mapStats("Amsterdam",6))));
    $("#dieselJamKD").text(round(calcRatio(mapStats("Diesel",5),mapStats("Diesel",6))));
    $("#gameJamKD").text(round(calcRatio(mapStats("Gameshow",5),mapStats("Gameshow",6))));
    $("#icbmJamKD").text(round(calcRatio(mapStats("ICBM",5),mapStats("ICBM",6))));
    $("#kgbJamKD").text(round(calcRatio(mapStats("KGB",5),mapStats("KGB",6))));
    $("#mansionJamKD").text(round(calcRatio(mapStats("Mansion",5),mapStats("Mansion",6))));
    $("#nukeJamKD").text(round(calcRatio(mapStats("Nuketown 84",5),mapStats("Nuketown 84",6))));
    $("#ubahnJamKD").text(round(calcRatio(mapStats("U-Bahn",5),mapStats("U-Bahn",6))));

    var atable = $("#amsterdamTable");
    var aWins = 0;
    var dtable = $("#dieselTable");
    var dWins = 0;
    var gtable = $("#gameTable");
    var gWins = 0;
    var itable = $("#icbmTable");
    var iWins = 0;
    var ktable = $("#kgbTable");
    var kWins = 0;
    var mtable = $("#manTable");
    var mWins = 0;
    var ntable = $("#nukeTable");
    var nWins = 0;
    var utable = $("#uTable");
    var uWins = 0;

      // Color each map table's rows based on win / loss
      atable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();

        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            aWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      dtable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();

        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            dWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      gtable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();

        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            gWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      itable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();

        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            iWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      ktable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();
        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            kWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      mtable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();
        
        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            mWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      ntable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();

        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            nWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      utable.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        ourscore = $tds.eq(0).text();

        if(ourscore == "6"){
            $tds.parent().css( "background-color", "#B4FFB4" );
            uWins++;
        }else{
            $tds.parent().css( "background-color", "#FFB4B4" );
        }

      });

      // This section calculates the win % for each map
      var aTotal = $("#amsterdamTable tr").length;
      var dTotal = $("#dieselTable tr").length;
      var gTotal = $("#gameTable tr").length;
      var iTotal = $("#icbmTable tr").length;
      var kTotal = $("#kgbTable tr").length;
      var mTotal = $("#manTable tr").length;
      var nTotal = $("#nukeTable tr").length;
      var uTotal = $("#uTable tr").length;

      $("#amsterdamWins").text(aWins);
      $("#dieselWins").text(dWins);
      $("#gameWins").text(gWins);
      $("#icbmWins").text(iWins);
      $("#kgbWins").text(kWins);
      $("#mansionWins").text(mWins);
      $("#nukeWins").text(nWins);
      $("#ubahnWins").text(uWins);

      $("#amsterdamLosses").text((aTotal - 1) - aWins);
      $("#dieselLosses").text((dTotal - 1) - dWins);
      $("#gameLosses").text((gTotal - 1) - gWins);
      $("#icbmLosses").text((iTotal - 1) - iWins);
      $("#kgbLosses").text((kTotal - 1) - kWins);
      $("#mansionLosses").text((mTotal - 1) - mWins);
      $("#nukeLosses").text((nTotal - 1) - nWins);
      $("#ubahnLosses").text((uTotal - 1) - uWins);

      $("#amsterdamWinPercentage").text(round(winPercentage(aWins, aTotal)));
      $("#dieselWinPercentage").text(round(winPercentage(dWins, dTotal)));
      $("#gameWinPercentage").text(round(winPercentage(gWins, gTotal)));
      $("#icbmWinPercentage").text(round(winPercentage(iWins, iTotal)));
      $("#kgbWinPercentage").text(round(winPercentage(kWins, kTotal)));
      $("#mansionWinPercentage").text(round(winPercentage(mWins, mTotal)));
      $("#nukeWinPercentage").text(round(winPercentage(nWins, nTotal)));
      $("#ubahnWinPercentage").text(round(winPercentage(uWins, uTotal)));

      function winPercentage(wins,total){
        var percentage = (wins / (total - 1)) * 100;
        return percentage;
      }

    
    // Function used to round values that is less ugly than the whole line below
    function round(x){
      return Math.round((x + Number.EPSILON) * 100) / 100;
    }

    // Calculates the total of the column thats passed to it
    function calcTotal(x) {
        var total = 0;
        for(var i = 0; i < x.length; i++){
            total = total + x[i];
            
        }
        return total;
    }

    // Calculates the ratio of passed values
    function calcRatio(x,y){
      var ratio = x / y;
      if(y == 0){
        ratio = x / 1;
      }
      return ratio;
    }

      // Finds the longest winstreak
      var winCount = 0;
      var highestStreak = 0;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        var ourscore = $tds.eq(1).text();

        if(ourscore == "6"){
            winCount++; 
        }
        else{
            winCount = 0;
        }
        
        if(winCount > highestStreak){
              highestStreak = winCount;
            }
        
    });
    $("#longestStreak").text(highestStreak);
    
    // Finds the highest value in a selected column
    function highestInColumn(x){
      var table = $("#main");
      var y = 0;
      var highest = 0;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
         y = parseFloat($tds.eq(x).text());
            if(y > highest){
              highest = y;
              y = 0;
            }
          });
    return highest;
    }

    function highestInColumn1(){
      // Loop thru the column in #jamMapStats table to find the highest K/D
      // If two K/Ds are the same, the kill count for that map is the deciding factor.
      // SUPER JANKY code, please dont judge me. Next 3 functions finds the lowest and then the highest/lowest
      // in the #camMapStats table
      var table = $("#jamMapStats");
      var y = 0;
      var highest = 0;
      var mapName;
      var finalMapName;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
         y = parseInt($tds.eq(3).text());
         mapName = $tds.eq(0).text();
            if(y > highest){
              highest = y;
              y = 0;
              finalMapName = mapName;
            }
          });
          return [highest,finalMapName];
    }

    function lowestInColumn1(){
      var table = $("#jamMapStats");
      var y = 1000000000;
      var lowest = 1000000000;
      var mapName;
      var finalMapName;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
         y = parseFloat($tds.eq(3).text());
         mapName = $tds.eq(0).text();
            if(y < lowest){
              lowest = y;
              y = 0;
              finalMapName = mapName;
            }
          });
    return [lowest,finalMapName];
    }

    function highestInColumn2(){
      var table = $("#camMapStats");
      var y = 0;
      var highest = 0;
      var mapName;
      var finalMapName;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
         y = parseFloat($tds.eq(3).text());
         mapName = $tds.eq(0).text();
            if(y > highest){
              highest = y;
              y = 0;
              finalMapName = mapName;
            }
          });
          return [highest,finalMapName];
    }

    function lowestInColumn2(){
      var table = $("#camMapStats");
      var y = 1000000000;
      var lowest = 1000000000;
      var mapName;
      var finalMapName;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
         y = parseFloat($tds.eq(3).text());
         mapName = $tds.eq(0).text();
            if(y < lowest){
              lowest = y;
              y = 0;
              finalMapName = mapName;
            }
          });
          return [lowest,finalMapName];
    }
    
    var camBestMap = highestInColumn2();
    var camWorstMap = lowestInColumn2();
    var jamBestMap = highestInColumn1();
    var jamWorstMap = lowestInColumn1();

    // Changes the best/worst map image based on the highest/lowest values
    switch(camBestMap[1]){
      case "Amsterdam":
      $("#camBestMapImage").attr("src","assets/am.jpg");
      $("#camBestMapImage").attr("title","Amsterdam");
      break;

      case "Diesel":
      $("#camBestMapImage").attr("src","assets/diesel.jpg");
      $("#camBestMapImage").attr("title","Diesel");
      break;

      case "Gameshow":
      $("#camBestMapImage").attr("src","assets/game.jpg");
      $("#camBestMapImage").attr("title","Gameshow");
      break;

      case "ICBM":
      $("#camBestMapImage").attr("src","assets/icbm.jpg");
      $("#camBestMapImage").attr("title","ICBM");
      break;

      case "KGB":
      $("#camBestMapImage").attr("src","assets/kgb.jpg");
      $("#camBestMapImage").attr("title","KGB");
      break;

      case "Mansion":
      $("#camBestMapImage").attr("src","assets/mansion.jpg");
      $("#camBestMapImage").attr("title","Mansion");
      break;

      case "Nuketown 84":
      $("#camBestMapImage").attr("src","assets/nuke.jpg");
      $("#camBestMapImage").attr("title","Nuketown 84");
      break;

      case "U-Bahn":
      $("#camBestMapImage").attr("src","assets/ubahn.jpg");
      $("#camBestMapImage").attr("title","U-Bahn");
      break;
    }

    switch(camWorstMap[1]){
      case "Amsterdam":
      $("#camWorstMapImage").attr("src","assets/am.jpg");
      $("#camWorstMapImage").attr("title","Amsterdam");
      break;

      case "Diesel":
      $("#camWorstMapImage").attr("src","assets/diesel.jpg");
      $("#camWorstMapImage").attr("title","Diesel");
      break;

      case "Gameshow":
      $("#camWorstMapImage").attr("src","assets/game.jpg");
      $("#camWorstMapImage").attr("title","Gameshow");
      break;

      case "ICBM":
      $("#camWorstMapImage").attr("src","assets/icbm.jpg");
      $("#camWorstMapImage").attr("title","ICBM");
      break;

      case "KGB":
      $("#camWorstMapImage").attr("src","assets/kgb.jpg");
      $("#camWorstMapImage").attr("title","KGB");
      break;

      case "Mansion":
      $("#camWorstMapImage").attr("src","assets/mansion.jpg");
      $("#camWorstMapImage").attr("title","Mansion");
      break;

      case "Nuketown 84":
      $("#camWorstMapImage").attr("src","assets/nuke.jpg");
      $("#camWorstMapImage").attr("title","Nuketown 84");
      break;

      case "U-Bahn":
      $("#camWorstMapImage").attr("src","assets/ubahn.jpg");
      $("#camWorstMapImage").attr("title","U-Bahn");
      break;
    }


    switch(jamBestMap[1]){
      case "Amsterdam":
      $("#jamBestMapImage").attr("src","assets/am.jpg");
      $("#jamBestMapImage").attr("title","Amsterdam");
      break;

      case "Diesel":
      $("#jamBestMapImage").attr("src","assets/diesel.jpg");
      $("#jamBestMapImage").attr("title","Diesel");
      break;

      case "Gameshow":
      $("#jamBestMapImage").attr("src","assets/game.jpg");
      $("#jamBestMapImage").attr("title","Gameshow");
      break;

      case "ICBM":
      $("#jamBestMapImage").attr("src","assets/icbm.jpg");
      $("#jamBestMapImage").attr("title","ICBM");
      break;

      case "KGB":
      $("#jamBestMapImage").attr("src","assets/kgb.jpg");
      $("#jamBestMapImage").attr("title","KGB");
      break;

      case "Mansion":
      $("#jamBestMapImage").attr("src","assets/mansion.jpg");
      $("#jamBestMapImage").attr("title","Mansion");
      break;

      case "Nuketown 84":
      $("#jamBestMapImage").attr("src","assets/nuke.jpg");
      $("#jamBestMapImage").attr("title","Nuketown 84");
      break;

      case "U-Bahn":
      $("#jamBestMapImage").attr("src","assets/ubahn.jpg");
      $("#jamBestMapImage").attr("title","U-Bahn");
      break;
    }

    switch(jamWorstMap[1]){
      case "Amsterdam":
      $("#jamWorstMapImage").attr("src","assets/am.jpg");
      $("#jamWorstMapImage").attr("title","Amsterdam");
      break;

      case "Diesel":
      $("#jamWorstMapImage").attr("src","assets/diesel.jpg");
      $("#jamWorstMapImage").attr("title","Diesel");
      break;

      case "Gameshow":
      $("#jamWorstMapImage").attr("src","assets/game.jpg");
      $("#jamWorstMapImage").attr("title","Gameshow");
      break;

      case "ICBM":
      $("#jamWorstMapImage").attr("src","assets/icbm.jpg");
      $("#jamWorstMapImage").attr("title","ICBM");
      break;

      case "KGB":
      $("#jamWorstMapImage").attr("src","assets/kgb.jpg");
      $("#jamWorstMapImage").attr("title","KGB");
      break;

      case "Mansion":
      $("#jamWorstMapImage").attr("src","assets/mansion.jpg");
      $("#jamWorstMapImage").attr("title","Mansion");
      break;

      case "Nuketown 84":
      $("#jamWorstMapImage").attr("src","assets/nuke.jpg");
      $("#jamWorstMapImage").attr("title","Nuketown 84");
      break;

      case "U-Bahn":
      $("#jamWorstMapImage").attr("src","assets/ubahn.jpg");
      $("#jamWorstMapImage").attr("title","U-Bahn");
      break;
    }

    // Used to find the highest KD ratio (kills divided by deaths) in the entire main
    function bestRatio(x,y){
      var table = $("#main");
      var kills = 0;
      var deaths = 0;
      var ratio = 0;
      var highest = 0;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
        kills = parseInt($tds.eq(x).text());
        deaths = parseInt($tds.eq(y).text());
         ratio = calcRatio(kills,deaths);
            if(ratio > highest){
              highest = ratio;
              ratio = 0;
            }
          });
    return highest;
    }


    // Calculates stats based on a map name
    function mapStats(mapName,x){
      var table = $("#main");
      var count = 0;
      var total = 0;
      var name;
      table.find('tr').each(function (i, el) {
        var $tds = $(this).find('td');
         count = parseInt($tds.eq(x).text());
         name = $tds.eq(0).text();

         if(name == mapName){
          total = total + count;
         }
         

          });
    return total;
    }

    // Show the form when you click the button
    $("#newButton").click(function(){
      $("#newGame").css("display", "inline-block");
    });
    // And hide the form when you click close
    $("#closeNewGame").click(function(){
      $("#newGame").css("display", "none");
    });

    // Change the active class when you click
    $("#homeButton").click(function(){
      $("#home").css("display", "block");
      $("#homeButton").addClass("active");
      $("#players").css("display", "none");
      $("#playersButton").removeClass("active");
      $("#mapsSection").css("display", "none");
      $("#mapsButton").removeClass("active");
    });

    $("#playersButton").click(function(){
      $("#players").css("display", "block");
      $("#playersButton").addClass("active");
      $("#home").css("display", "none");
      $("#homeButton").removeClass("active");
      $("#mapsSection").css("display", "none");
      $("#mapsButton").removeClass("active");
    });

    $("#mapsButton").click(function(){
      $("#mapsSection").css("display", "block");
      $("#mapsButton").addClass("active");
      $("#home").css("display", "none");
      $("#homeButton").removeClass("active");
      $("#players").css("display", "none");
      $("#playersButton").removeClass("active");
    });

    // Show the password entry box when you click
    $("#passButton").click(function(){
      $("#passEntry").css("display", "block");
    });

    // Used to show/hide the password text when the checkbox is clicked
    function showHide() {
  var x = document.getElementById("passwordInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

// Checks the value of the password is correct against the password stored in a seperate file
function checkPassword(){
  var input = document.getElementById("passwordInput").value;

  if(input === password){
    $("#passEntry").css("display", "none");
    $("#passButton").css("display", "none");
    $("#newButton").css("display", "block");
  }else{
    $("#passwordInput").css("border", "3px solid red");
  }
}

// Scrolls to the bottom on the page when the down arrow is clicked
$('#scrollBottom').click(function() {
          var windowHeight = $(window).height();
          var scrollHeight = $('body')[0].scrollHeight;
          $("html, body").stop().animate({scrollTop: scrollHeight - windowHeight});
     });

// Scrolls to the top on the page when the up arrow is clicked
$('#scrollTop').click(function() {
         $("html, body").animate({scrollTop: 0});
     });

</script>
</html>