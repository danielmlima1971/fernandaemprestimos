<?php
	
	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	//print_r($_POST);

	class Mensagem {
		private $nome = null;
		private $contato = null;
		public $status = array('codigo_status' => null , 'descricao_status' => '');

		public function __get($atributo){
			return $this->$atributo;
		}

		public function __set($atributo, $valor){
			return $this->$atributo = $valor;
		}

		public function mensagemValida(){
			if(empty($this->nome) || empty($this->contato)) {
				return false;
			}

			return true;
		}
	}

	$mensagem = new Mensagem();

	$mensagem->__set('nome', $_POST['nome']);
	$mensagem->__set('contato', $_POST['contato']);

	// print_r($mensagem);
	
	if(!$mensagem->mensagemValida()) {
		echo 'Mensagem INVÁLIDA';
		header('location: index.php');
	} 
	//verifica se o SSL foi carregado
	//echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."\n";
	//echo "<hr />";

	//verifica se o servidor do GMAIL está sendo alcançado
	// $host = 'smtp.gmail.com';
	// exec("ping -n 1 -w 1 " . $host, $output, $result);
	// if ($result == 0) {
	//   echo "Servidor do GMAIL está ON";
	//   echo "<hr />";
	// } else {
	//   echo "Servidor do GMAIL está OFF";
	//   echo "<hr />";
	// }



// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;//SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'udemy.cursowebcompleto@gmail.com';                     // SMTP username
    $mail->Password   = 'Bruno1971@';                               // SMTP password
    $mail->SMTPSecure = 'tls';//PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('contato@fernandaemprestimos.com.br', 'Site Fernanda Emprestimos v2.0');
    $mail->addAddress('daniel.lima.1971@gmail.com', 'Daniel Lima');     // Add a recipient
    //$mail->addAddress('fernandamarechal270@outlook.com');               // Name is optional
    $mail->addReplyTo('fernandamarechal270@outlook.com', 'Reply do Cliente');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Novo contato pelo Site';
    $mail->Body    = 'Nova Mansagem recebida pelo site:' . '<br />' . 'Nome: '. $mensagem->nome . '<br />' . 'Contato: ' . $mensagem->contato . '<br />' . 'Entre em contato com o cliente o mais breve possível';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['mensagem_status'] = 'Mensagem enviada com sucesso!! <br /> Aguarde nosso contato!!';

} catch (Exception $e) {
	$mensagem->status['codigo_status'] = 2;
    $mensagem->status['mensagem_status'] = 'Mensagem não enviada. <br /> Tente novamente mais tarde ou entre em contato por telefone ou whatsapp!';
    // Detalhes do erro: ' . $mail->ErrorInfo;
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<!-- Estilo customizado -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">

	<title>Processa Envio</title>
	<!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Botao whatsapp -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

</head>
<body>
	<header><!-- inicio Cabecalho -->
      <nav class="navbar navbar-expand-sm navbar-light bg-warning">
        <div class="container">
          
          <a href="index.php" class="navbar-brand">
            <img src="img/LogoFernandaEmprestimos.png" width="180">
          </a>

          <button class="navbar-toggler" data-toggle="collapse" data-target="#nav-principal">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="nav-principal">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <a href="index.php" class="nav-link">Home</a>
              </li>
              <li class="nav-item">
                <a href="comochegar.html" class="nav-link">Como Chegar</a>
              </li>
            </ul>
          </div>

        </div>
      </nav>
    </header><!--/fim Cabecalho -->

    <div class="container">
    	<div class="py-3 text-center">
			<h2>Fernanda Empréstimos</h2>
			<p class="lead">
				INSS, Federais e Estaduais
			</p>
    	</div>
    	<div class="row">
    		<div class="col-md-12">
    			<?php if($mensagem->status['codigo_status'] == 1) { ?>

    				<div class="container text-center">
    					<h1 class="display-4 text-success"> Sucesso!</h1>
    					<p><?php Echo ($mensagem->status['mensagem_status']) ?></p>
    					<a href="index.php" class="btn btn-success btn-lg mb-5 text-white">Voltar</a>
    				</div>

    			<?php } ?>
    			<?php if($mensagem->status['codigo_status'] == 2) { ?>
    				<div class="container text-center">
    					<h1 class="display-4 text-danger">Ops!</h1>
    					<p><?php Echo ($mensagem->status['mensagem_status']) ?></p>
    					<a href="index.php" class="btn btn-danger btn-lg mb-5 text-white">Voltar</a>
    				</div>
    				

    			<?php } ?>
    			
    		</div>
    		
    	</div>
    </div>



    <footer class="bg-warning"> <!-- Inicio secao rodape -->
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <p>
              <a href="index.php">Home</a>
              <a href="comochegar.html">Como chegar</a>
            </p>
            <p>
              &copy; 2006-2021 / Daniel Lima - daniel.lima.1971@gmail.com - Whatsapp: 
              <a class="whatsapp-link" href="https://web.whatsapp.com/send?phone=5551981831132" target="_blank">
              <i class="fa fa-whatsapp"></i>
              </a>
            </p>
        </div>
      </div>
    </footer> <!-- Final secao rodape -->
</body>
</html>