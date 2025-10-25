<?php

class Contacto {
    private $telefone;
    private $email;
    private $web_site;
    private $descricao;

    public function __construct($data = []) {
        foreach ($data as $key => $value)
            if (property_exists($this, $key))
                $this->$key = $value;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getWebSite() {
        return $this->web_site;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
        return $this;
    }
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }
    public function setWebSite($web_site) {
        $this->web_site = $web_site;
        return $this;
    }  
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        return $this;
    }
}
