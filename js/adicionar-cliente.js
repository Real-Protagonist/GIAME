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