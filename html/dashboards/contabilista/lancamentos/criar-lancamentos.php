<?php
session_start();
if (!isset($_SESSION['us_id'])) {
  header('Location: ../../login.html');
  exit();
}
require_once '../../../../assets/conf/conf-dbcon.php';

$get_nome = $conn->prepare("SELECT primeiro_nome, ultimo_nome FROM pessoa INNER JOIN usuario ON pessoa.id = usuario.pessoa_id WHERE usuario.id = :user_id LIMIT 1");
$get_nome->bindParam(':user_id', $_SESSION['us_id']);
$get_nome->execute();
$nome_result = $get_nome->fetch(PDO::FETCH_ASSOC);
$_SESSION['nome'] = $nome_result['primeiro_nome'] . ' ' . $nome_result['ultimo_nome'];

$empresa_id = isset($_GET['cliente']) ? htmlspecialchars($_GET['cliente']) : null;

$get = $conn->prepare("SELECT empresas.*, enderecos.*, contactos.* FROM empresas INNER JOIN enderecos ON empresas.endereco = enderecos.id INNER JOIN contactos ON empresas.contacto_id = contactos.id WHERE empresas.id_empresa = :empresa_id LIMIT 1");
$get->bindParam(':empresa_id', $empresa_id);
$get->execute();
$empresa = $get->fetch(PDO::FETCH_ASSOC);

$get_lancamento_numero = $conn->prepare("SELECT MAX(lancamento_id) AS max_lancamento FROM lancamentos");
$get_lancamento_numero->execute();
$lancamento_result = $get_lancamento_numero->fetch(PDO::FETCH_ASSOC);
$next_lancamento_numero = $lancamento_result ? $lancamento_result['max_lancamento'] + 1 : 1;
?>

