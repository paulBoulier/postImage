<?php

// var_dump($_FILES["img"]["type"]);
// var_dump(mime_content_type($_FILES["img"]["tmp_name"]));

function vardumpArr($file)
{
    foreach ($file as $key => $value) {
        echo "$key: $value <br>";
    }
}

function store($tmp_name, $uid, $ext)
{
    move_uploaded_file($tmp_name, "./img/" . $uid . "." . $ext);
}

// enregistre sur le server
function upload($img_file, $type = "image", $size = 100000)
{
    if (!isset($_POST["submit"])) {
        return false;
    }

    $img_file = $_FILES[$img_file] ?? false; # on "identifie" $img_file
    $type = "/$type/"; # on prépare la regex
    $msgArray = []; # notre liste de messages

    if ($img_file && $img_file["error"] == 0) {
        if (!preg_match($type, mime_content_type($img_file["tmp_name"]))) { # si c'est une image
        $msgArray[] = "Votre fichier n'est pas une image";
        } elseif ($img_file["size"] > $size) { # si le fichier est plus grand que 1Mo
        $msgArray[] = "Désolé, votre fichier doit faire moins de 1 Mo";
        }

        if (count($msgArray) != 0) { # si il y a un déjà message dans notre array
        $msgArray[] = "Votre fichier n'a pas été upload.";
        } else { # dans le cas ou tout est bon
        $uid = uniqid();
            $ext = pathinfo($img_file["name"])["extension"];
            store($img_file["tmp_name"], $uid, $ext);
            $msgArray[] = "Le fichier " . $uid . "." . $ext . " a bien été uploadé.";
        }
    }

    if ($img_file["error"] == 4) {
        $msgArray[] = "Veuillez choisir un fichier.";
    }

    $res = "";
    foreach ($msgArray as $msg) {
        $res .= "<p>" . $msg . "</p>";
    }
    return $res;
}

$uploaded = upload("img");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" enctype="multipart/form-data">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/uploadPreview.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Module d'enregistrement d'images.</h1>
        <div>Mise en pratique PHP : Upload d'images.</div>
    </header>
    <div class="box">
        <div class="wrapper">
            <div class="mb-3">Veuillez choisir une image :</div>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="input-container flex-wrap"">
                    <label class="me-2" for="fileToUpload">Parcourir...</label>
                    <input name="img" type="file" id="fileToUpload" accept="image/png, image/jpg, image/jpeg">
                    <div class="input-text"><?=$uploaded?></div>
                    <img class="preview" src="" alt="" id="imgPreview">
                    <i class="<?=preg_match("/a bien été uploadé/", $uploaded) ? "bi bi-check2-circle validation-icon" : ""?>"></i>
                </div>
                <!-- <input type="submit" value="Upload Image" name="submit"> -->
                <button class="btn btn-secondary mt-3" type="submit" name="submit">upload</button>
            </form>
        </div>
        <div class="wrapper d-none d-md-block">
            <img src="./assets/img/server.jpg" alt="">
        </div>
    </div>
    <script src="./assets/js/uploadPreview.js"></script>
</body>
</html>
