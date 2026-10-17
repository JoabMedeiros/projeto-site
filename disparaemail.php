<?php
// Importa as classes do PHPMailer para o código
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carrega o autoloader do Composer (necessário para achar o PHPMailer na pasta vendor)
require 'vendor/autoload.php';

// Verifica se o formulário foi enviado pelo botão (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura os dados do formulário
    $nome = $_POST['tNome'];
    $email_visitante = $_POST['tEmail'];
    $whatsapp = $_POST['tWhats'];
    $mensagem = $_POST['cTexto'];

    // Cria uma nova instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        // ==========================================
        // CONFIGURAÇÕES DO SERVIDOR (GMAIL)
        // ==========================================
        $mail->isSMTP();                                            // Define que usará SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Servidor do Gmail
        $mail->SMTPAuth   = true;                                   // Habilita autenticação
        $mail->Username   = 'seu_novo_email@gmail.com';             // SEU E-MAIL (O QUE VAI ENVIAR)
        $mail->Password   = 'sua_senha_de_app_aqui_sem_espacos';    // SUA SENHA DE APP DO GOOGLE
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Tipo de segurança
        $mail->Port       = 587;                                    // Porta do Gmail
        $mail->CharSet    = 'UTF-8';                                // Para não dar erro em acentos

        // ==========================================
        // REMETENTE E DESTINATÁRIOS
        // ==========================================
        // Quem está enviando (o e-mail do seu robô)
        $mail->setFrom('seu_novo_email@gmail.com', 'Site Portfólio');
        
        // Para quem vai a mensagem (pode ser o seu e-mail pessoal ou o da UEPB)
        $mail->addAddress('joab.medeiros@aluno.uepb.edu.br', 'Joab Medeiros'); 
        
        // Se você clicar em "Responder" no seu Gmail, a resposta vai para o e-mail do visitante
        $mail->addReplyTo($email_visitante, $nome);

        // ==========================================
        // CONTEÚDO DO E-MAIL
        // ==========================================
        $mail->isHTML(true); // Define que o e-mail aceita formatação HTML
        $mail->Subject = 'Novo contato no Portfólio de: ' . $nome;
        
        // Montando o corpo do e-mail com quebras de linha (<br>)
        $corpo = "<h3>Você recebeu uma nova mensagem!</h3>";
        $corpo .= "<b>Nome:</b> {$nome}<br>";
        $corpo .= "<b>E-mail:</b> {$email_visitante}<br>";
        $corpo .= "<b>WhatsApp:</b> {$whatsapp}<br><br>";
        $corpo .= "<b>Mensagem:</b><br>" . nl2br($mensagem); // nl2br converte os Enters do texto em <br>

        $mail->Body = $corpo;

        // Envia o e-mail
        $mail->send();
        
        // Exibe o alerta e redireciona (Comportamento que você pediu)
        echo "<script>
                alert('Mensagem enviada com sucesso!');
                window.location.href = 'index.html';
              </script>";

    } catch (Exception $e) {
        // Se der erro de senha incorreta ou falta de internet, avisa o usuário
        echo "<script>
                alert('Erro ao enviar a mensagem. Detalhe do erro: {$mail->ErrorInfo}');
                window.history.back(); // Volta para o formulário
              </script>";
    }
} else {
    // Se alguém tentar acessar a URL do disparaemail.php direto, joga pro Início
    header("Location: index.html");
}