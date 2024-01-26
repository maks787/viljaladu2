<?php
require('conf.php');
global $yhendus;
?>
<div id="sisu">
    <?php
    global $yhendus;

    // Обработка нажатия на autonr
    if (isset($_REQUEST["id"])) {
        $paring = $yhendus->prepare("SELECT autonr, sisenemismass, lahkumismass FROM koormad WHERE id=?");
        $paring->bind_param("i", $_REQUEST["id"]);
        $paring->bind_result($autonr, $sisenemismass, $lahkumismass);
        $paring->execute();

        if ($paring->fetch()) {
            echo "<p>ID: " . $autonr;
            echo "<br>Sisenemismass: " . $sisenemismass;
            echo "<br>Lahkumismass: " . $lahkumismass;
            echo "</p>";
        }

        $paring->close();
    }

    $autonr_paring = $yhendus->prepare("SELECT id, autonr FROM koormad");
    $autonr_paring->bind_result($id, $autonr);
    $autonr_paring->execute();

    while ($autonr_paring->fetch()) {
        echo "<a href='?id=$id'>$autonr</a><br>";
    }

    $autonr_paring->close();
    ?>
</div>
