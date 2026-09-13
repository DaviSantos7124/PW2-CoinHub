<?php

session_start();

require_once "includes/conexao.php";

if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST["usuario"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($usuario === "" || $senha === "") {

        $erro = "Preencha o usuário e a senha.";

    } else {

        $sql = "SELECT
                    cd_usuario,
                    nm_usuario,
                    ds_login,
                    ds_senha
                FROM tb_usuarios
                WHERE ds_login = ?
                LIMIT 1";

        $stmt = $conexao->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("s", $usuario);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {

                $dados = $resultado->fetch_assoc();

                if ($senha === $dados["ds_senha"]) {

                    $_SESSION["usuario"] = $dados["ds_login"];
                    $_SESSION["nome_usuario"] = $dados["nm_usuario"];
                    $_SESSION["cd_usuario"] = $dados["cd_usuario"];

                    header("Location: index.php");
                    exit;

                } else {

                    $erro = "Usuário ou senha incorretos.";
                }

            } else {

                $erro = "Usuário ou senha incorretos.";
            }

            $stmt->close();

        } else {

            $erro = "Erro ao consultar o banco de dados.";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Login - CoinHub</title>

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<link
    rel="stylesheet"
    href="css/style.css"
>
```

</head>

<body class="login-page">

```
<div class="login-container">

    <div class="login-logo">

        <i class="bi bi-coin"></i>

        <h1>
            Coin<span>Hub</span>
        </h1>

        <p>
            Controle de Estoque de Moedas Virtuais
        </p>

    </div>


    <div class="login-box">

        <h2>
            Acesse sua conta
        </h2>


        <?php if ($erro): ?>

            <div class="mensagem erro">

                <i class="bi bi-x-circle-fill"></i>

                <?= htmlspecialchars($erro) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="campo">

                <label for="usuario">
                    Usuário
                </label>

                <div class="input-icon">

                    <i class="bi bi-person-fill"></i>

                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        placeholder="Usuário"
                        value="<?= htmlspecialchars($_POST["usuario"] ?? "") ?>"
                        required
                    >

                </div>

            </div>


            <div class="campo">

                <label for="senha">
                    Senha
                </label>

                <div class="input-icon">

                    <i class="bi bi-lock-fill"></i>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Senha"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                class="btn-login"
            >
                Entrar
            </button>

        </form>


        <a
            href="#"
            class="esqueci-senha"
        >
            Esqueceu sua senha?
        </a>

    </div>

</div>

</body>

</html>
