<?php

namespace Basico\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response\Stream;
use Zend\Http\Headers;

class LogDownloadController extends AbstractActionController {	
	
    public function indexAction(){
        //captura o nome do arquivo enviado na url
        $nomeArquivo = $this->params('filename');
        
        //busca os dados de configuração
        $config = $this->getServiceLocator()->get('config');
        $filePath = getcwd(). str_replace('/', '\\', $config['log']['path']);
        $fileFullPath = $filePath.$nomeArquivo;
        
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
            die('Arquivo de log não encontrado.');
        }                
    }
    
}
