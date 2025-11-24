<?php
session_start();
if (!isset($_SESSION['us_id'])) {
  header('Location: ../../../login.html');
  session_abort();
  session_unset();
  session_destroy();
  exit();
}
$empresa_id = isset($_GET['empresa']) ? htmlspecialchars($_GET['empresa']) : null;

require_once '../../../../assets/conf/conf-dbcon.php';
require_once '../../../../assets/models/mdl-empresa.php';
require_once '../../../../assets/models/mdl-endereco.php';
require_once '../../../../assets/models/mdl-contacto.php';

$get = $conn->prepare("SELECT empresas.*, enderecos.*, contactos.* FROM empresas INNER JOIN enderecos ON empresas.endereco = enderecos.id INNER JOIN contactos ON empresas.contacto_id = contactos.id WHERE empresas.id_empresa = :empresa_id LIMIT 1");
$get->bindParam(':empresa_id', $empresa_id);
$get->execute();
$empresa = $get->fetch(PDO::FETCH_ASSOC);

$get_address = $conn->prepare("SELECT * FROM enderecos INNER JOIN empresas ON enderecos.id = empresas.endereco WHERE empresas.id_empresa = :empresa");
$get_address->bindParam(':empresa', $empresa['id_empresa']);
$get_address->execute();
$address = $get_address->fetch(PDO::FETCH_ASSOC);

$get_contact = $conn->prepare("SELECT * FROM contactos INNER JOIN empresas ON contactos.id = empresas.contacto_id WHERE empresas.id_empresa = :empresa");
$get_contact->bindParam(':empresa', $empresa['id_empresa']);
$get_contact->execute();
$contact = $get_contact->fetch(PDO::FETCH_ASSOC);

if (!$empresa) {
  header('Location: ver-clientes.html');
  exit();
}

