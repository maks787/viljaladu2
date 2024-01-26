<?php
require_once("conf.php");
global $yhendus;

if (!empty($_POST['login']) && !empty($_POST['pass'])) {

    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    $cool = 'superpaev';
    $kryp = crypt($pass, $cool);

    $kask2 = $yhendus->prepare("INSERT INTO kasutaja (kasutaja, parool, onAdmin) VALUES (?, ?, 0)");
    $kask2->bind_param("ss", $login, $kryp);
    $kask2->execute();

    echo '<script>alert("Registreerimine õnnestus!"); window.location.href = "login.php";</script>';

    $kask2->close();
    $yhendus->close();
    exit();
}
?>

<script>
    function back() {
        window.history.back();
    }
</script>

<link rel="stylesheet" type="text/css" href="autocss.css">
<h1>Registreerimine</h1>
<form action="" method="post">
    Kasutaja nimi: <input type="text" name="login"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Register">
</form>


<?php
require('conf.php');
// Andmete kustutamine tabelist
global $yhendus;
if (isset($_REQUEST["kustuta"]) && isAdmin()) {
    $paring = $yhendus->prepare("DELETE FROM koduloomad WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
}

// Lisamine andmetabelisse
if (isset($_REQUEST["nimi"]) && !empty($_REQUEST["nimi"]) && isAdmin()) {
    $paring = $yhendus->prepare("INSERT INTO koduloomad (varv, pilt, nimed, tougu) VALUES (?, ?, ?, ?)");
    $paring->bind_param("ssss", $_REQUEST["varv"], $_REQUEST["pilt"], $_REQUEST["nimi"], $_REQUEST["tougu"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
}
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


</body>
</html>

