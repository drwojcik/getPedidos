<?php
// ./errorhandler.php in root of ZF2 app
//adapt from http://stackoverflow.com/questions/277224/how-do-i-catch-a-php-fatal-error
define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR |
        E_COMPILE_ERROR | E_RECOVERABLE_ERROR);

define('DISPLAY_ERRORS', TRUE);
define('ERROR_REPORTING', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

register_shutdown_function('shut');
set_error_handler('handler');

//catch function
function shut()
{
    $error = error_get_last();
    if ($error && ($error['type'] & E_FATAL)) {
        handler($error['type'], $error['message'], $error['file'], $error['line']);
    }
}

function handler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {

    	case E_ERROR: // 1 //
    	    $typestr = 'E_ERROR'; break;
    	case E_WARNING: // 2 //
    	    $typestr = 'E_WARNING'; break;
    	case E_PARSE: // 4 //
    	    $typestr = 'E_PARSE'; break;
    	case E_NOTICE: // 8 //
    	    $typestr = 'E_NOTICE'; break;
    	case E_CORE_ERROR: // 16 //
    	    $typestr = 'E_CORE_ERROR'; break;
    	case E_CORE_WARNING: // 32 //
    	    $typestr = 'E_CORE_WARNING'; break;
    	case E_COMPILE_ERROR: // 64 //
    	    $typestr = 'E_COMPILE_ERROR'; break;
    	case E_CORE_WARNING: // 128 //
    	    $typestr = 'E_COMPILE_WARNING'; break;
    	case E_USER_ERROR: // 256 //
    	    $typestr = 'E_USER_ERROR'; break;
    	case E_USER_WARNING: // 512 //
    	    $typestr = 'E_USER_WARNING'; break;
    	case E_USER_NOTICE: // 1024 //
    	    $typestr = 'E_USER_NOTICE'; break;
    	case E_STRICT: // 2048 //
    	    $typestr = 'E_STRICT'; break;
    	case E_RECOVERABLE_ERROR: // 4096 //
    	    $typestr = 'E_RECOVERABLE_ERROR'; break;
    	case E_DEPRECATED: // 8192 //
    	    $typestr = 'E_DEPRECATED'; break;
    	case E_USER_DEPRECATED: // 16384 //
    	    $typestr = 'E_USER_DEPRECATED'; break;
    }

    if(!($errno & ERROR_REPORTING)) {
        return;
    }

    if (DISPLAY_ERRORS) {
        # ------------------------------------------------------------------------------
        #   Configura os parâmetros para envio de email
        # ------------------------------------------------------------------------------        
        $config = include __DIR__.'/../config/autoload/config.local.php';
        if (!$config){
            die('Arquivo de configuração não encontrado.');            
        }
        
        //Configura os dados para envio do email, de acordo com o arquivo de configuração
        foreach ($config['email']['email_from'] as $email => $nome){
            $mailFromEmail  = $email;
            $mailFromName   = $nome;
        }
        foreach ($config['email']['email_suporte_softwar'] as $email => $nome){
            $mailToEmail  = $email;
            $mailToName   = $nome;
        }
        
        
        # ------------------------------------------------------------------------------
        #   Seta e cria o diretório de logs
        # ------------------------------------------------------------------------------
        if (empty($config['log']['path'])){
            die('Caminho dos arquivos de log não configurado.');
        }
        $dirLog = str_replace('/public', '', $config['log']['path']);
        $dirFileOnDisc = __DIR__ . str_replace('/', '\\', $dirLog);
        
        if (!is_dir($dirFileOnDisc)){
            if (!mkdir($dirFileOnDisc, 0777, true)){
                die('Erro ao gerar arquivo de log. Falha ao criar o diretório.');
            }
        }
        
        
        # ------------------------------------------------------------------------------
        #   Cria o objeto e grava o arquivo de Log
        # ------------------------------------------------------------------------------
        $logger = new \Zend\Log\Logger();
        
        //stream writer
        $filename = date('Ymd_His') . '.txt';
        $_SESSION['erro_nomearquivo'] = $filename;
        
        $writerStream = new \Zend\Log\Writer\Stream($dirFileOnDisc.$filename);
        $logger->addWriter($writerStream);
        
        //Mensagem de log
        $msg = "\nMensagem: {$errstr}";
        $msg.= "\nArquivo: {$errfile}";
        $msg.= "\nLinha: {$errline} - Tipo: {$typestr}";
        $msg.= "\nUrl : {$_SERVER['REQUEST_URI']}";
        
        //log it!
        $logger->crit($msg);
        # ------------------------------------------------------------------------------                
        
        # ------------------------------------------------------------------------------
        #   Configura e envia o email para o suporte
        # ------------------------------------------------------------------------------
        if ($config['log']['notifica_suporte']){            
            //Mail Body
            $text = '<p>Ocorreu um erro crítico em um código do ERP SCAM WEB. Segue abaixo os detalhes do erro:</p><br>';
            $text.= "<p><b>Mensagem: </b>{$errstr}</p>";
            $text.= "<p><b>Arquivo: </b>{$errfile} - <b>Tipo do erro:</b> {$typestr}</p>";
            $text.= "<p><b>Linha: </b>{$errline}</p>";
            $text.= "<p><b>Url Acessada: </b>{$_SERVER['REQUEST_URI']}</p>";
            
            $html = new \Zend\Mime\Part($text);
            $html->type = 'text/html';
            $html->charset = 'UTF-8';
            
            $body = new \Zend\Mime\Message;
            $body->setParts(array($html));
                    
            //mail writer
            $message = new \Zend\Mail\Message();
            $message->setFrom($mailFromEmail, $mailFromName);
            $message->addTo($mailToEmail, $mailToName);
            $message->setSubject("ERP SCAM WEB - Erro Php");
            $message->setBody($body);
                    
            $transport = new \Zend\Mail\Transport\Smtp();
            $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                'host' => $config['email']['smtp']['host'],
                'port'=> $config['email']['smtp']['port'],
                'connection_class' => 'login',
                'connection_config' => array(
                    'username' => $config['email']['smtp']['user'],
                    'password' => $config['email']['smtp']['pass'],
                ),
            ));
            $transport->setOptions($smtpOptions);
            $transport->send($message);
        }                
        # ------------------------------------------------------------------------------
        
        //show user that's the site is down right now
        include __DIR__.'/../module/Basico/view/basico/error-page/erro-critico.phtml';
        die;
    }
}
