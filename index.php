<?php

function convert_date($date_string) {
    $date = DateTime::createFromFormat("d/m/Y", $date_string);
    $date->add(DateInterval::createFromDateString("6 months"));
    return $date;
}

function is_valid($date) {
    if ($date >= new DateTime("-6 month")) {
        return "Valide";
    }
    return "Expiré";
}

if (!empty($_GET)) {
    $data_complete = array(
        "NOM" => $_GET["nom"]??"null",
        "PRENOM" => $_GET["prenom"]??"null",
        "N° DE SÉCU" => $_GET["secu"]??"null",
        "DATE DE NAISSANCE" => $_GET["born"]??"null",
        "DATE DE VACCINATION" => $_GET["vaccinated"]??"null",
    );
    $data_complete["DATE D'EXPIRATION"] = convert_date($data_complete["DATE DE VACCINATION"]);
    $data_complete["STATUT"] = is_valid($data_complete["DATE D'EXPIRATION"]);
} else {
    $data_complete = array(
        "NOM" => "MOUZOUNE",
        "PRENOM" => "Ahmed",
        "N° DE SÉCU" => "1 0123 20823Y813Y8",
        "DATE DE NAISSANCE" => "26/08/2021",
        "DATE DE VACCINATION" => "17/05/2021",
        "DATE D'EXPIRATION" => DateTime::createFromFormat("d/m/Y", "17/11/2021"),
        "STATUT" => "Vacciné"
    );
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/normalize.css">
    <title>TousVacciner</title>
</head>
    <body>
        <section class="conteneur">
            <h1 class="titre">MON PASSEPORT</h1>
            <section class="card">
                <div class="card-header">
                    <?php
                    use chillerlan\QRCode\QRCode;
                    include 'vendor/autoload.php';
                    $data = array(
                            "NOM" => $data_complete["NOM"],
                            "PRENOM" => $data_complete["PRENOM"],
                            "DATE DE VACCINATION" => $data_complete["DATE DE VACCINATION"],
                            "DATE D'EXPIRATION" => $data_complete["DATE D'EXPIRATION"]->format("d/m/Y"),
                            "STATUT" => $data_complete["STATUT"]
                    );
                    echo '<img src="'.(new QRCode)->render(urldecode(str_replace('=', ' : ', http_build_query($data, "", "\n")))).'" alt="QR Code" />';
                    ?>
                </div>
                <h2 class="titre-secondaire">INFORMATIONS</h2>
                <div class="card-body">
                    <ul>
                        <li>NOM : <?php echo $data_complete["NOM"];?></li>
                        <li>PRENOM : <?php echo $data_complete["PRENOM"];?></li>
                        <li>DATE DE NAISSANCE : <?php echo $data_complete["DATE DE NAISSANCE"];?></li>
                        <li>DATE DE VACCINATION : <?php echo $data_complete["DATE DE VACCINATION"];?></li>
                        <li>N° DE SÉCU : <?php echo $data_complete["N° DE SÉCU"];?></li>
                        <li>DATE DE D'EXPIRATION : <?php echo $data_complete["DATE D'EXPIRATION"]->format("d/m/Y");?></li>
                        <li>STATUT : <?php echo $data_complete["STATUT"];?></li>
                    </ul>
                </div>
            </section>
        </section>
    </body>
</html>
