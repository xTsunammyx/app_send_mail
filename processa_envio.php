<?php

require "./bibliotecas/PHPMailer/Exception.php";
require "./bibliotecas/PHPMailer/OAuth.php";
require "./bibliotecas/PHPMailer/PHPMailer.php";
require "./bibliotecas/PHPMailer/POP3.php";
require "./bibliotecas/PHPMailer/SMTP.php";

//importando namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//print_r($_POST);

class Mensagem{
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = ['codigo' => null, 'descricao' => ""];

    public function __get($atributo){
        return $this->$atributo;
    }
    public function __set($atributo,$valor){
        $this->$atributo = $valor;
    }
    public function mensagemValida(){
        if(empty($this->para) || empty($this->assunto)|| empty($this->mensagem)){
            return false;
        }
        return true;
    }
}
$mensagem = new Mensagem();
$mensagem->__set("para", $_POST["para"]);
$mensagem->__set("assunto", $_POST["assunto"]);
$mensagem->__set("mensagem", $_POST["mensagem"]);

/*echo "<pre>";
print_r($mensagem);
echo "</pre>";*/

if(!$mensagem->mensagemValida()){
    echo "mensagem invalida";
    header("location:index.php");
    //die(); //mata o processamento do script
}

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'xtsunammyx@gmail.com';                     //SMTP username
    $mail->Password   = 'lkxe ajuv xtdw fqpq';                               //SMTP password
    $mail->SMTPSecure = 'tls';
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('xtsunammyx@gmail.com', 'Remetente');
    $mail->addAddress($mensagem->__get("para"));     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information'); //contato padrão
   // $mail->addCC('cc@example.com'); //copia
   // $mail->addBCC('bcc@example.com'); //copia oculta

    //Attachments/ adicionar anexos no email
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get("assunto"); //assunto do email
    $mail->Body    = $mensagem->__get("mensagem");
    $mail->AltBody = "É necessário usar um client que suporte HTML para ter acesso total ao conteúdo"; //aqui não tem suporte para html

    $mail->send();

    $mensagem->status['codigo'] = 1;
	$mensagem->status['descricao'] = 'E-mail enviado com sucesso';

} catch (Exception $e) {

    $mesagem->status["codigo"] = 2;
    $mesagem->status["descricao"] = "Não foi possível enviar esse e-mail. Detalhes do erro:". $mail->ErrorInfo;
}//com o PHP(backend finalizado), abaixo terá uma pagina html para deixar a página mais agradavel
?>

<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>

	<body>

		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">

					<?php if($mensagem->status['codigo'] == 1) { ?>

						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $mensagem->status['descricao'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

					<?php if($mensagem->status['codigo'] == 2) { ?>

						<div class="container">
							<h1 class="display-4 text-danger">Ops!</h1>
							<p><?= $mensagem->status['descricao'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>

	</body>
</html>
