<?php
namespace Basico\Email;

use Zend\View\Renderer\PhpRenderer;

//Mail
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;

class Email {

    /**
     * @var Zend\Mail\Transport\SmtpOptions
     */
    private $_SmtpOptions;
    private $_SmtpHost;    
    private $_SmtpPort;
    private $_SmtpUser;
    private $_SmtpPass;    
    
    /**
     * @var Zend\View\Renderer\PhpRenderer
     */
    private $_ViewRenderer;
    private $_MailIsTemplate;
    private $_TemplateFile;
    private $_TemplateParams;    
    private $_FileAttachments;    
    private $_PathAttachment;
    private $_FileName;    
    
    /**
     * @var Zend\Mime\Part
     */
    private $_MailMimePart;
    
    /**
     * @var Zend\Mime\Message
     */
    private $_MimeMessage;        
    
    private $_MailFrom;
    private $_MailFromName;
    private $_MailTo;
    private $_MailSubject;
    private $_MailBody;
    private $_MailBcc;
    private $_MailIsHtml;
    private $_MailCc;
    private $_MailReplyTo;
    
    private $_Config;
    
    public function __construct(PhpRenderer $ViewRenderer, $Config){
        $this->_ViewRenderer 	= $ViewRenderer;
        $this->_Config			= $Config;
    }
    
    public function send(){
    	
        $this->prepare();
        
        //verifica se está utilizando template
        if ($this->_MailIsTemplate){
            // mail body de um template
            $content = $this->_ViewRenderer->render($this->_TemplateFile, $this->_TemplateParams);
            if($this->_FileAttachments){
            	// make a header as html whit attach
            	$html = new MimePart($content);
            	$html->type = "text/html";
            	$html->charset = "UTF-8";
            	//Alteração em 19/06/2018 por Diego Wojcik #17694 para anexar mais de um arquivo
                //Reparte os anexos divididos por ';'
                $arrayAnexo = explode(";", $this->_PathAttachment);
                $arrayNomeArquivo = explode(";", $this->_FileName);
                $body = new MimeMessage();
                if($arrayAnexo){
                    for($i=0; $i < count($arrayAnexo) ; $i++){
                        if($arrayAnexo[$i] != ''){
                            chmod($arrayAnexo[$i], 0775);
                            $fileContents = fopen($arrayAnexo[$i], 'r');
                            $attachment = new MimePart($fileContents);
                            $attachment->type = 'application/pdf';
                            $attachment->filename = $arrayNomeArquivo[$i];
                            $attachment->encoding = Mime::ENCODING_BASE64;
                            $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;

                               //Vincula os Anexos ao inver se setar fixo Alterada da função setParts para a addParts
                            //$body->setParts(array($html, $attachment));
                            $body->addPart($attachment);
                        }
                    }
                    $body->addPart($html);
                }else{
                    chmod($this->_PathAttachment, 0775);
                    $fileContents = fopen($this->_PathAttachment, 'r');
                    $attachment = new MimePart($fileContents);
                    $attachment->type = 'application/pdf';
                    $attachment->filename = $this->_FileName;
                    $attachment->encoding = Mime::ENCODING_BASE64;
                    $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;
                    //$body = new MimeMessage();
                    $body->setParts(array($html, $attachment));
                }




            }else{
            	// make a header as html
            	$html = new MimePart($content);
            	$html->type = "text/html";
            	$html->charset = "UTF-8";
            	
            	$body = new MimeMessage();
            	$body->setParts(array($html));
            }
            
        }
        else if ($this->_MailIsHtml){
            // make a header as html
            $html = new MimePart($this->_MailBody);
            $html->type = "text/html";
            $body = new MimeMessage();
            $body->setParts(array($html));
        }
        else {
            $body = $this->_MailBody;
        }
               
        $mail = new Message();
        $mail->setBody($body);
        $mail->setFrom($this->_MailFrom,$this->_MailFromName);
        $mail->setSubject($this->_MailSubject);
        foreach ($this->_MailTo as $destinatario){
            $mail->addTo($destinatario['Email'], $destinatario['Nome']);
        }
        if (!empty($this->_MailBcc)){
            foreach ($this->_MailBcc as $copiaOcultaEmail){
                $mail->addBcc($copiaOcultaEmail);
            }
        }
        //Alterado em 23/06/2017 por Gabriel Henrique #15208 - Verifica se existe um email específico para ser respondido
        if(!empty($this->_MailReplyTo)){
        	$mail->setReplyTo($this->_MailReplyTo);
        }
        $mail->setEncoding('UTF-8');

        $transport = new Smtp($this->_SmtpOptions);

        //ficava enviando emails repetidos
        $this->_MailTo = null;
        $this->_MailBcc = null;
        $this->_MailReplyTo = null;
        $transport->send($mail);
    }
    
    /**
     * Verifica se os dados necessários para o envio estão configurados.
     */
    private function prepare(){
    	if (empty($this->_SmtpHost) || empty($this->_SmtpUser) || empty($this->_SmtpPass))
    	    $this->setSmtpOptionsFromConfigFile();
    	
    	if (empty($this->_MailFrom))
    	    $this->setFromByConfigFile();
    	
    	if (empty($this->_MailTo))
    	    $this->setToFromConfigFile();
    	
    	if (empty($this->_MailSubject))
    	    throw new \Exception('Assunto não informado. Utilize o método setTo() ou addTo().');
    	
    	//seta a logo do email
    	$this->setUrlLogo();
    	$this->setLogoSoftwar();
    	$this->setLogoAssinatura();
    }
    
