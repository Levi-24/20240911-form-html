<?php
$testResults = [];
// Egyszerű tesztellenőrző függvény
function assertEqual($expected, $actual, $testName) {
    global $testResults;
    if ($expected === $actual) {
        $testResults[] = " $testName: Teszt sikeres!";
    } else {
        $testResults[] = "$testName: Sikertelen teszt. Várt érték: '$expected', tényleges: '$actual'";
    }
}
// Include-oljuk az osztályt tartalmazó fájlt
include_once 'form2.php';

$formHandler = new FormDataHandler("Lucfer Morningstar", "lucifer@gmail.com", "123456789", "frontend", null);
$formattedName = (new ReflectionClass('FormDataHandler'))->getMethod('formatName')->invokeArgs($formHandler, ['Lucfer Morningstar']);
assertEqual("Lucfer Morningstar", $formattedName, "Név formázása");

// 2. teszt: Helyes adatok feldolgozása
$photo = ['error' => UPLOAD_ERR_OK, 'name' => 'test.jpg', 'tmp_name' => '/tmp/test.jpg'];
$formHandler = new FormDataHandler("Lucfer Morningstar", "lucifer@gmail.com", "123456789", "frontend", $photo);

ob_start(); // Kimenet elkapása
$formHandler->processForm();
$output = ob_get_clean();
$response = json_decode($output, true);

assertEqual("Lucfer Morningstar", $response['name'], "Név feldolgozása");
assertEqual("lucifer@gmail.com", $response['email'], "Email feldolgozása");
assertEqual("123456789", $response['phone'], "Telefonszám feldolgozása");
assertEqual("frontend", $response['course'], "Tanfolyam feldolgozása");
assertEqual(true, isset($response['photo']), "Fénykép feldolgozása");

// 3. teszt: Hiányzó mezők tesztelése
$formHandler = new FormDataHandler("", "lucifer@gmail.com", "123456789", "frontend", null);
ob_start(); // Kimenet elkapása
$formHandler->processForm();
$output = ob_get_clean();
$response = json_decode($output, true);
assertEqual("Kérjük, töltse ki az összes kötelező mezőt!", $response['error'], "Hiányzó mezők kezelése");

// 4. teszt: Feltöltési hiba kezelése
$photo = ['error' => UPLOAD_ERR_NO_FILE];
$formHandler = new FormDataHandler("Lucfer Morningstar", "lucifer@gmail.com", "123456789", "frontend", $photo);
ob_start(); // Kimenet elkapása
$formHandler->processForm();
$output = ob_get_clean();
$response = json_decode($output, true);
assertEqual(null, $response['photo'], "Feltöltési hiba kezelése");

// Az eredmények megjelenítése
echo "<h2>Teszt eredmények:</h2>";
foreach ($testResults as $result) {
    echo $result;
}

