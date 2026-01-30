<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Controle | Gestão Corporativa</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../static/css/styles.css">
</head>

<body>

  <div class="main-container">

    <!-- Header & Summary -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">Registro de Controle</h4>
        <p class="text-muted small mb-0">Gestão de trocas e monitoramento de frota</p>
      </div>
      <div class="text-end">
        <span class="badge bg-light text-dark border">v2.0 Stable</span>
      </div>
    </div>

    <!-- Dashboard Summary -->
    <div class="summary-grid">
      <div class="summary-card">
        <span class="label">Total de Registros</span>
        <span class="value" id="statTotal">0</span>
      </div>
      <div class="summary-card">
        <span class="label">Trocados</span>
        <span class="value text-success" id="statTrocados">0</span>
      </div>
      <div class="summary-card">
        <span class="label">Pendentes</span>
        <span class="value text-danger" id="statPendentes">0</span>
      </div>
    </div>

    <!-- FORMULÁRIO -->
    <div class="card-custom">
      <div class="section-title">
        <i class="bi bi-plus-circle text-primary"></i> Novo Registro
      </div>
      <form method="POST" action="../api/register.php">
        <div class="row g-4">
          <!-- Número do carro -->
          <div class="col-md-3">
            <label class="form-label">Número do carro</label>
            <input type="number" name="carro" id="carroInput" class="form-control" placeholder="Ex: 2407" min="2400"
              max="2459" required>
          </div>

          <!-- Data do registro -->
          <div class="col-md-3">
            <label class="form-label">Data do registro</label>
            <input type="date" name="data" id="dataRegistro" class="form-control" required>
          </div>

          <!-- Gravação -->
          <div class="col-md-3">
            <label class="form-label">Possui gravação</label>
            <div class="d-flex gap-3 pt-1">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gravacao" id="gravSim" value="1">
                <label class="form-check-label" for="gravSim">Sim</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gravacao" id="gravNao" value="0" checked>
                <label class="form-check-label" for="gravNao">Não</label>
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <div class="d-flex gap-3 pt-1">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="trocado" id="statusSim" value="1">
                <label class="form-check-label" for="statusSim">Trocado</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="trocado" id="statusNao" value="0" checked>
                <label class="form-check-label" for="statusNao">Não trocado</label>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end">
          <button type="submit" id="submitBtn" class="btn btn-primary">
            <i class="bi bi-check2-circle me-1"></i> Registrar
          </button>
        </div>
      </form>
    </div>

    <!-- REGISTROS ANTERIORES -->
    <div class="section-title mt-5">
      <i class="bi bi-list-ul text-primary"></i> Registros Anteriores
    </div>

    <!-- BARRA DE AÇÕES (BUSCA + FILTROS) -->
    <div class="action-bar">
      <div class="search-input-group">
        <i class="bi bi-search"></i>
        <input type="text" id="searchCarro" class="form-control" placeholder="Buscar por número do carro...">
      </div>

      <div style="min-width: 150px;">
        <label class="form-label small text-muted mb-1">Status</label>
        <select id="filterStatus" class="form-select form-select-sm">
          <option value="all">Todos Status</option>
          <option value="1">Trocado</option>
          <option value="0">Não trocado</option>
        </select>
      </div>

      <div style="min-width: 150px;">
        <label class="form-label small text-muted mb-1">Gravação</label>
        <select id="filterGravacao" class="form-select form-select-sm">
          <option value="all">Todas Gravações</option>
          <option value="1">Sim</option>
          <option value="0">Não</option>
        </select>
      </div>

      <div class="d-flex gap-2 align-items-end">
        <div>
          <label class="form-label small text-muted mb-1">De:</label>
          <input type="date" id="filterDateStart" class="form-control form-control-sm">
        </div>
        <div>
          <label class="form-label small text-muted mb-1">Até:</label>
          <input type="date" id="filterDateEnd" class="form-control form-control-sm">
        </div>
      </div>
    </div>

    <!-- TABELA DE REGISTROS -->
    <div class="table-container">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Data <i class="bi bi-arrow-down-up ms-1 small"></i></th>
              <th>Carro <i class="bi bi-arrow-down-up ms-1 small"></i></th>
              <th>Gravação</th>
              <th>Status</th>
              <th class="text-end">Ações</th>
            </tr>
          </thead>

          <?php
          include("../src/db/db.php");

          $result = mysqli_query($conn, "SELECT * FROM registros");
          ?>

          <tbody id="recordsTableBody">
            <?php while ($row = mysqli_fetch_assoc($result)) {
              // A lógica deve ficar DENTRO do loop para cada linha ser avaliada individualmente
              $statusTrocado = $row['trocado'];
              $classeCor = $statusTrocado ? 'text-success' : 'text-danger';
              $icone     = $statusTrocado ? 'fa-check-circle' : 'fa-times-circle';
              $texto     = $statusTrocado ? 'Trocado' : 'Não trocado';
            ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($row['data_registro'])) ?></td>
                <td><?= htmlspecialchars($row['carro']) ?></td>
                <td>
                  <span class="badge <?= $row['gravacao'] ? 'bg-info' : 'bg-secondary' ?>">
                    <?= $row['gravacao'] ? 'Sim' : 'Não' ?>
                  </span>
                </td>
                <td class="<?= $classeCor ?> fw-bold">
                  <i class="fas <?= $icone ?> me-1"></i> <?= $texto ?>
                </td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> Ver
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>

        </table>
      </div>
    </div>

  </div>

  <!-- Toast Feedback Container -->
  <div id="toastContainer" class="toast-container"></div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="../static/js/script.js"></script>
  <script>
    function statusBadge(valor) {
      return valor == 1 ?
        `<span class="badge bg-success"><i class="bi bi-check-circle"></i> Trocado</span>` :
        `<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Não trocado</span>`;
    }
  </script>


</body>

</html>