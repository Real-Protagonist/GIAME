// ==========================
// INICIALIZAÇÕES GERAIS

// ==========================
lucide.createIcons();
gsap.registerPlugin(ScrollTrigger);

// ==========================
// MENU MOBILE (NAVBAR TOGGLE)
// ==========================
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuBtn && mobileMenu) {
  mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
}


// ==========================
// ANIMAÇÕES GSAP
// ==========================

// Hero section
gsap.from('.hero-gradient h1', {
  duration: 1,
  y: 50,
  opacity: 1,
  ease: 'power3.out'
});

gsap.from('.hero-gradient p', {
  duration: 1,
  y: 30,
  opacity: 1,
  delay: 0.3,
  ease: 'power3.out'
});

gsap.from('.hero-gradient .flex', {
  duration: 1,
  y: 30,
  opacity: 1,
  delay: 0.6,
  ease: 'power3.out'
});

// Features
gsap.from('.feature-card', {
  scrollTrigger: {
    trigger: '#features',
    start: 'top 80%'
  },
  duration: 0.8,
  y: 50,
  opacity: 1,
  stagger: 0.2,
  ease: 'power3.out'
});

// Testimonials
gsap.from('.testimonial-card', {
  scrollTrigger: {
    trigger: '#testimonials',
    start: 'top 80%'
  },
  duration: 0.8,
  y: 50,
  opacity: 1,
  stagger: 0.2,
  ease: 'power3.out'
});

// Pricing
gsap.from('.pricing-card', {
  scrollTrigger: {
    trigger: '#pricing',
    start: 'top 80%'
  },
  duration: 0.8,
  y: 50,
  opacity: 1,
  stagger: 0.2,
  ease: 'power3.out'
});


// ==========================
// FORMULÁRIO DE REGISTRO
// ==========================
const registerForm = document.getElementById('registerForm');

if (registerForm) {
  registerForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const name = document.getElementById('name')?.value;
    const email = document.getElementById('email')?.value;
    const company = document.getElementById('company')?.value;
    const password = document.getElementById('password')?.value;
    const confirmPassword = document.getElementById('confirmPassword')?.value;
    const terms = document.getElementById('terms')?.checked;

    if (!name || !email || !company || !password || !confirmPassword) {
      alert('Por favor, preencha todos os campos.');
      return;
    }

    if (password !== confirmPassword) {
      alert('As senhas não coincidem.');
      return;
    }

    if (password.length < 8) {
      alert('A senha deve ter pelo menos 8 caracteres.');
      return;
    }

    if (!terms) {
      alert('Você deve concordar com os termos de serviço.');
      return;
    }

    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    setTimeout(() => {
      alert('Conta criada com sucesso! Redirecionando para o Login...');
      window.location.href = 'login.html';
    }, 2000);
  });
}


// ==========================
// VALIDAÇÃO DE SENHA EM TEMPO REAL
// ==========================
const confirmPasswordInput = document.getElementById('confirmPassword');

if (confirmPasswordInput) {
  confirmPasswordInput.addEventListener('input', function () {
    const password = document.getElementById('password')?.value;
    const confirmPassword = this.value;

    if (confirmPassword && password !== confirmPassword) {
      this.classList.add('border-red-300');
      this.classList.remove('border-gray-300');
    } else {
      this.classList.remove('border-red-300');
      this.classList.add('border-gray-300');
    }
  });
}


// ==========================
// SIDEBAR RESPONSIVO
// ==========================
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebar-toggle');
const mobileOverlay = document.getElementById('mobile-overlay');

let sidebarOpen = window.innerWidth >= 768;

