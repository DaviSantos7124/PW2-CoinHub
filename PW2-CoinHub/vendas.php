<?php

$vendas = [
    [
        "id" => 1,
        "produto" => "Roblox - R$ 100",
        "cliente" => "João",
        "quantidade" => 2,
        "valor" => 200.00,
        "data" => "05/09/2026"
    ],
    [
        "id" => 2,
        "produto" => "800 V-Bucks",
        "cliente" => "Carlos",
        "quantidade" => 1,
        "valor" => 39.90,
        "data" => "06/09/2026"
    ],
    [
        "id" => 3,
        "produto" => "1.166 Diamantes",
        "cliente" => "Marcos",
        "quantidade" => 3,
        "valor" => 149.70,
        "data" => "06/09/2026"
    ],
    [
        "id" => 4,
        "produto" => "1.000 VP",
        "cliente" => "Pedro",
        "quantidade" => 1,
        "valor" => 34.90,
        "data" => "07/09/2026"
    ],
    [
        "id" => 5,
        "produto" => "1.050 FC Points",
        "cliente" => "Lucas",
        "quantidade" => 2,
        "valor" => 79.80,
        "data" => "08/09/2026"
    ]
];

$mensagem = "";
$erro = "";

$acao = $_GET["acao"] ?? "";
$id = $_GET["id"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acao = $_POST["acao"] ?? "";

    if ($acao === "adicionar") {

        $produto = $_POST["produto"] ?? "";
        $cliente = $_POST["cliente"] ?? "";
        $quantidade = $_POST["quantidade"] ?? "";
        $valor = $_POST["valor"] ?? "";
        $data = $_POST["data"] ?? "";


        if ($produto === "" || $cliente === "" || $quantidade === "" || $valor === "" || $data === "") {

            $erro = "Preencha todos os campos.";
        } elseif (!is_numeric($quantidade) || $quantidade <= 0) {

            $erro = "Digite uma quantidade válida.";
        } elseif (!is_numeric($valor) || $valor < 0) {

            $erro = "Digite um valor válido.";
        } else {

            /*
            FUTURO BANCO DE DADOS

            Aqui será colocado o INSERT no MySQL.
            */

            $mensagem = "Venda registrada com sucesso.";
        }
    }


    if ($acao === "editar") {

        $id = $_POST["id"] ?? "";
        $produto = $_POST["produto"] ?? "";
        $cliente = $_POST["cliente"] ?? "";
        $quantidade = $_POST["quantidade"] ?? "";
        $valor = $_POST["valor"] ?? "";
        $data = $_POST["data"] ?? "";


        if ($produto === "" || $cliente === "" || $quantidade === "" || $valor === "" || $data === "") {

            $erro = "Preencha todos os campos.";
        } elseif (!is_numeric($quantidade) || $quantidade <= 0) {

            $erro = "Digite uma quantidade válida.";
        } elseif (!is_numeric($valor) || $valor < 0) {

            $erro = "Digite um valor válido.";
        } else {

            /*
            FUTURO BANCO DE DADOS

            Aqui será colocado o UPDATE no MySQL.
            */

            $mensagem = "Venda atualizada com sucesso.";
        }
    }


    if ($acao === "excluir") {

        $id = $_POST["id"] ?? "";

        /*
        FUTURO BANCO DE DADOS

        Aqui será colocado o DELETE no MySQL.
        */

        $mensagem = "Venda excluída com sucesso.";
    }
}

include "includes/header.php"; ?>

