<?php

namespace domain\User;

use DomainObject;
class User extends DomainObject
{
    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $name;
    protected $lastName;
    protected $telephone;
    protected $activeUser;

    public function __construct($id = '',$username, $password, $email, $name, $lastName, $telephone = '', $activeUser = true)
    {
        parent::__construct();

        if (empty($id)) {
            //nuevo usuario
            $this->setPassword($password);
        } else {
            // usuario existente
            $this->setId($id);
            $this->password = $this->setPassword($password); //siempre y cuando no venga hasheado
        }
        $this->setName($name);
        $this->setLastname($lastName);
        $this->setEmail($email);

    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPasswordHash() {
        return $this->password;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setLastName($lastname) {
        $this->lastName = $lastname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }
}