function updateSidebarState() {
  const isMobile = window.innerWidth < 768;

  if (isMobile) {
    if (sidebarOpen) {
      sidebar.classList.remove('sidebar-collapsed');
      sidebar.classList.add('sidebar-open');
      mobileOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    } else {
      sidebar.classList.remove('sidebar-open');
      sidebar.classList.add('sidebar-collapsed');
      mobileOverlay.classList.add('hidden');
      document.body.style.overflow = '';
    }
  } else {
    mobileOverlay.classList.add('hidden');
    document.body.style.overflow = '';

    if (sidebarOpen) {
      sidebar.classList.remove('sidebar-collapsed');
      sidebar.classList.add('sidebar-open');
    } else {
      sidebar.classList.remove('sidebar-open');
      sidebar.classList.add('sidebar-collapsed');
    }
  }
}

function toggleSidebar() {
  sidebarOpen = !sidebarOpen;
  updateSidebarState();
}

if (sidebarToggle && sidebar) {
  sidebarToggle.addEventListener('click', toggleSidebar);
}

if (mobileOverlay) {
  mobileOverlay.addEventListener('click', () => {
    if (sidebarOpen && window.innerWidth < 768) {
      toggleSidebar();
    }
  });
}

window.addEventListener('resize', () => {
  const wasOpen = sidebarOpen;
  sidebarOpen = window.innerWidth >= 768 ? wasOpen : document.body.style.overflow === 'hidden';
  updateSidebarState();
});

if (mobileOverlay) {
  updateSidebarState();
}




// ==========================
// ACORDEÃO DA SIDEBAR
// ==========================
document.querySelectorAll('.collapsible-trigger').forEach(trigger => {
  trigger.addEventListener('click', () => {
    const section = trigger.closest('.collapsible-section');
    const content = section.querySelector('.collapsible-content');
    const chevron = trigger.querySelector('[data-lucide="chevron-down"]');

    if (content.classList.contains('open')) {
      content.classList.remove('open');
      chevron.classList.remove('rotate-180');
    } else {
      document.querySelectorAll('.collapsible-content.open').forEach(openContent => {
        if (openContent !== content) {
          openContent.classList.remove('open');
          const otherChevron = openContent.closest('.collapsible-section')?.querySelector('[data-lucide="chevron-down"]');
          otherChevron?.classList.remove('rotate-180');
        }
      });

      content.classList.add('open');
      chevron.classList.add('rotate-180');
    }
  });
});


// ==========================
// SCROLL LOCK NO MOBILE
// ==========================
document.addEventListener('touchmove', function (e) {
  if (
    window.innerWidth < 768 &&
    sidebarOpen &&
    !sidebar.contains(e.target) &&
    !mobileOverlay.contains(e.target)
  ) {
    e.preventDefault();
  }
}, { passive: false });


// ==========================
// FECHAR SIDEBAR AO CLICAR EM LINK (MOBILE)
// ==========================
document.querySelectorAll('aside a').forEach(link => {
  link.addEventListener('click', () => {
    if (window.innerWidth < 768 && sidebarOpen) {
      toggleSidebar();
    }
  });
});


// ==========================
// MENU DROPDOWN FLUTUANTE (Tables)
// ==========================

// Função para controlar o dropdown
function toggleDropdown(button) {
  const allDropdowns = document.querySelectorAll('.dropdown-menu');

  // Fecha todos os outros dropdowns
  allDropdowns.forEach(menu => {
    menu.classList.add('hidden');
  });

  // Encontra o dropdown correspondente
  const dropdown = button.nextElementSibling;

  if (dropdown.classList.contains('hidden')) {
    // Calcula a posição do botão
    const buttonRect = button.getBoundingClientRect();

    // Posiciona o dropdown
    dropdown.style.position = 'fixed';
    dropdown.style.top = (buttonRect.bottom + 4) + 'px';
    dropdown.style.right = (window.innerWidth - buttonRect.right) + 'px';
    dropdown.style.zIndex = '9999';

    // Mostra o dropdown
    dropdown.classList.remove('hidden');
  } else {
    dropdown.classList.add('hidden');
  }
}

