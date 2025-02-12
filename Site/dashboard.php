<?php
session_start();
if (isset($_SESSION['username']) == false) {
    header('Location:http://51.210.151.13/btssnir/projets2022/easyportal/site/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPortal - Dashboard</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/modal.css">
</head>

<body>
    <div class="content">
        <div class="left-side">
            <div class="left-side-div camera-render">
                <iframe src="http://172.16.200.50:8081" frameborder="0" scrolling="yes" style="width: 100%;height: 100%;"></iframe>
            </div>
            <div class="left-side-div logs">
                <?php

                $curl = curl_init();
                $url = 'http://51.210.151.13/btssnir/projets2022/easyportal/api/logs.php';

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($curl);

                if ($e = curl_error($curl)) {
                    echo $e;
                } else {
                    $json = json_decode($response, true);
                    // var_dump($json);
                    foreach ($json['result']['results'] as $key) {
                        // var_dump($key);
                        $date = new DateTime($key['date']);
                        $r = ($key['methode'] == 'le site') ? $key['methode'] : $key['methode'] . " <span style='font-weight:bold'>" . $key['plate'] . "</span>";
                        echo '<span class="log">' . date_format($date, 'd-m-Y H:i') . ' : <span style="font-weight:bold">' . $key['user'] . '</span> a ouvert le portaille avec ' . $r . '</span>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="dashboard">
            <nav>
                <h1>EasyPortal</h1>
                <img src="images/svg/menu-icon.svg" class="menu-open" alt="Open menu">
            </nav>
            <div class="top-view">
                <div class="title">
                    <h1>EasyPortal</h1>
                </div>
                <div class="displ">
                    <div class="left-displ">
                        <div class="leftButton quit">
                            <img src="images/svg/quit-icon.svg" alt="Icone d'ouverture de porte">
                        </div>
                        <div class="leftButton export">
                            <img src="images/svg/export-icon.svg" alt="Icone d'ouverture de porte">
                        </div>
                    </div>
                    <div class="open-button">
                        <img src="images/svg/door-icon.svg" alt="Icone d'ouverture de porte">
                        <h2 class="open">Ouvrir</h2>
                        <p class="open-label">Préssé pour ouvrir</p>
                    </div>
                </div>
            </div>
            <div class="user-action">
                <div class="add-user user-btn">
                    <img src="images/svg/add-icon.svg" alt="Icone d'ajout d'utilisateur">
                    <h2 class="add-user-heading">Ajouter un utilisateur</h2>
                    <p class="add-user-desc">Indiquez le nom, prenom et nom d’utilisateur ainsi qu’une premiére plaque.
                    </p>
                </div>
                <div class="csv-user user-btn">
                    <img src="images/svg/csv-icon.svg" alt="Icone d'ajout d'utilisateur">
                    <h2 class="csv-user-heading">Ajouter via csv</h2>
                    <p class="csv-user-desc">Ajouter des plaques via un fichier .csv</p>
                </div>
            </div>
            <div class="users-list">
                <h2>UTILISATEUR</h2>
                <div class="table">
                    <div class="tr nex table-header">
                        <div class="cell">Username</div>
                        <div class="cell">Password</div>
                        <div class="cell">Nom</div>
                        <div class="cell">Prénom</div>
                        <div class="cell">Groupe</div>
                        <div class="cell"></div>
                    </div>
                    <?php

                    $url = 'http://51.210.151.13/btssnir/projets2022/easyportal/api/utilisateurs.php';
                    curl_setopt($curl, CURLOPT_URL, $url);

                    $response = curl_exec($curl);

                    if ($e = curl_error($curl)) {
                        echo $e;
                    } else {
                        $json = json_decode($response, true);
                        foreach ($json['result'] as $key => $value) {
                            // var_dump($json['result']);
                            if ($json['result'][$key]['perm'] == "2") {
                                $r = 'admin';
                            } elseif ($json['result'][$key]['perm'] == "1") {
                                $r = 'user';
                            } else {
                                $r = 'restricted';
                            }
                            echo '<div class="tr nex table-row">';
                            echo '<div class = "cell">' . $json['result'][$key]['username'] . '</div>';
                            echo '<div class = "cell pswd">' . $json['result'][$key]['password'] . '</div>';
                            echo '<div class = "cell">' . $json['result'][$key]['lastname'] . '</div>';
                            echo '<div class = "cell">' . $json['result'][$key]['firstname'] . '</div>';
                            echo '<div class = "cell">' . $r . '</div>';
                            echo '<div class = "cell">
                                    <div class="table-img">
                                        <img class="arrow-ico nClick table-ico" src="images/svg/arrow-icon.svg" alt="Flêche d\'éxtention">
                                        <img class="edit-ico table-ico" src="images/svg/edit-ico.svg" alt="Boutton modifier">
                                        <img class="trash-ico table-ico" src="images/svg/trash-ico.svg" alt="Boutton supprimer">
                                    </div>
                                </div>
                            </div>';
                            echo '<div class="table extended hide">
                            <div class="tr ex table-header">
                                <div class="cell-e">Numéro de plaque </div>
                                <!--<div class="cell-e">Derniére utilisation</div>-->
                                <div class="cell-e"></div>
                            </div>';
                            foreach ($json['result'][$key]['plates'] as $key2 => $val) {
                                echo '<div class="tr ex table-row">';
                                echo '<div class="cell-e">' . $val['plateNumber'] . '</div>';
                                echo '<!--<div class="cell-e">21/06/2022 10h34</div>-->
                                    <div class="cell-e">
                                        <div class="table-img">
                                            <img class="edit-ico table-ico edit-plate" src="images/svg/edit-ico.svg" alt="Boutton modifier">
                                            <img class="trash-ico table-ico del-plate" src="images/svg/trash-ico.svg" alt="Boutton supprimer">
                                        </div>
                                    </div></div>';
                            }
                            echo '</div>';
                        }
                    }
                    curl_close($curl);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal">
        <div class="modal-body">
        </div>
    </div>
    <div id="overlay"></div>
    <div class="menu" id="menu">
        <div class="sub-menu menu-header">
            <img src="images/svg/cross.svg" alt="Close cross" class="menu-close">
            <hr>
        </div>
        <div class="sub-menu menu-body">
            <h2>LOGS</h2>
            <h2>CAMERA</h2>
        </div>
    </div>
    <script>
        const username = "<?= $_SESSION['username'] ?>";
        const quitButton = document.querySelector('.quit');

        quitButton.addEventListener("click", () => {
            location.href = "login.php"
        })
    </script>
    <script src="scripts/lib/papaparse.min.js"></script>
    <script src="scripts/js/dashboard.js"></script>
    <script src="scripts/js/editTableAdmin.js"></script>
</body>

</html>