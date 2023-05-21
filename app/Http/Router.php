<?php

namespace App\Http;

use \ReflectionFunction;
use \Closure;
use \Exception;
use \App\Http\Request;
use \App\Http\Response;

class Router{

    /**
     * URL completa do projeto
     * RAÍZ
     */
    private $url = '';
    /**
     * Prefixo de todas as rotas
     */
    private $prefix = '';
    /**
     * Guarda todas as rotas
     * fazendo indíce de rotas
     */
    private $routes = [];
    /**
     * Instância de Request
     */
    private $request;
    /**
     * Método responsável por iniciar a classe
     */
    public function __construct($url){
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }
    /**
     * Método responsável por definir o prefixo
     * das rotas
     */
    private function setPrefix(){
        //Informações da URL atual
        $parseUrl = parse_url($this->url);
        //Define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }
    /**
     * Método responsável por adicionar
     * uma rota na classe
     */
    private function addRoute($method, $route, $params = []){
        //VALIDAÇÃO DOS PARÂMETROS  
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        //Variáveis da rota
        $params['variables'] = [];

        //Padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable,$route,$matches)){
                $route = preg_replace($patternVariable,'(.*?)',$route);
                $params['variables'] = $matches[1];
        }


        //Padrão de validação da URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';
        //Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }
    /**
     *Método responsável
     por definir uma rota de GET
     */
    public function get($route, $params = []){
        return $this->addRoute('GET',$route,$params);
    }

    public function post($route,$params = []){
        return $this->addRoute('POST',$route,$params);
    }

    public function put($route,$params = []){
        return $this->addRoute('PUT',$route,$params);
    }

    public function delete($route,$params = []){
        return $this->addRoute('DELETE',$route,$params);
    }


    /**
     * Método responsável por retornar a URI
     * desconsiderando o prefixo
     */
    private function getUri(){
        //Uri da request
        $uri = $this->request->getUri();
        //Fatia a Uri com prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];
        //Rtorna a uri sem prefixo
        return end($xUri);
    }
    private function getRoute(){
        /**
         * retorna a URI
         */
        $uri = $this->getUri();
        //Metódo da URI
        $httpMethod = $this->request->getHttpMethod();
        //Valida as rotas
        foreach($this->routes as $patternRoute=>$methods){
            //Veriifica se a URI bate com o padrão
            if(preg_match($patternRoute,$uri,$matches)){
                //Verifica o método
                if(isset($methods[$httpMethod])){
                    //Remove a primeira posição
                    unset($matches[0]);
                    //Retorno dos parametros da rota

                    //Variáveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                    return $methods[$httpMethod];
                }
                //Método não permitido definido
                throw new Exception("Método não permitido", 405);
            }
        }
        //URL não encontrada
        throw new Exception("URL não encontrada", 404);
    }
    /**
     * Método responsável
     * por executar a rota
     * atual
     */
    public function run(){
        try{
            //Pega a rota atual
            $route = $this->getRoute();
        
            //Verifica o controlador
            if(!isset($route['controller'])){
                throw new Exception("A URL não pode ser processada", 500);
            }

            //Argumentos da função
            $args = [];

            //Reflection
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            //Retorna a execução da função
            return call_user_func_array($route['controller'],$args);

        }catch(Exception $e){
            return new Response($e->getCode(),$e->getMessage());
        }
    }

}