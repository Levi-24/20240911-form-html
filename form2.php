<?php

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
        }
        if ($this->photo && $this->photo['error'] === UPLOAD_ERR_OK) {
        }
    }
}
