<?php
class Empresa {
    private $nome;
    private $nif;
    private $tipo;
    private $particularidade;
    private $objectivo_social;
    private $data_constituicao;
    private $tipo_empresa;
    private $representante_legal;
    private $sector_atividade;
    private $tamanho_empresa;
    private $capital_social;
    private $pessoa_id;
    private $contacto;
    private $endereco;

    public function __construct($data = []) {
        foreach ($data as $key => $value)
            if (property_exists($this, $key))
                $this->$key = $value;
    }

    public function getNif() {
        return $this->nif;
    }
    public function setNif($nif) {
        $this->nif = $nif;
        return $this;
    }
    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }
    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        return $this;
    }
    public function getObjectivoSocial() {
        return $this->objectivo_social;
    }
    public function setObjectivoSocial($objectivo_social) {
        $this->objectivo_social = $objectivo_social;
        return $this;
    }
    public function getDataConstituicao() {
        return $this->data_constituicao;
    }
    public function setDataConstituicao($data_constituicao) {
        $this->data_constituicao = $data_constituicao;
        return $this;
    }
    public function getTipoEmpresa() {
        return $this->tipo_empresa;
    }
    public function setTipoEmpresa($tipo_empresa) {
        $this->tipo_empresa = $tipo_empresa;
        return $this;
    }
    public function getRepresentanteLegal() {
        return $this->representante_legal;
    }
    public function setRepresentanteLegal($representante_legal) {
        $this->representante_legal = $representante_legal;
        return $this;
    }
    public function getSectorAtividade() {
        return $this->sector_atividade;
    }
    public function setSectorAtividade($sector_atividade) {
        $this->sector_atividade = $sector_atividade;
        return $this;
    }
    public function getTamanhoEmpresa() {
        return $this->tamanho_empresa;
    }
    public function setTamanhoEmpresa($tamanho_empresa) {
        $this->tamanho_empresa = $tamanho_empresa;
        return $this;
    }
    public function getCapitalSocial() {
        return $this->capital_social;
    }
    public function setCapitalSocial($capital_social) {
        $this->capital_social = $capital_social;
        return $this;
    }

    public function getParticularidade() {
        return $this->particularidade;
    }
    public function setParticularidade($particularidade) {
        $this->particularidade = $particularidade;
        return $this;
    }
    public function getContacto() {
        return $this->contacto;
    }
    public function setContacto($contacto) {
        $this->contacto = $contacto;
        return $this;
    }
    public function getEndereco() {
        return $this->endereco;
    }
    public function setEndereco($endereco) {
        $this->endereco = $endereco;
        return $this;
    }
}