<?php
require('conf.php');
global $yhendus;
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="autocss.css">
    <title>Väljumass</title>
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
</head>
<body>

<h1>Väljumass</h1>
<nav>
    <ul>
        <li>
            <a href="koormadadmin.php">Lisa auto</a>
        </li>
        <li>
            <a href="valjumass.php">Lahkumismass redigeerimine</a>
        </li>
        <li>
            <a href="tulnudauto.php">Auto tuleb</a>
        </li>

    </ul>

</nav>

<?php
global $yhendus;

if (isset($_POST["edit"]) && !empty($_POST["lahkumismass"])) {
    $autonr = $_POST["autonr"];
    $sisenemismass = $_POST["sisenemismass"];
    $muuda_lahkumismass = $_POST["lahkumismass"];

    $paring = $yhendus->prepare("UPDATE koormad SET lahkumismass = ? WHERE autonr = ?");
    $paring->bind_param("ss", $muuda_lahkumismass, $autonr);
    $paring->execute();

    echo "Lahkumismass autole $autonr muutunud: $muuda_lahkumismass";
    echo "<br>";
}

$paring = $yhendus->prepare("SELECT autonr, sisenemismass, lahkumismass FROM koormad");
$paring->bind_result($autonr, $sisenemismass, $lahkumismass);
$paring->execute();
?>

<table>
    <tr>
        <th>Autonr</th>
        <th>Sisenemismass</th>
        <th>Väljumass</th>
    </tr>
    <?php
    while ($paring->fetch()) {
        echo "<tr><td>$autonr</td><td>$sisenemismass</td><td>$lahkumismass</td></tr>";
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <tr>
                <td colspan="2">
                    Lahkumismass: <input type="text" name="lahkumismass" value="<?php echo $lahkumismass; ?>" required>
                    <input type="hidden" name="autonr" value="<?php echo $autonr; ?>">
                    <input type="hidden" name="sisenemismass" value="<?php echo $sisenemismass; ?>">
                </td>
                <td>
                    <input type="submit" name="edit" value="Redigeeri">
                </td>
            </tr>
        </form>
        <?php
    }

    $paring->close();
    ?>
</table>

</body>
</html>
