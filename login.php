<?php
include 'db/conexao.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        echo "Por favor, preencha todos os campos.";
    } else {
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $nome, $hash);

        if ($stmt->fetch() && password_verify($senha, $hash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            header("Location: index.php");
            exit();
        } else {
            echo "E-mail ou senha inválidos!";
        }

        $stmt->close();
    }
}
?>

<form method="POST">
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>
<a href="register.php">Criar conta</a>
