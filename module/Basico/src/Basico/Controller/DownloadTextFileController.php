<?php

namespace Basico\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;
	  
class DownloadTextFileController extends AbstractActionController {	
    public function indexAction(){
        //captura o nome do arquivo enviado na url
        
        $diretorioGeral = $this->params('filename');
        $arrayDiretorios = explode('--', $diretorioGeral);
        ///public/upload/pasta/
        $arrayInvertido =  array_reverse($arrayDiretorios);
        $nomeArquivo = $arrayInvertido[0];
        $fileFullPath = implode('/', $arrayDiretorios);
        $fileFullPath = 'public/'.$fileFullPath;
        
        //verifica se o arquivo existe e o envia para download
        if(is_file($fileFullPath)){
            $response = new Stream();
            $response->setStream(fopen($fileFullPath, 'r'));
            $response->setStatusCode(200);
            
            $headers = new Headers();
            $headers->addHeaderLine('Content-Type', 'text/plain')
                    ->addHeaderLine('Content-Disposition', 'attachment; filename="'.$nomeArquivo.'"')
                    ->addHeaderLine('Content-Length', filesize($fileFullPath));
            
            $response->setHeaders($headers);
            
            return $response;
        }
        else {
            die('Arquivo txt não encontrado.');
        }                
    }
    
}
