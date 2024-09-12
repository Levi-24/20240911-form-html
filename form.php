<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = htmlspecialchars(trim(ucwords(strtolower($_POST['name']))));
    $email = strtolower($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $course = $_POST['course'];

    filter_var($email, FILTER_SANITIZE_EMAIL);
    filter_var($email, FILTER_VALIDATE_EMAIL);

    if ($name && $email && $phone && $course) {
        echo "<h1>Név: </h1>" . $name . "<br>";
        echo "<h1>E-mail: </h1>" . $email . "<br>";
        echo "<h1>Telefon: </h1>" . $phone . "<br>";
        echo "<h1>Tanfolyam: </h1>" . $course . "<br>";

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoTmpPath = $_FILES['photo']['tmp_name'];
            $photoName = uniqid() . '_' . $_FILES['photo']['name'];
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destination = $uploadDir . basename($photoName);

            if (move_uploaded_file($photoTmpPath, $destination)) {
                echo "<img src='" . $destination . "' alt='photo'>";
            } else {
                echo "Hiba a fájl feltöltésekor.";
            }
        }
    } else {
        echo "Nem adott meg adataokat.";
    }
}
