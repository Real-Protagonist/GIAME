<?php
    class Usuarios {
        private $email_usuario;
        private $senha_usuario;
        private $last_login;

        public function __construct($data = []) {
            foreach ($data as $key => $value)
                if (property_exists($this, $key))
                    $this->$key = $value;
        }

        public function get_email_usuario() {
            return $this->email_usuario;
        }
        public function get_senha_usuario() {
            return $this->senha_usuario;
        }
        public function get_last_login() {
            return $this->last_login;
        }
        public function set_email_usuario($email_usuario) {
            $this->email_usuario = $email_usuario;
        }   
        public function set_senha_usuario($senha_usuario) {
            $this->senha_usuario = $senha_usuario;
        }   
        public function set_last_login($last_login) {
            $this->last_login = $last_login;
        }
    }