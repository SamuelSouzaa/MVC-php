<?php

    namespace App\Utils;

    
    class View{
        /**
         * Variáveis padrão 
         * da View
         */
        private static $vars = [];
        /**
         * Método responsável
         * por definir os dados 
         * iniciais da classe
         */
        public static function init($vars = []){
            self::$vars = $vars;
        }
        private static function getContentView($view){
            $file = __DIR__.'/../../resources/view/'.$view.'.html';
            return file_exists($file) ? file_get_contents($file) : '';
        }
        public static function render($view, $vars = []){
            $contentView = self::getContentView($view);

            //Merge de variáveis da view
            $vars = array_merge(self::$vars,$vars);

            $keys = array_keys($vars);
            $keys = array_map(function($items){
                return '{{'.$items.'}}';
            }, $keys);
    
            return str_replace($keys,array_values($vars),$contentView);
        }

    }

?>