<main class="container">

    <?php if ($acao === "adicionar"): ?>

        <div class="pagina-topo">
            <div>
                <span class="tag"> Vendas </span>
                <h1>Registrar venda</h1>
                <p> Registre uma nova venda de moeda virtual. </p>
            </div>
        </div>


        <?php if ($mensagem): ?>

            <div class="mensagem sucesso">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <?php if ($erro): ?>

            <div class="mensagem erro">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>


        <section class="form-container">
            <form method="POST">
                <input type="hidden" name="acao" value="adicionar">

                <div class="campo">
                    <label for="produto"> Produto </label>

                    <select name="produto" id="produto" required>
                        <option value=""> Selecione o produto </option>

                        <option value="Roblox - R$ 100"> Roblox - R$ 100 </option>

                        <option value="800 V-Bucks"> 800 V-Bucks </option>

                        <option value="1.166 Diamantes"> 1.166 Diamantes </option>

                        <option value="1.000 VP"> 1.000 VP </option>

                        <option value="1.050 FC Points"> 1.050 FC Points </option>
                    </select>
                </div>


                <div class="campo">
                    <label for="cliente"> Cliente </label>
                    <input type="text" id="cliente" name="cliente" placeholder="Nome do cliente" required>
                </div>


                <div class="form-linha">
                    <div class="campo">
                        <label for="quantidade"> Quantidade </label>
                        <input type="number" id="quantidade" name="quantidade" min="1" placeholder="1" required>
                    </div>


                    <div class="campo">
                        <label for="valor"> Valor </label>
                        <input type="number" id="valor" name="valor" step="0.01" min="0" placeholder="39.90" required>
                    </div>
                </div>


                <div class="campo">
                    <label for="data"> Data da venda </label>
                    <input type="date" id="data" name="data" required>
                </div>


                <div class="form-acoes">
                    <a href="vendas.php" class="btn btn-cancelar"> Cancelar </a>
                    <button type="submit" class="btn btn-amarelo"> <i class="bi bi-check-lg"></i> Registrar venda </button>
                </div>
            </form>
        </section>


    <?php elseif ($acao === "editar"): ?>

        <?php

        $venda = [
            "id" => $id,
            "produto" => "Roblox - R$ 100",
            "cliente" => "João",
            "quantidade" => 2,
            "valor" => 200.00,
            "data" => "2026-09-05"
        ];

        ?>


        <div class="pagina-topo">
            <div>
                <span class="tag"> Vendas </span>
                <h1>Editar venda</h1>
                <p> Altere as informações da venda selecionada. </p>
            </div>
        </div>


        <?php if ($mensagem): ?>

            <div class="mensagem sucesso">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <?php if ($erro): ?>

            <div class="mensagem erro">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>


        <section class="form-container">
            <form method="POST">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= htmlspecialchars($venda["id"]) ?>">

                <div class="campo">
                    <label for="produto"> Produto </label>

                    <select name="produto" id="produto" required>
                        <option value="Roblox - R$ 100" <?= $venda["produto"] === "Roblox - R$ 100" ? "selected" : "" ?>> Roblox - R$ 100 </option>

                        <option value="800 V-Bucks" <?= $venda["produto"] === "800 V-Bucks" ? "selected" : "" ?>> 800 V-Bucks </option>

                        <option value="1.166 Diamantes" <?= $venda["produto"] === "1.166 Diamantes" ? "selected" : "" ?>> 1.166 Diamantes </option>

                        <option value="1.000 VP" <?= $venda["produto"] === "1.000 VP" ? "selected" : "" ?>> 1.000 VP </option>

                        <option value="1.050 FC Points" <?= $venda["produto"] === "1.050 FC Points" ? "selected" : "" ?>> 1.050 FC Points </option>
                    </select>
                </div>


                <div class="campo">
                    <label for="cliente"> Cliente </label>
                    <input type="text" id="cliente" name="cliente" value="<?= htmlspecialchars($venda["cliente"]) ?>" required>
                </div>


                <div class="form-linha">
                    <div class="campo">
                        <label for="quantidade"> Quantidade </label>
                        <input type="number" id="quantidade" name="quantidade" min="1" value="<?= htmlspecialchars($venda["quantidade"]) ?>" required>
                    </div>


                    <div class="campo">
                        <label for="valor"> Valor </label>
                        <input type="number" id="valor" name="valor" step="0.01" min="0" value="<?= htmlspecialchars($venda["valor"]) ?>" required>
                    </div>
                </div>


                <div class="campo">
                    <label for="data"> Data da venda </label>
                    <input type="date" id="data" name="data" value="<?= htmlspecialchars($venda["data"]) ?>" required>
                </div>


                <div class="form-acoes">
                    <a href="vendas.php" class="btn btn-cancelar"> Cancelar </a>
                    <button type="submit" class="btn btn-amarelo"> <i class="bi bi-check-lg"></i> Salvar alterações </button>
                </div>
            </form>
        </section>


    <?php else: ?>

        <div class="pagina-topo">
            <div>
                <span class="tag"> Vendas </span>
                <h1>Vendas</h1>
                <p> Gerencie as vendas realizadas na loja. </p>
            </div>

            <a href="vendas.php?acao=adicionar" class="btn btn-amarelo">
                <i class="bi bi-plus-lg"></i>
                Registrar venda
            </a>
        </div>


        <?php if ($mensagem): ?>

            <div class="mensagem sucesso">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <?php if ($erro): ?>

            <div class="mensagem erro">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>


        <section class="tabela-container">
            <table class="tabela">
                <thead>
                    <tr>
                        <th> Produto </th>
                        <th> Cliente </th>
                        <th> Quantidade </th>
                        <th> Valor </th>
                        <th> Data </th>
                        <th> Ações </th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($vendas as $venda): ?>

                        <tr>
                            <td> <?= htmlspecialchars($venda["produto"]) ?> </td>

                            <td> <?= htmlspecialchars($venda["cliente"]) ?> </td>

                            <td> <?= $venda["quantidade"] ?> </td>

                            <td> R$ <?= number_format($venda["valor"], 2, ",", ".") ?> </td>

                            <td> <?= htmlspecialchars($venda["data"]) ?> </td>

                            <td>
                                <div class="acoes">

                                    <a href="vendas.php?acao=editar&id=<?= $venda["id"] ?>" class="btn btn-editar">
                                        <i class="bi bi-pencil"></i>
                                        Editar
                                    </a>

                                    <form action="vendas.php" method="POST" onsubmit="return confirm('Deseja realmente excluir esta venda?');">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?= $venda["id"] ?>">

                                        <button type="submit" class="btn btn-excluir">
                                            <i class="bi bi-trash"></i>
                                            Excluir
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </section>

    <?php endif; ?>

</main>

<?php include "includes/footer.php"; ?>