<!DOCTYPE html>
<html lang="en" class="h-full antialiased bg-gray-50">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="color-scheme" content="light dark" />
  <title>Adicionar Lançamentos</title>

  <link rel="stylesheet" href="../../../../css/tailwind/output.css">
  <link rel="stylesheet" href="../../../../css/index.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- PRELOAD: conexão antecipada com o servidor das fontes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- SWAP: estratégia de renderização da fonte -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="h-full">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar"
      class="fixed z-50 w-64 h-full border-r shadow-lg bg-gradient-brand sidebar-transition md:relative md:shadow-none sidebar-collapsed">

      <!-- Cabeçalho da sidebar -->
      <div class="sidebar-header">
        <div class="flex-shrink-0">
          <img src="../../../../assets/logos/IMG-20250702-WA0005-removebg-preview.png" alt="Logo GIAME"
            class="sidebar-logo" />
        </div>
        <span class="sidebar-title">GIAME</span>
      </div>

      <!-- Conteúdo da sidebar -->
      <div class="flex-1 overflow-y-auto">
        <nav class="p-4">
          <!-- Item direto: Dashboard -->
          <a href="../index" class="sidebar-link">
            <i data-lucide="layout-dashboard" class="w-5 h-5 collapsibble-menu-icons"></i>
            Dashboard
          </a>

          <!-- Seção: Clientes -->
          <div class="collapsible-section">
            <button
              class="collapsible-trigger sidebar-link w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group">
              <div class="flex items-center gap-2">
                <i data-lucide="user-cog" class="w-4 h-4 collapsibble-menu-icons"></i>
                <span class="truncate">Gerir Clientes</span>
              </div>
              <i data-lucide="chevron-down" class="w-4 h-4 collapsibble-menu-icons dropdown-transition"></i>
            </button>

            <div class="mt-1 collapsible-content">
              <div class="pl-4 space-y-1 border-l-2">
                <a href="../clientes/ver-clientes.html" class="sidebar-subitem">
                  <i data-lucide="users" class="w-4 h-4"></i> Ver clientes
                </a>
                <a href="../clientes/adicionar-cliente.html" class="sidebar-subitem">
                  <i data-lucide="plus" class="w-4 h-4"></i> Adicionar Cliente
                </a>
              </div>
            </div>
          </div>

          <!-- Seção: Plano Geral Contas ->
          <div class="collapsible-section">
            <button
              class="collapsible-trigger sidebar-link w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group">
              <div class="flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 collapsibble-menu-icons"></i>
                <span class="truncate">Plano Geral Contas</span>
              </div>
              <i data-lucide="chevron-down" class="w-4 h-4 collapsibble-menu-icons dropdown-transition"></i>
            </button>

            <div class="mt-1 collapsible-content">
              <div class="pl-4 space-y-1 border-l-2">
                <a href="../PGC/ver-contas.html" class="sidebar-subitem">
                  <i data-lucide="list" class="w-4 h-4"></i> Ver Contas
                </a>
                <a href="../PGC/criar-conta.html" class="sidebar-subitem">
                  <i data-lucide="plus" class="w-4 h-4"></i> Criar Conta
                </a>
              </div>
            </div>
          </div>

          <a href="../lancamentos/diario-consulta.html" class="sidebar-subitem">
            <i data-lucide="calendar-search" class="w-4 h-4"></i> Diário de consulta
          </a -->

          <!-- <a href="../../admin/index.html" class="sidebar-link">
            <i data-lucide="layout-dashboard" class="w-5 h-5 collapsibble-menu-icons"></i>
            Dashboard Admin
          </a> -->
        </nav>
      </div>
    </aside>

    <!-- Conteúdo principal -->
    <div class="flex flex-col flex-1 main-content">
      <!-- Header -->
      <header class="header-container">
        <button id="sidebar-toggle"
          class="flex items-center justify-center flex-shrink-0 transition-colors duration-200 rounded-lg w-9 h-9">
          <i data-lucide="panel-left" class="w-5 h-5"></i>
        </button>
      </header>

      <!-- Conteúdo -->
      <main class="flex-1 overflow-auto">
        <form class="page-content form-grid form-col-3" id="form-submit" data-title="Lançamento Criado!" data-id="lancamento"
          data-msg="O lançamento foi Criado com sucesso." data-redirect="ver-lancamentos">
          <h1
            class="text-2xl font-semibold text-center text-transparent md:text-start md:text-3xl bg-gradient-to-r from-primary to-secondary bg-clip-text">
            Adicionar Lançamentos
          </h1>

          <!-- Cabeçalho do Lançamento -->
          <div class="p-6 space-y-4 bg-white border rounded-lg shadow-sm">
            <h2
              class="text-xl font-semibold text-center text-transparent md:text-start bg-gradient-to-r from-primary to-secondary bg-clip-text">
              Cabeçalho de Lançamentos
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
              <div>
                <label for="numero_lancamento" class="label">Nº Lançamento</label>
                <input type="text" name="numero_lancamento" id="numero_lancamento" class="input" value="<?= date("Ym").$next_lancamento_numero ?>"
                  placeholder="Ex: 000123" readonly />
              </div>

              <div>
                <label for="nif_empresa" class="label">NIF da Empresa</label>
                <input type="text" name="nif_empresa" id="nif_empresa" class="input" placeholder="Ex: 500123456"
                  value="<?= htmlspecialchars($empresa['nif']); ?>" readonly required />
              </div>

              <div>
                <label for="criado_por" class="label">Criado por</label>
                <input type="text" name="criado_por" id="criado_por" class="input"
                value="<?= $_SESSION['nome'] ?>" placeholder="Seu Nome" readonly
                  required />
              </div>

              <div>
                <label for="total_debito" class="label">Total Débito</label>
                <input type="text" name="total_debito" id="total_debito" class="bg-gray-100 cursor-not-allowed input"
                  readonly value="0.00" required />
              </div>

              <div>
                <label for="total_credito" class="label">Total Crédito</label>
                <input type="text" name="total_credito" id="total_credito" class="bg-gray-100 cursor-not-allowed input"
                  readonly value="0.00" required />
              </div>

              <div>
                <label for="diferenca" class="label">Diferença</label>
                <input type="text" id="diferenca" class="bg-gray-100 cursor-not-allowed input" readonly disabled
                  value="0.00" />
              </div>

              <div>
                <label for="ano_analise" class="label">Ano de Análise</label>
                <input type="text" name="ano_analise" id="ano_analise" class="cursor-not-allowed input"
                  placeholder="2025" required readonly value="<?= htmlspecialchars($_GET['ano'] ?? '2025'); ?>" />
              </div>

              <div>
                <label for="data_movimento" class="label">Data do Movimento</label>
                <input type="date" name="data_movimento" id="data_movimento" class="font-normal input" required>
              </div>

              <div>
                <label for="descricao_movimento" class="label">Descrição do Movimento</label>
                <input type="text" name="descricao_movimento" id="descricao_movimento" class="font-normal input"
                  required>
              </div>
            </div>
          </div>

          <div class="flex flex-col gap-2 mt-2 mb-1 md:flex-row md:justify-between md:items-center">
            <h2
              class="text-xl font-semibold text-center text-transparent md:text-start md:text-2xl bg-gradient-to-r from-primary to-secondary bg-clip-text">
              Linhas do Lançamento
            </h2>

            <!-- Botão Adicionar -->
            <button type="button"
              class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary"
              onclick="AdicionarLinha()">
              <i data-lucide="plus" class="w-4 h-4"></i>
              Adicionar Linha
            </button>
          </div>

          <div class="overflow-hidden bg-white border rounded-lg shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-700 ">
                <thead class="">
                  <tr>
                    <!-- th>Nº do Movimento</th -->
                    <!-- th>Data do Movimento</th -->
                    <th>Conta principal</th>
                    <th>Conta Lançamento</th>
                    <th>Valor Débito</th>
                    <th>Valor Crédito</th>
                    <th class="text-right ">Ações</th>
                  </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                  <tr>
                    <!--td>
                      <input type="number" name="numero_movimento" id="numero_movimento"
                        class="font-normal input placeholder:font-normal" placeholder="Nº do movimento" required>
                    </td-->

                    <td>
                      <select name="conta_debito" id="conta_debito" class="font-normal input"
                        required>
                        <option disabled selected>Selcione a conta</option>
                        <?php
                        $get_contas = $conn->prepare("SELECT * FROM conta_principal ORDER BY codigo ASC");
                        $get_contas->execute();
                        $contas = $get_contas->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($contas as $conta): ?>
                          <option value="<?= $conta['codigo']; ?>"><?= $conta['codigo'] . " - " . $conta['descricao']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </td>

                    <td>
                      <select name="subconta_lancamento_deb" id="subconta_lancamento" class="font-normal input"
                        required>
                        <option disabled selected>Selcione a subconta</option>
                        <!-- <option value="11">11.1 - Subconta A</option>
                        <option value="32">11.2 - Subconta B</option>
                        <option value="201">11.3 - Subconta C</option>
                        <option value="301">11.4 - Subconta D</option>
                        <option value="401">11.5 - Subconta E</option> -->
                      </select>
                    </td>

                    <td>
                      <input type="number" name="valor_debito" class="font-normal input placeholder:font-normal"
                        placeholder="Valor débito" required>
                    </td>

                    <td>
                      <input type="number" name="valor_credito" class="font-normal input placeholder:font-normal"
                        placeholder="Valor crédito" required>
                    </td>

                    <td class="pr-4 text-right">
                      <button type="button" id="btn-remover-linha"
                        class="p-1 text-gray-500 transition-colors duration-200 rounded-full hover:text-gray-700 hover:bg-gray-100">
                        <i data-lucide="trash2" class="w-4 h-4"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Botões -->
          <div class="flex flex-col gap-3 md:flex-row ">
            <a href="ver-lancamentos.html"
              class="order-2 w-full px-4 py-2 text-sm font-medium text-center text-gray-700 transition-all duration-200 border border-gray-200 rounded-lg md:w-auto tab-button hover:bg-gray-100 md:order-1">
              Voltar
            </a>

            <button type="submit" id="btn-registrar"
              class="order-1 w-full px-4 py-2 text-sm text-white rounded-lg md:w-auto bg-primary hover:bg-opacity-90 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary">
              Registar
            </button>
          </div>

        </form>

      </main>
    </div>

  </div>

  <!-- Overlay para mobile -->
  <div id="mobile-overlay" class="fixed inset-0 z-40 hidden transition-opacity duration-300 bg-black/50 md:hidden">
  </div>

  <script src="../../../../js/scripts.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      fetch('../../../../assets/conf/conf-get-subconta.php')
        .then(response => response.text())
        .then(data => {
          document.getElementById('subconta_lancamento').innerHTML += data;
          // document.getElementsByTagName('script')[4].remove();
          // var s = document.createElement("script");
          // s.src = "../../../../js/scripts.js";
          // document.body.appendChild(s);
          // if (window.lucide) {
          //   window.lucide.createIcons();
          // }
        })
        .catch(error => {
          console.error('Erro ao carregar os dados:', error);
        });
    });
  </script>

</body>

</html>