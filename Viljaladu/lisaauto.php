<?php
require('conf.php');
global $yhendus;
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="autocss.css">
</head>
<body>

<h1>Koormad andmebaasist</h1>

<?php
global $yhendus;
$paring = $yhendus->prepare("SELECT autonr, sisenemismass, lahkumismass,autotuleb FROM koormad");
$paring->bind_result($autonr, $sisenemismass, $lahkumismass,$autotuleb);
$paring->execute();
?>

<table>
    <tr>
        <th>Auto number</th>
        <th>Sisenemismass</th>
        <th>Lahkumismass</th>
        <th>Reisid</th>
    </tr>
    <?php
    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>$autonr</td>";


        echo "<td>$sisenemismass</td>";
        echo "<td>$lahkumismass</td>";
        echo "<td>$autotuleb</td>";

        echo "</tr>";
    }
    $yhendus->close();
    ?>
</table>