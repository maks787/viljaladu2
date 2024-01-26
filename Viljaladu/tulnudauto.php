<?php
require('conf.php');
session_start();
global $yhendus;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="autocss.css">
    <title>Auto tuleb</title>
    <?php
    if(isset($_SESSION['kasutaja']) && isAdmin()){
        ?>
        <h1>Tere, <?php echo $_SESSION['kasutaja']; ?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        header("Location: lisaauto.php");
        exit();
    }
    function isAdmin(){
        return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
    }
    ?>

</head>
<body>

<h1>Auto tuleb</h1>
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
if (isset($_GET["id"]) && isset($_GET["action"]) && $_GET["action"] == "increment") {
    $updateParing = $yhendus->prepare("UPDATE koormad SET autotuleb = autotuleb + 1 WHERE id = ?");
    $updateParing->bind_param("i", $_GET["id"]);
    $updateParing->execute();
    $updateParing->close();
}

?>

<?php
if (isset($_REQUEST["id"])) {
    $paring = $yhendus->prepare("SELECT autonr, sisenemismass, lahkumismass, autotuleb FROM koormad WHERE id = ?");
    $paring->bind_param("i", $_REQUEST["id"]);
    $paring->bind_result($autonr, $sisenemismass, $lahkumismass, $autotuleb);
    $paring->execute();

    if ($paring->fetch()) {
        $vilja_kogus = $sisenemismass - $lahkumismass;
        ?>
        <table>
            <tr>
                <th>Autonumber</th>
                <th>Sisenemismass</th>
                <th>Lahkumismass</th>
                <th>Vilja kogus</th>
                <th>Kõik reisid</th>
            </tr>
            <tr>
                <td><?php echo $autonr; ?></td>
                <td><?php echo $sisenemismass; ?></td>
                <td><?php echo $lahkumismass; ?></td>
                <td><?php echo $vilja_kogus; ?></td>
                <td><?php echo $autotuleb; ?></td>
            </tr>
        </table>
        <?php
    }

    $paring->close();
}
?>

<!-- Отображение списка автомобилей -->
<?php
$autonr_paring = $yhendus->prepare("SELECT id, autonr FROM koormad");
$autonr_paring->bind_result($id, $autonr);
$autonr_paring->execute();
?>

<table>
    <tr>
        <th>Autonumber</th>
        <th>Tegevused</th>
    </tr>

    <?php
    while ($autonr_paring->fetch()) {
        ?>
        <tr>
            <td><?php echo $autonr; ?></td>
            <td>
                <a href='?id=<?php echo $id; ?>'>Vaata</a>
                <a href='?id=<?php echo $id; ?>&action=increment'> +1 reis</a>
            </td>
        </tr>
        <?php
    }

    $autonr_paring->close();
    ?>

</table>

</body>
</html>
