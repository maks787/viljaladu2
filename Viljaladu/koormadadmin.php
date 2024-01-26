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
    <title>Koormad</title>
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

<h1>Sõiduki laadimine </h1>
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
function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
}
global $yhendus;
if (isset($_REQUEST["synd"]) && !empty($_REQUEST["synd"]) && isAdmin()) {
    $paring = $yhendus->prepare("INSERT INTO koormad (autonr, sisenemismass) VALUES (?, ?)");
    $paring->bind_param("ss", $_REQUEST["autonr"], $_REQUEST["sisenemismass"]);
    $paring->execute();
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
?>

<?php if (isAdmin()): ?>
    <h2>Lisa uus auto</h2>
    <table>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <tr>
                <th>Autonr:</th> <td><input type="text" name="autonr" required></td>
            </tr>
            <tr>
                <th>Sisenemismass:</th> <td><input type="text" name="sisenemismass" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="synd" value="Loo uus"></td>
            </tr>
        </form>
    </table>
<?php endif; ?>

<?php
$paring = $yhendus->prepare("SELECT lahkumismass FROM koormad WHERE id = ?");
$paring->bind_param("i", $id);
$paring->execute();
$paring->bind_result($lahkumismass);
$paring->fetch();
$paring->close();

echo "<br>";
?>

<?php
$paring = $yhendus->prepare("SELECT autonr, sisenemismass FROM koormad");
$paring->bind_result($autonr, $sisenemismass);
$paring->execute();
?>

<table>
    <tr>
        <th>Autonr</th>
        <th>Sisenemismass</th>
    </tr>

    <?php
    while ($paring->fetch()) {
        echo "<tr><td>$autonr</td><td>$sisenemismass</td></tr>";
    }
    ?>
</table>

<?php if (isAdmin()): ?>
    <h2>Ootsi autoga lahkumassi</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="selected_id">Valitud id:</label>
        <select name="selected_id" id="selected_id">
            <?php
            $paring = $yhendus->prepare("SELECT id FROM koormad");
            $paring->execute();
            $paring->bind_result($id);
            while ($paring->fetch()) {
                echo "<option value=\"$id\">$id</option>";
            }
            $paring->close();
            ?>
        </select>
        <input type="submit" name="select_id" value="Выбрать">
    </form>
<?php endif; ?>

<?php
if (isset($_POST["select_id"]) && isAdmin()) {
    $selected_id = $_POST["selected_id"];

    $paring = $yhendus->prepare("SELECT lahkumismass FROM koormad WHERE id = ?");
    $paring->bind_param("i", $selected_id);
    $paring->execute();
    $paring->bind_result($lahkumismass);
    $paring->fetch();

    echo "<p>Ig-ga auto lahkumassi määramiseks $selected_id: $lahkumismass</p>";
    $paring->close();
}
?>

</body>
</html>
