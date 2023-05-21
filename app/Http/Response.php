<?php

namespace App\Http;

class Response{
    /**
     * Código do Status HTTP
     */
    private $httpCode = 200;
    /**
     * Retorna headers do response
     *  */    
    private $headers = [];
    /**
     * Tipo de conteúdo retornado;
     */
    private $contentType = 'text/html';
    /**
     * Conteúdo do Response
     */
    private $content;
    /**
     * Método responsável por iniciar a classe
     * e definir os valores
     */
    public function __construct($httpCode,$content,$contentType='text/html'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }
    /**
     * Método responsável por alterar
     * o contentType do resnpose
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type',$contentType);
    }
    /**
     * Método responsável por adicionar
     * uma registro de cabeçalho do
     * response
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }
   /**
    * Método responsável
    por enviar os headers
    para o navegador
    */
    private function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);

        //Enviar headers
        foreach($this->headers as $key=>$value){
            header($key.': '.$value);
        }
    }
    /**
     * Método responsável por
     * enviar a resposta para 
     * o usuário
     */
    public function sendResponse(){
        //Envia os headers
        $this->sendHeaders();

        //Imprimi o conteúdo
        switch($this->contentType){
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}