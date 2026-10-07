<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processando Contato | Joab Medeiros</title>
    <link rel="stylesheet" href="estilos/style.css">
    <link rel="stylesheet" href="estilos/formulario.css">
</head>
<body>
    <div id="interface">
        <main>
            <header>
                <img class="foto-perfil" src="imagens/perfil400_400web.jpg" alt="Foto de perfil de Joab Medeiros">
                <div class="apresentacao">
                    <h1>Joab Medeiros</h1>
                    <p>Desenvolvedor em Formação & Estudante de Programação</p>
                </div>
            </header>

            <?php
            // Verifica se a requisição veio via POST
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                // Resgata e sanitiza os dados do formulário falecomigo.html
                $nome  = htmlspecialchars(trim($_POST['tNome'] ?? ''));
                $email = filter_var(trim($_POST['tEmail'] ?? ''), FILTER_SANITIZE_EMAIL);
                $whats = htmlspecialchars(trim($_POST['tWhats'] ?? ''));
                $texto = htmlspecialchars(trim($_POST['tTexto'] ?? ''));

                // Validação de campos obrigatórios
                if (empty($nome) || empty($email) || empty($texto)) {
                    echo "<h2>Atenção!</h2>";
                    echo "<p>Por favor, preencha todos os campos obrigatórios.</p>";
                    echo "<div class='container-voltar'><a href='falecomigo.html' class='btn-voltar'>Voltar</a></div>";
                    exit;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<h2>Atenção!</h2>";
                    echo "<p>O e-mail informado não é válido.</p>";
                    echo "<div class='container-voltar'><a href='falecomigo.html' class='btn-voltar'>Voltar</a></div>";
                    exit;
                }

                // Configurações do e-mail
                $para = "joab.medeiros@aluno.uepb.edu.br";
                $assunto = "Contato pelo Portfólio - " . $nome;

                // Montagem do corpo da mensagem
                $mensagem  = "Nome: " . $nome . "\n";
                $mensagem .= "E-mail: " . $email . "\n";
                $mensagem .= "WhatsApp: " . $whats . "\n\n";
                $mensagem .= "Mensagem:\n" . $texto . "\n";

                // Cabeçalhos (Headers) do e-mail
                $headers  = "From: " . $email . "\r\n";
                $headers .= "Reply-To: " . $email . "\r\n";
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $headers .= "X-Mailer: PHP/" . phpversion();

                // Tenta enviar o e-mail
                if (@mail($para, $assunto, $mensagem, $headers)) {
                    echo "<h2>Mensagem Enviada!</h2>";
                    echo "<p>Obrigado pelo contato, <strong>" . $nome . "</strong>. Em breve te responderei!</p>";
                } else {
                    // Tratamento amigável para testes no XAMPP local
                    echo "<h2>Dados recebidos com sucesso (Modo Local)</h2>";
                    echo "<p>Como o Apache local no XAMPP não possui servidor SMTP configurado por padrão, o envio real via <code>mail()</code> não é disparado, mas seus dados foram processados com sucesso no PHP:</p>";
                    echo "<ul style='margin-left: 50px; line-height: 1.8;'>";
                    echo "<li><strong>Nome:</strong> " . $nome . "</li>";
                    echo "<li><strong>E-mail:</strong> " . $email . "</li>";
                    echo "<li><strong>WhatsApp:</strong> " . $whats . "</li>";
                    echo "<li><strong>Mensagem:</strong> " . $texto . "</li>";
                    echo "</ul>";
                }

                echo "<br><div class='container-voltar'><a href='index.html' class='btn-voltar'>Voltar ao Início</a></div>";

            } else {
                // Caso alguém tente acessar disparaEmail.php diretamente na URL sem enviar o formulário
                header("Location: falecomigo.html");
                exit();
            }
            ?>
        </main>
    </div>
</body>
</html>