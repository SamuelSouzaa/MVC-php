<?php

namespace App\Http;

class Request{
    /**
     * Método HTTP da requisição
     */
    private $httpMethod;
    /**
     * URI da página, a Rota
     */
    private $uri;
    /**
     * Parâmetros GET da URL
     */
    private $queryParams = [];
    /**
     * Variáveis que recebemos
     * no POST da página
     */
    private $postVars = [];
    /**
     * Cabeçalhos da requisição
     */
    private $headers = [];

    public function __construct(){
        $this->queryParams = $_GET ?? '';
        $this->postVars = $_POST ?? '';
        $this->headers = getallheaders();
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    }
    /**
     * Método responsável por retornar
     * o método HTTP da requisição
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }
    /**
     * Método responsável por retornar a URI 
     * da requisição
     */
    public function getUri(){
        return $this->uri;
    }
    /**
     * Método responsável por 
     * retornar os headers da 
     * requisição
     */
    public function getHeaders(){
        return $this->headers;
    }
    /**
     * Método responsável por retornar os 
     * parâmetros da URL da requisição
     */
    public function getQueryParams(){
        return $this->queryParams;
    }
    /**
     * Método responsável por
     * retornar as variaveis POST da requisição
     */
    public function getPostVars(){
        return $this->postVars;
    }
}