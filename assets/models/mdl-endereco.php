<?php
    class Endereco {
        private $morada;
        private $municipio;
        private $bairro;
        private $provincia;

        public function __construct($data = []) {
           foreach ($data as $key => $value)
                if (property_exists($this, $key))
                    $this->$key = $value;
        }

        public function getMorada() {
            return $this->morada;
        }

        public function setMorada($morada) {
            $this->morada = $morada;
            return $this;
        }

        public function getMunicipio() {
            return $this->municipio;
        }

        public function setMunicipio($municipio) {
            $this->municipio = $municipio;
            return $this;
        }

        public function getBairro() {
            return $this->bairro;
        }

        public function setBairro($bairro) {
            $this->bairro = $bairro;
            return $this;
        }

        public function getProvincia() {
            return $this->provincia;
        }

        public function setProvincia($provincia) {
            $this->provincia = $provincia;
            return $this;
        }
    }