// Fecha dropdown ao clicar fora
document.addEventListener('click', function (event) {
  const dropdowns = document.querySelectorAll('.dropdown-menu');
  const buttons = document.querySelectorAll('button[onclick*="toggleDropdown"]');

  let clickedButton = false;
  buttons.forEach(button => {
    if (button.contains(event.target)) {
      clickedButton = true;
    }
  });

  if (!clickedButton) {
    dropdowns.forEach(dropdown => {
      dropdown.classList.add('hidden');
    });
  }
});

// Reposiciona dropdowns ao redimensionar a janela
window.addEventListener('resize', function () {
  const dropdowns = document.querySelectorAll('.dropdown-menu');
  dropdowns.forEach(dropdown => {
    dropdown.classList.add('hidden');
  });
});


// =======================================
// Número automático do Lançamento
// =======================================
document.addEventListener('DOMContentLoaded', function () {
  // Gerar número aleatório de 4 dígitos (entre 1000 e 9999)
  const numeroAleatorio = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;

  // Obter data atual
  const hoje = new Date();
  const dia = String(hoje.getDate()).padStart(2, '0');      // 01-31
  const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // 01-12 (getMonth() é zero-based)
  const ano = hoje.getFullYear();                           // 2025

  const dataAtual = `${dia}${mes}${ano}`; // Ex: 05042025

  // Combinar número aleatório + data
  const numeroFinal = `${numeroAleatorio}${dataAtual}`; // Ex: 732505042025

  // Exibir no input
  document.getElementById('numero_lancamento').value = numeroFinal;
});


// ==========================
// TOGGLE DE VISUALIZAÇÃO DE SENHA
// ==========================
function togglePassword() {
  const input = document.getElementById('password');
  const icon = document.getElementById('eyeIcon');

  if (input.type === 'password') {
    input.type = 'text';
    icon.setAttribute('data-lucide', 'eye');
  } else {
    input.type = 'password';
    icon.setAttribute('data-lucide', 'eye-off');
  }

  // Atualiza o ícone (re-renderiza)
  lucide.createIcons();
}

// ==========================
// TABS
// ==========================
function showTab(tab) {
  // Pega todos os elementos que começam com id="tab-"
  const tabButtons = document.querySelectorAll('[id^="tab-"]');
  const tabContents = document.querySelectorAll('[id^="content-"]');

  tabButtons.forEach(btn => {
    btn.classList.remove('bg-primary', 'text-white');
    btn.classList.add('text-gray-700', 'hover:bg-gray-100');
  });

  tabContents.forEach(content => {
    content.classList.add('hidden');
  });

  document.getElementById(`tab-${tab}`).classList.add('bg-primary', 'text-white');
  document.getElementById(`tab-${tab}`).classList.remove('text-gray-700', 'hover:bg-gray-100');
  document.getElementById(`content-${tab}`).classList.remove('hidden');
}


// ==========================
// MICROMODAL
// ==========================
document.addEventListener('DOMContentLoaded', () => {
  MicroModal.init();
});


// ==========================
// ADICIONAR SÓCIOS
// ==========================
const listaSocios = document.getElementById("lista-socios");
const btnAdicionarSocio = document.getElementById("btn-add-socio");

const inputNome = document.getElementById("socio-nome");
const inputContacto = document.getElementById("socio-contacto");
const inputParticipacao = document.getElementById("socio-participacao");
const campoHidden = document.getElementById("socios-json");

let socios = [];
let indiceEdicao = null;

// Atualiza o campo hidden com o array em JSON
function atualizarCampoHidden() {
  campoHidden.value = JSON.stringify(socios);
}

