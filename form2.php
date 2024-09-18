<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

class FormDataHandler
{
    private $name;
    private $email;
    private $phone;
    private $course;
    private $photo;

    private function formatName($name)
    {
        return ucwords(strtolower($name));
    }

    public function __construct($name, $email, $phone, $course, $photo)
    {
        $this->name = $this->formatName($name);
        $this->email = strtolower($email);
        $this->phone = htmlspecialchars($phone);
        $this->course = $course;
        $this->photo = $photo;
    }

    public function ProcessForm()
    {
        $response = [];
        if ($this->name && $this->email && $this->phone && $this->course) {
            $response['name'] = $this->name;
            $response['email'] = $this->email;
            $response['phone'] = $this->phone;
            $response['course'] = $this->course;

            if ($this->photo && $this->photo['error'] === UPLOAD_ERR_OK) {
                $photoUrl = $this->uploadPhoto();
                $response['photo'] = $photoUrl;
            } else {
                $response['photo'] = null;
            }
        } else {
            $response['error'] = 'Kérjük, töltse ki az összes kötelező mezőt!';
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function uploadPhoto()
    {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $photoName = uniqid() . '_' . basename($this->photo['name']);

        $destination = $uploadDir . $photoName;

        if (move_uploaded_file($this->photo['tmp_name'], $destination)) {
            return $destination;
        } else {
            return null;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null;

    $formHandler = new FormDataHandler($name, $email, $phone, $course, $photo);
    $formHandler->ProcessForm();
} else {
    echo "Hibás kérés!";
}