$get_socios = $conn->prepare("SELECT socios.* FROM socios WHERE empresa_id = :empresa_id");
$get_socios->bindParam(':empresa_id', $empresa_id);
$get_socios->execute();
$socios = $get_socios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" class="h-full antialiased bg-gray-50">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="color-scheme" content="light dark" />
  <title>Cliente - <?= htmlspecialchars($empresa['nome']); ?></title>

  <link rel="stylesheet" href="../../../../css/tailwind/output.css">
  <link rel="stylesheet" href="../../../../css/index.css">
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

      <main class="flex-1 p-6 overflow-y-auto">
        <div class="page-content">
          <!-- Capa -->
          <section class="flex items-center gap-6 p-6 bg-white border rounded-lg">
            <div class="flex items-center justify-center text-2xl rounded-full text-primary">
              <i data-lucide="building2" class="w-12 h-12"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold"><?= htmlspecialchars($empresa['nome']); ?></h2>
                <span class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Ativa</span>
              </div>
              <div class="mt-1 text-sm text-gray-600">
                <p>NIF: <span class="font-medium text-gray-800"><?= htmlspecialchars($empresa['nif']); ?></span></p>
                <p>Representante: <span
                    class="font-medium text-gray-800"><?= htmlspecialchars($empresa['representante_legal']); ?></span>
                </p>
              </div>
            </div>
          </section>

          <!-- Detalhes -->
          <section class="p-6 bg-white border rounded-lg">
            <h3 class="mb-4 text-lg font-semibold">Detalhes do Cliente</h3>

            <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-3">
              <div>
                <p class="text-gray-500">Capital Social</p>
                <p class="font-medium"><?= htmlspecialchars($empresa['capital_social']); ?></p>
              </div>

              <div>
                <p class="text-gray-500">Endereço</p>
                <p class="font-medium"><?= htmlspecialchars($address['morada'] . ", " . $address['bairro'] . "; " . $address['provincia']); ?></p>
              </div>

              <div>
                <p class="text-gray-500">Contacto</p>
                <p class="font-medium"><?= htmlspecialchars($contact['telefone']); ?></p>
              </div>

              <div>
                <p class="text-gray-500">Email</p>
                <p class="font-medium"><?= htmlspecialchars($contact['email']); ?></p>
              </div>

              <div>
                <p class="text-gray-500">Data de Constituição</p>
                <p class="font-medium"><?= htmlspecialchars($empresa['data_fundacao']); ?></p>
              </div>

              <div>
                <p class="text-gray-500">Objecto Social</p>
                <p class="font-medium"><?= htmlspecialchars($empresa['objecto_social']); ?></p>
              </div>

              <div>
                <p class="text-gray-500">Regime</p>
                <p class="font-medium">Simplificado</p>
              </div>

              <div class="md:col-span-2">
                <p class="mb-1 text-gray-500">Ano de Análise</p>
                <select name="ano_analise" id="anoAnalise" class="input md:!w-auto w-full">
                  <option value="" disabled selected>Selecione o ano de análise</option>
                  <?php $currentYear = date("Y");
                  for ($year = $currentYear; $year >= $currentYear - 5; $year--): ?>
                    <option value="<?= $year; ?>"><?= $year; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
          </section>

          <!-- Sócios: Cards -->
          <section class="space-y-4 ">
            <h3 class="text-lg font-semibold text-gray-800">Sócios</h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
              <!-- Card 1 -->
              <?php foreach ($socios as $socio): ?>
                <div class="p-4 transition bg-white border rounded-lg hover:shadow-sm">
                  <div class="flex items-center gap-3">
                    <div class="p-2 rounded-md bg-gray-50">
                      <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($socio['nome_socio']); ?></p>
                      <p class="text-xs text-gray-500"><?= htmlspecialchars($socio['contacto']); ?></p>
                    </div>
                  </div>
                  <div class="mt-2 text-xs text-gray-500">
                    Participação: <span class="font-medium text-gray-700"><?= htmlspecialchars($socio['participacao']); ?>
                      KZ</span>
                  </div>
                </div>
              <?php endforeach; ?>

              <!-- Card 2 -->


              <!-- Card 2 -->

            </div>
          </section>

          <!-- Ações -->
          <section id="accoes" class="hidden">
            <h3 class="mb-4 text-lg font-semibold text-gray-700">Ações</h3>

            <div class="grid grid-cols-4 gap-4 md:grid-cols-4">
              <!-- Ver Lançamentos -->
              <a href="../lancamentos/ver-lancamentos?cliente=<?= $empresa_id; ?>"
                class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="list" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">Ver Lançamentos</p>
              </a>

              <!-- Novo Lançamento -->
              <a href="../lancamentos/criar-lancamentos?cliente=<?= $empresa_id; ?>"
                id="link-lance" class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">Fazer Lançamentos</p>
              </a>

              <!-- Diário -->
              <a href="../lancamentos/diario-consulta?cliente=<?= $empresa_id; ?>"
                id="link-diario" class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">Diário</p>
              </a>

              <!-- balancete -->
              <a href="../lancamentos/balancete.html"
                class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">Balancete</p>
              </a>

              <!-- balancete resumo-->
              <a href="../lancamentos/balanco.html"
                class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">Balanço</p>
              </a>

              <!-- DR & Balanço -->
              <a href="../lancamentos/dr.html"
                class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">DR</p>
              </a>

              <!-- PGC -->
              <a href="../lancamentos/pgc.html"
                class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">PGC</p>
              </a>

              <!-- balancete -->
              <a href="../PGC/criar-conta.html"
                class="flex flex-col items-center justify-center gap-2 p-4 transition bg-white border border-gray-200 shadow-sm rounded-xl hover:bg-gray-50 group">
                <div class="p-2 transition-all rounded-lg bg-primary/10 text-primary group-hover:text-highlight">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <p class="text-sm text-gray-700">Inserir Conta</p>
              </a>


            </div>
          </section>

          <!-- Botões -->
          <section class="flex justify-end gap-3">
            <a href="ver-clientes.html"
              class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 border border-gray-200 rounded-lg tab-button hover:bg-gray-100">
              Voltar
            </a>

            <button onclick="window.location.href = 'editar-cliente.html'"
              class="px-4 py-2 text-sm text-white rounded-lg bg-primary hover:bg-opacity-90">
              Editar Cliente
            </button>
          </section>
        </div>
      </main>
    </div>
  </div>

  <!-- Overlay para mobile -->
  <div id="mobile-overlay" class="fixed inset-0 z-40 hidden transition-opacity duration-300 bg-black/50 md:hidden">
  </div>

  <script>
    const empresa_id = `<?= $empresa_id; ?>`;
  </script>
  <script src="../../../../js/scripts.js"></script>
</body>

</html>