// Re-renderiza visualmente a lista de sócios
function renderizarLista() {
  listaSocios.innerHTML = "";

  socios.forEach((socio, index) => {
    const linha = document.createElement("div");
    linha.className = "flex items-center justify-between w-full p-4 text-sm text-gray-700 bg-white border rounded-md shadow-sm hover:bg-gray-50";

    linha.innerHTML = `
      <div class="flex-1 flex items-center gap-6">
        <span class="min-w-[150px] font-medium truncate">${socio.nome}</span>
        <span class="min-w-[140px] text-gray-500 truncate">${socio.contacto}</span>
        <span class="min-w-[150px] text-gray-500 truncate">Participação: ${Number(socio.participacao).toLocaleString()} KZ</span>
      </div>
      <div class="flex items-center gap-2 flex-shrink-0">
        <button title="Editar" class="text-gray-500 hover:text-highlight btn-editar-socio" data-index="${index}">
          <i data-lucide="edit3" class="w-4 h-4"></i>
        </button>
        <button title="Remover" class="text-gray-500 hover:text-red-600 btn-remover-socio" data-index="${index}">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
      </div>
    `;

    listaSocios.appendChild(linha);
  });

  lucide.createIcons();
  atualizarCampoHidden();
}

// Adicionar ou editar sócio
if (btnAdicionarSocio) {
  btnAdicionarSocio.addEventListener("click", () => {
    const nome = inputNome.value.trim();
    const contacto = inputContacto.value.trim();
    const participacao = inputParticipacao.value.trim();

    if (!nome || !contacto || !participacao) {
      alert("Preencha todos os campos do sócio!");
      return;
    }

    const novoSocio = { nome, contacto, participacao };

    if (indiceEdicao !== null) {
      socios[indiceEdicao] = novoSocio;
      indiceEdicao = null;
    } else {
      socios.push(novoSocio);
    }

    renderizarLista();

    // Reset e fecha modal
    inputNome.value = "";
    inputContacto.value = "";
    inputParticipacao.value = "";
    MicroModal.close("modal-socios");
  });
}

if (listaSocios) {
  // Remover sócio
  listaSocios.addEventListener("click", (e) => {
    const btn = e.target.closest(".btn-remover-socio");
    if (btn) {
      const index = parseInt(btn.dataset.index);
      socios.splice(index, 1);
      renderizarLista();
    }
  });

  // Editar sócio
  listaSocios.addEventListener("click", (e) => {
    const btn = e.target.closest(".btn-editar-socio");
    if (btn) {
      const index = parseInt(btn.dataset.index);
      const socio = socios[index];

      inputNome.value = socio.nome;
      inputContacto.value = socio.contacto;
      inputParticipacao.value = socio.participacao;

      indiceEdicao = index;
      MicroModal.show("modal-socios");
    }
  });
}


// ==========================
// ADICIONAR LINHAS
// ==========================
function AdicionarLinha() {
  const tbody = document.querySelector("tbody");
  const linhaOriginal = tbody.querySelector("tr");
  const novaLinha = linhaOriginal.cloneNode(true);

  // Limpa valores e habilita inputs
  novaLinha.querySelectorAll("input").forEach(input => {
    input.value = "";
    input.disabled = false; // reabilita campos para evitar herança de bloqueio
  });
  novaLinha.querySelectorAll("select").forEach(select => select.selectedIndex = 0);

  // Adiciona evento ao botão de remover na nova linha
  const btnRemover = novaLinha.querySelector("button");
  btnRemover.addEventListener("click", function () {
    if (tbody.children.length > 1) {
      novaLinha.remove();
      AtualizarTotais(); // atualiza ao remover
    } else {
      alert("Tem que deixar pelo menos uma linha!");
    }
  });

  AdicionarEventosDeInputNaLinha(novaLinha);

  tbody.appendChild(novaLinha);
}


// ==========================
// ADICIONAR EVENTOS DE INPUT NA LINHA
// ==========================
document.addEventListener("DOMContentLoaded", () => {
  const linhaOriginal = document.querySelector("tbody tr");
  AdicionarEventosDeInputNaLinha(linhaOriginal);

  const btnRemover = document.querySelector("#btn-remover-linha");
  if (btnRemover) {
    btnRemover.addEventListener("click", function () {
      const tbody = document.querySelector("tbody");
      if (tbody.children.length > 1) {
        this.closest("tr").remove();
        AtualizarTotais(); // não esquece disso aqui também no remover da linha inicial
      } else {
        alert("Tem que deixar pelo menos uma linha, cria!");
      }
    });
  }
});


