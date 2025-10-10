<?php
    class Pessoa {
        private $primeiro_nome;
        private $ultimo_nome;
        private $dt_nascimento;
        private $nacionalidade;
        private $nif;

        public function __construct($data = []) {
           foreach ($data as $key => $value)
                if (property_exists($this, $key))
                    $this->$key = $value;
        }

        public function getPrimeiroNome() {
            return $this->primeiro_nome;
        }

        public function setPrimeiroNome($primeiro_nome) {
            $this->primeiro_nome = $primeiro_nome;
            return $this;
        }

        public function getUltimoNome() {
            return $this->ultimo_nome;
        }

        public function setUltimoNome($ultimo_nome) {
            $this->ultimo_nome = $ultimo_nome;
            return $this;
        }

        public function getDtNascimento() {
            return $this->dt_nascimento;
        }

        public function setDtNascimento($dt_nascimento) {
            $this->dt_nascimento = $dt_nascimento;
            return $this;
        }

        public function getNacionalidade() {
            return $this->nacionalidade;
        }

        public function setNacionalidade($nacionalidade) {
            $this->nacionalidade = $nacionalidade;
            return $this;
        }

        public function getNif() {
            return $this->nif;
        }

        public function setNif($nif) {
            $this->nif = $nif;
            return $this;
        }
    }