<?php
require_once '../../../../assets/conf/conf-dbcon.php';
session_start();
if (!isset($_SESSION['us_id'])) {
    header("Location: ../../login.html");
    exit();
}
$empresa_id = isset($_GET['cliente']) ? htmlspecialchars($_GET['cliente']) : null;

$get_empresa = $conn->prepare("SELECT * FROM empresas WHERE id_empresa = :empresa_id LIMIT 1");
$get_empresa->bindParam(':empresa_id', $empresa_id);
$get_empresa->execute();
$empresa = $get_empresa->fetch(PDO::FETCH_ASSOC);

$get_lancamentos = $conn->prepare("SELECT li.*, l.data_lancamento, cp.descricao AS conta_principal_descricao, l.lancamento, sc.codigo AS sub_conta_codigo, sc.descricao AS sub_conta_descricao
                                        FROM lancamento_itens li
                                        JOIN lancamentos l ON li.lancamento_id = l.lancamento_id
                                        JOIN sub_conta_2 sc ON li.sub_conta_id = sc.id
                                        JOIN conta_principal cp ON sc.conta_pai = cp.codigo
                                        JOIN empresas e ON l.empresa_id = e.id_empresa
                                        JOIN usuario u ON l.criador_usuario = u.id
                                        WHERE l.empresa_id = :empresa_id AND u.id = :us
");
$get_lancamentos->bindParam(':empresa_id', $empresa_id);
$get_lancamentos->bindParam(':us', $_SESSION['us_id']);
$get_lancamentos->execute();
$lancamentos = $get_lancamentos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" class="h-full antialiased bg-gray-50">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="color-scheme" content="light dark" />
  <title>Diário de consulta</title>

  <link rel="stylesheet" href="../../../../css/tailwind/output.css">
  <link rel="stylesheet" href="../../../../css/index.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>

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
          <a href="../index.html" class="sidebar-link">
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

          <a href="../../admin/index.html" class="sidebar-link">
            <i data-lucide="layout-dashboard" class="w-5 h-5 collapsibble-menu-icons"></i>
            Dashboard Admin
          </a>
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
        <div class=" page-content">
          <h1
            class="text-3xl font-semibold text-center text-transparent bg-gradient-to-r from-primary to-secondary bg-clip-text">
            Diário de consulta
          </h1>

          <!-- Search -->
          <div class="flex flex-wrap items-center justify-center gap-2 mt-2 mb-1">
            <!-- Search Input -->
            <div class="w-full max-w-sm ">
              <input type="text" placeholder="Pesquisar por NIF..."
                class="w-full px-3 py-2 text-sm transition-all duration-200 ease-in-out border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
                value="123456LA058" />
            </div>

            <!-- Botão Search -->
            <button
              class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary">
              <i data-lucide="search" class="w-4 h-4"></i>
              Pesquisar
            </button>
          </div>

          <h1 class="text-2xl font-semibold text-transparent bg-gradient-to-r from-primary to-secondary bg-clip-text">
            Informações do Cliente
          </h1>

          <div class="bg-white rounded-lg ">
            <div class="p-6 bg-white border rounded-lg">
              <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
                <div>
                  <p class="text-gray-500">NIF</p>
                  <p class="font-medium"><?= htmlspecialchars($empresa['nif']); ?></p>
                </div>

                <div>
                  <p class="text-gray-500">Nome da Empresa</p>
                  <p class="font-medium"><?= htmlspecialchars($empresa['nome']); ?></p>
                </div>

                <div>
                  <p class="text-gray-500">Ano de Análise</p>
                  <p class="font-medium"><?= htmlspecialchars($_GET['ano']); ?></p>
                </div>

                <div>
                  <p class="text-gray-500">Total de Débito</p>
                  <p class="font-medium">2400.00 KZ</p>
                </div>

                <div>
                  <p class="text-gray-500">Total de Crédito</p>
                  <p class="font-medium">2400.00 KZ</p>
                </div>
              </div>
            </div>
          </div>

          <h1 class="text-2xl font-semibold text-transparent bg-gradient-to-r from-primary to-secondary bg-clip-text">
            Movimentos Lançados
          </h1>

          <div class="overflow-hidden bg-white border rounded-lg shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-700 ">
                <thead class="">
                  <tr>
                    <th>Data do Movimento</th>
                    <th>Nº do Movimento</th>
                    <th>Conta Principal</th>
                    <th>Subconta</th>
                    <!-- <th>Conta Associada</th> -->
                    <th>Valor Débito</th>
                    <th>Valor Crédito</th>
                    <!-- <th class="text-right ">Ações</th> -->
                  </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                  <?php foreach ($lancamentos as $lancamento): ?>
                  <tr>
                    <td><?php echo date('d/m/Y', strtotime($lancamento['data_lancamento'])); ?></td>
                    <td><?php echo htmlspecialchars($lancamento['lancamento']); ?></td>
                    <td><?php echo htmlspecialchars(substr($lancamento['sub_conta_codigo'], 0, strpos($lancamento['sub_conta_codigo'], '.'))." - ".$lancamento['conta_principal_descricao']); ?></td>
                    <td><?php echo htmlspecialchars($lancamento['sub_conta_codigo']." - ".$lancamento['sub_conta_descricao']); ?></td>
                    <td><?php echo $lancamento['tipo'] === 'Debito' ? number_format($lancamento['valor'], 2, ',', '.') : '0,00'; ?></td>
                    <td><?php echo $lancamento['tipo'] === 'Credito' ? number_format($lancamento['valor'], 2, ',', '.') : '0,00'; ?></td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Overlay para mobile -->
  <div id="mobile-overlay" class="fixed inset-0 z-40 hidden transition-opacity duration-300 bg-black/50 md:hidden">
  </div>

  <script src="../../../../js/scripts.js"></script>
</body>

</html>
