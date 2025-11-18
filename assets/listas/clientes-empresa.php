<?php
require_once '../../assets/conf/conf-dbcon.php';
require_once '../../assets/models/mdl-empresa.php';

session_start();

if (isset($_SESSION['us_id'])) {
    $search = htmlspecialchars($_SESSION['us_id']);
    $stmt = $conn->prepare("SELECT * FROM empresas as emp INNER JOIN empresas_usr ON emp.id = empresas_usr.empresa_id INNER JOIN usuario ON empresas_usr.usuario_id = usuario.id WHERE usuario.id = :search LIMIT 10");
    $stmt->bindParam(':search', $search);
    $stmt->execute();
    $empresas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $content = '';
    $cont = 1;
    foreach ($empresas as $empresa) {
        $content .= '<tr>
                      <td>'.$cont.'</td>
                      <td>'.$empresa["nome"].'</td>
                      <td>'.$empresa["capital_social"].'</td>
                      <td>'.$empresa["contacto_id"].'</td>
                      <td>'.$empresa["email"].'</td>
                      <td class="px-3 text-gray-500">'.$empresa["tipo"].'</td>
                      <td>'.$empresa["tamanho"].'</td>
                      <td>'.$empresa["sector_atividade"].'</td>
                      <td class="pr-4 text-right">
                        <div class="relative">
                          <button
                            class="p-1 text-gray-500 transition-colors duration-200 rounded-full hover:text-gray-700 hover:bg-gray-100"
                            onclick="toggleDropdown(this)">
                            <i data-lucide="more-vertical" class="w-4 h-4"></i>
                          </button>

                          <div class="hidden bg-white border border-gray-200 rounded-lg shadow-lg w-36 dropdown-menu">
                            <div class="py-1">
                              <button onclick="window.location.href =\'visualizar-cliente-empresa?empresa='.$empresa["id"].' \'"
                                class="flex items-center w-full gap-1 px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-50">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                ver
                              </button>
                              <button onclick="window.location.href =\'editar-cliente.html?empresa='.$empresa["id"].'\'"
                                class="flex items-center w-full gap-2 px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-50">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                Editar
                              </button>
                              <button class="flex items-center w-full gap-2 px-4 py-2 text-sm text-left btn-acao"
                                data-tipo="apagar" data-msg="Tem certeza que quer remover este cliente?"
                                data-title="Remover Cliente">
                                <i data-lucide="trash2" class="w-4 h-4"></i>
                                Remover
                              </button>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>';
        $cont++;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM empresas");
}
      echo $content;

?>