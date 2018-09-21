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

    public function __construct($id = null,$username, $password, $email, $name, $lastName, $telephone = '', $activeUser)
    {
        parent::__construct();

        if (empty($id)) {
            //nuevo usuario
            $this->setPassword($password);
        } else {
            // usuario existente
            $this->setId($id);
        }
        $this->setPassword($password); //siempre y cuando no venga hasheado
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setName($name);
        $this->setLastname($lastName);
        $this->setTelephone($telephone);
        $this->setActiveUser($activeUser);
    }

    public function getId() {
        return $this->id;
    }

    public function getPasswordHash() {
        return $this->password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getActiveUser(){
        return $this->activeUser;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setLastName($lastname) {
        $this->lastName = $lastname;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setActiveUser($activeUser) {
        $this->activeUser = $activeUser;
    }
}