// ==========================
// ATUALIZAR TOTAIS + DIFERENÇA
// ==========================
function AtualizarTotais() {
  let totalDebito = 0;
  let totalCredito = 0;

  const debitos = document.querySelectorAll('input[name="valor_debito"]');
  const creditos = document.querySelectorAll('input[name="valor_credito"]');

  debitos.forEach(input => totalDebito += parseFloat(input.value) || 0);
  creditos.forEach(input => totalCredito += parseFloat(input.value) || 0);

  const inputTotalDebito = document.querySelector('#total_debito');
  const inputTotalCredito = document.querySelector('#total_credito');
  const inputDiferenca = document.querySelector('#diferenca');
  const btnRegistrar = document.querySelector('#btn-registrar');

  if (inputTotalDebito && inputTotalCredito) {
    inputTotalDebito.value = totalDebito.toFixed(2);
    inputTotalCredito.value = totalCredito.toFixed(2);
  }

  // Calcular diferença
  const diferenca = totalDebito - totalCredito;
  if (inputDiferenca) {
    inputDiferenca.value = diferenca.toFixed(2);
  }

  // Habilitar/desabilitar botão
  if (btnRegistrar) {
    if (diferenca !== 0) {
      btnRegistrar.disabled = true;
      btnRegistrar.classList.add("opacity-50", "cursor-not-allowed");
      btnRegistrar.classList.remove("hover:bg-opacity-90");
    } else {
      btnRegistrar.disabled = false;
      btnRegistrar.classList.remove("opacity-50", "cursor-not-allowed");
      btnRegistrar.classList.add("hover:bg-opacity-90");
    }
  }
}


// ==========================
// ADICIONAR EVENTOS DE INPUT NA LINHA
// ==========================
function AdicionarEventosDeInputNaLinha(linha) {
  const debitoInput = linha.querySelector('input[name="valor_debito"]');
  const creditoInput = linha.querySelector('input[name="valor_credito"]');

  // Evento para débito
  debitoInput.addEventListener('input', () => {
    if (debitoInput.value) {
      creditoInput.value = "";
      creditoInput.disabled = true;
    } else {
      creditoInput.disabled = false;
    }
    AtualizarTotais();
  });

  // Evento para crédito
  creditoInput.addEventListener('input', () => {
    if (creditoInput.value) {
      debitoInput.value = "";
      debitoInput.disabled = true;
    } else {
      debitoInput.disabled = false;
    }
    AtualizarTotais();
  });
}