    private function setUrlLogo(){
        $this->_TemplateParams['UrlLogo'] = $this->_Config['email']['logo']['url'];
    }
    private function setLogoSoftwar(){
        $this->_TemplateParams['UrlLogoSoftwar'] = $this->_Config['email']['logo']['logosoftwar'];
    }
    private function setLogoAssinatura(){
        $this->_TemplateParams['UrlLogoAssinatura'] = $this->_Config['email']['logo']['assinatura'];
    }
    
    /**
     * Seta os dados para autenticação SMTP.
     * 
     * @param string $Host
     * @param string $User
     * @param string $Pass
     * @param int $Port (optional) Defaullt 587.
     */
    public function setSmtpOptions($Host, $User, $Pass, $Port = null){
        $this->_SmtpHost = $Host;
        $this->_SmtpUser = $User;
        $this->_SmtpPass = $Pass;
        $this->_SmtpPort = (empty($Port) ? 587 : $Port);

        $this->_SmtpOptions = new SmtpOptions(array(
		            'host' => $this->_SmtpHost,
		            'port'=> $this->_SmtpPort,

		            'connection_class' => 'login',
		            'connection_config' => array(
		                    'username' => $this->_SmtpUser,
		                    'password' => $this->_SmtpPass,
		                    //'ssl' => 'tls',

		            ),




	    ));
    }
    
    /**
     * Configura os dados de autenticação SMTP de acordo com o que está definido no arquivo de configuração.
     *  
     * @throws \Exception
     */
    protected function setSmtpOptionsFromConfigFile(){
        if (empty($this->_Config['email']['smtp'])){
            throw new \Exception('Os dados de autenticação SMTP não foram e informados e os mesmos também
                    				não foram encontrados no arquivo de configuração. 
                    				Você pode utilizar o método setSmtpOptions() para informar os dados de autenticação.');
        } else {
            $this->setSmtpOptions($this->_Config['email']['smtp']['host'], 
                    				$this->_Config['email']['smtp']['user'],
                    				$this->_Config['email']['smtp']['pass']);
        }
    }
    
    /**
     * Seta um template html para o email.
     * 
     * @param string $TemplateFile
     * @param array $Params (optional)
     */
    public function setEmailTemplate($TemplateFile, $Params = null){
        $this->_TemplateFile 	= $TemplateFile;

        $this->_TemplateParams	= (empty($Params) ? array() : $Params);
        $this->_MailIsTemplate	= true;
    }
  
    public function setAttachment($PathFile, $File){
    	$this->_FileAttachments = true;
    	$this->_PathAttachment = $PathFile;
    	$this->_FileName = $File;
    }
    
    /**
     * Seta os dados do remetente do email.
     * 
     * @param string $Email
     * @param string $Name
     */
    public function setFrom($Email, $Name = null){
        $this->_MailFrom 		= $Email;
        $this->_MailFromName 	= $Name; 
    }
    
    /**
     * Adiciona o remetente de acordo com o que está definido no arquivo de configuração.
     * 
     * @throws \Exception
     */
    protected function setFromByConfigFile(){
        if (empty($this->_Config['email']['email_from'])){
            throw new \Exception('O remetente não foi e informado e o mesmo
                    também não foi encontrado no arquivo de configuração.
                    Você pode utilizar o método setFrom() para informar o remetente.');
        } else {
            foreach ($this->_Config['email']['email_from'] as $email => $nome){
                $this->setFrom($email, $nome);
            }
        }
    }
    
    /**
     * Dados do destinatário do email.
     * Envia apenas para UM destinatário. Para enviar para MÚLTIPLOS destinatários utilize o método addTo().
     *
     * @param string $Email
     */
    public function setTo($Email){
        $this->_MailTo = null;
        $this->_MailTo[] = $Email;
    }
    
    /**
     * Adiciona um novo destinatário do email.
     *
     * @param string $Email
     */
    public function addTo($Email, $Nome = null){
        #$this->_MailTo[] = $Email;
        $this->_MailTo[] = array('Email' => $Email, 'Nome' => $Nome);
    }
    
    /**
     * Adiciona os destinatários definidos no arquivo de configuração.
     * 
     * @throws \Exception
     */
    protected function setToFromConfigFile(){
        if (empty($this->_Config['email']['email_aviso'])){
            throw new \Exception('O destinatário não foi e informado e o mesmo 
                    				também não foi encontrado no arquivo de configuração.
                    				Você pode utilizar o método setTo() ou addTo() para informar o(s) destinatário(s).');
        } else {
            foreach ($this->_Config['email']['email_aviso'] as $email => $nome){
                $this->addTo($email, $nome);
            }
        }
    }
    
    /**
     * Assunto do email.
     * 
     * @param srting $Subject
     */
    public function setSubject($Subject){
        $this->_MailSubject = $Subject;
    }
    
    /**
     * Adiciona uma cópia oculta ao email.
     *
     * @param string $Email
     */
    public function addBcc($Email){
        $this->_MailBcc[] = $Email;
    }
    public function addCc($Email){
    	$this->_MailCc[] = $Email;
    }
    
    /**
     * Indica que o conteúdo do emai será do tipo html.
     * 
     * @param boolean $flag
     */
    public function setToHtml($flag){
    	$this->_MailIsHtml = (boolean) $flag;
    }
    
   
    public function setReplyTo($Email){
    	$this->_MailReplyTo[] = $Email;
    }
}

?>