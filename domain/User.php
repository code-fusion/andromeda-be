<?php

namespace domain\User;
require_once "../class/AuthHelper.class.php";
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

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setActiveUser($activeUser) {
        $this->activeUser = $activeUser;
    }

    /**
     * @param $email
     * @param $password
     * @return string
     */
    public function login($email, $password) {
        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $authLogin = new \AuthHelper();

        $loginResponse = $authLogin->loginAuth($credentials);

        return $loginResponse;
    }
}