// ==========================
// CONFIRMAR AÇÃO
// ==========================
document.addEventListener("click", function (e) {
  const btn = e.target.closest(".btn-acao");
  if (!btn) return;

  const titulo = btn.dataset.title || "Tens certeza?";
  const mensagem = btn.dataset.msg || "Esta ação é irreversível.";
  const tipo = btn.dataset.tipo || "info";
  const acao = btn.dataset.action;

  Swal.fire({
    html: `
    <div class="flex flex-col items-center text-center">
      <i data-lucide="${tipo === 'apagar' ? 'alert-circle' : 'help-circle'}"
         class="w-14 h-14 mb-3"></i>
      <h2 class="text-xl font-semibold text-gray-900 mb-2">${titulo}</h2>
      <p class="text-gray-700 text-sm">${mensagem}</p>
    </div>
  `,
    showCancelButton: true,
    confirmButtonText: "Sim, continuar!",
    cancelButtonText: "Cancelar",

    // Estilização com Tailwind
    customClass: {
      popup: "rounded-xl p-6 shadow-xl bg-white",
      confirmButton: `${tipo === "apagar"
        ? "bg-red-600 hover:bg-red-700"
        : "bg-primary"
        } text-white px-4 py-2 mt-4 rounded-lg text-sm font-medium transition-colors`,
      cancelButton: "mt-4 ml-2 px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-200 border border-gray-200 rounded-lg tab-button hover:bg-gray-100",
    },

    buttonsStyling: false,

    didOpen: () => {
      lucide.createIcons();
    }
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        html: `
            <div class="flex flex-col items-center text-center">
              <i data-lucide="circle-check"
                class="w-14 h-14 mb-3 text-green-500"></i>
              <h2 class="text-xl font-semibold text-gray-900 mb-2">Feito!</h2>
              <p class="text-gray-700 text-sm">A operação foi concluída com sucesso.</p>
            </div>
        `,
        customClass: {
          popup: "rounded-xl p-6 shadow-xl bg-white",
          confirmButton: "bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium",
        },
        buttonsStyling: false,
        didOpen: () => {
          lucide.createIcons();
          if (acao) {
            window.location.href = acao;
          } else {
            console.warn("Sem action definida");
          }
        }
      });

    }
  });

});


// ==========================
// CONFIRMAR APÓS SUBMIT DE CADASTRO
// ==========================
const form = document.querySelector("#form-submit");

if (form) {
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const form = this;

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const titulo = form.dataset.title || "Sucesso!";
    const mensagem = form.dataset.msg || "Tudo certo!";
    const destino = form.dataset.redirect || "index.html";

    Swal.fire({
      html: `
      <div class="flex flex-col items-center text-center">
        <i data-lucide="circle-check" class="w-14 h-14 mb-3 text-green-500"></i>
        <h2 class="text-xl font-semibold text-gray-900 mb-2">${titulo}</h2>
        <p class="text-gray-700 text-sm">${mensagem}</p>
      </div>
    `,

      showConfirmButton: true,
      confirmButtonText: "Fechar",
      customClass: {
        popup: "rounded-xl p-6 shadow-xl bg-white",
        confirmButton: "bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium",
      },
      buttonsStyling: false,
      didOpen: () => lucide.createIcons(),
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = destino;
      }
    });
  });
}

// ==========================
// CONFIRMAR APÓS SUBMIT DE EDITAR
// ==========================
// REPLICAR BLOCO DE CONFIRMAÇÃO DE SUBMIT DE CADASTRO ACIMA


// ===========================
// Alternar exibição do campo add sócio (com base no tipo de cliente)
// ===========================

window.addEventListener('DOMContentLoaded', function () {
  const tipoSelect = document.getElementById('tipo-cliente');
  const grupoSocios = document.getElementById('grupo-socios');

  function atualizarExibicaoSocios() {
    const tipo = tipoSelect.value;
    grupoSocios.classList.toggle('hidden', tipo === 'particular');
  }

  atualizarExibicaoSocios(); // já verifica o estado no início
  tipoSelect.addEventListener('change', atualizarExibicaoSocios);
});

// ===========================
// Alternar exibição da seção de análise (com base no ano de análise)
// ===========================
const selectAno = document.getElementById('anoAnalise');
const secaoAccoes = document.getElementById('accoes');

if (selectAno && secaoAccoes) {
  selectAno.addEventListener('change', () => {
    if (selectAno.value) {
      secaoAccoes.classList.remove('hidden');
    } else {
      secaoAccoes.classList.add('hidden');
    }
  });
}

// ===========================
// Alternar exibição da seção de análise (com base no ano de análise)
// ===========================
const valorDebito = document.getElementById('valor_debito');

valorDebito.addEventListener('input', () => {
  const valor = parseFloat(valorDebito.value);
  const valorFormatado = valor.toLocaleString('pt-PT', { style: 'currency', currency: 'EUR' });
  valorDebito.value = valorFormatado;
});
