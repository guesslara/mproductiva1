<?php

    class guardarArchivoMatriz{
        
        
        public function guardarMatriz($nombreArchivo,$idUsuario,$contenidoArchivo){
            //procedimiento para guardar el archivo en el directorio
            $path="../../reportesUsuario/";
            if(is_dir($path)){
                echo "procedimiento para guardar";
                
            }else{
                echo "El directorio especificado no es un Directorio Valido";
            }
        }
    }

?>