<div class="modal fade" id="modaladdcliente" tabindex="-1" role="dialog" aria-labelledby="modaladdclienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0">

      <div class="modal-header bg-info text-white">
        <h4 class="modal-title" id="modaladdclienteLabel">
          <i class="fas fa-user-plus"></i> Novo Cliente
        </h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="formAddCliente" method="POST">
          @csrf

          <!-- DADOS PESSOAIS -->
          <h5 class="text-primary mb-2"><i class="fas fa-id-card"></i> Dados Pessoais</h5>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="nomeCliente">Nome</label>
              <input type="text" class="form-control" id="nomeCliente" name="nomeCliente"
                     placeholder="Nome completo"
                     value="@isset($cliente){{$cliente[0]->nome}}@endisset" required>
            </div>

            <div class="form-group col-md-2">
              <label for="idadeCliente">Idade</label>
              <input type="number" class="form-control" id="idadeCliente" name="idadeCliente"
                     placeholder="Idade"
                     value="@isset($cliente){{$cliente[0]->idade}}@endisset">
            </div>

            <div class="form-group col-md-3">
              <label for="sexoCliente">Sexo</label>
              <select class="form-control" id="sexoCliente" name="sexoCliente">
                <option value="">Selecione</option>
                <option>Masculino</option>
                <option>Feminino</option>
                <option>Outro</option>
              </select>
            </div>

            <div class="form-group col-md-3">
              <label for="estadoCivil">Estado Civil</label>
              <select class="form-control" id="estadoCivil" name="estadoCivil">
                <option value="">Selecione</option>
                <option>Solteiro(a)</option>
                <option>Casado(a)</option>
                <option>Divorciado(a)</option>
                <option>Viúvo(a)</option>
                <option>União Estável</option>
              </select>
            </div>
          </div>

          <hr>

          <!-- COMPLEMENTARES -->
          <h5 class="text-primary mb-2"><i class="fas fa-briefcase"></i> Informações Complementares</h5>
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="possuiFilhos">Possui filhos?</label>
              <select class="form-control" id="possuiFilhos" name="possuiFilhos" onchange="toggleQtdFilhos()">
                <option value="">Selecione</option>
                <option>Sim</option>
                <option>Não</option>
              </select>
            </div>

            <div class="form-group col-md-3" id="qtdFilhosContainer" style="display:none;">
              <label for="qtdFilhos">Quantidade de filhos</label>
              <input type="number" class="form-control" id="qtdFilhos" name="qtdFilhos" min="0" placeholder="Ex.: 2">
            </div>

            <div class="form-group col-md-3">
              <label for="profissao">Profissão</label>
              <input type="text" class="form-control" id="profissao" name="profissao" placeholder="Profissão atual">
            </div>

            <div class="form-group col-md-3">
              <label for="diagnostico">Diagnóstico psiquiátrico</label>
              <input type="text" class="form-control" id="diagnostico" name="diagnostico"
                     placeholder="Ex.: Ansiedade, Depressão, TDAH...">
            </div>
          </div>

          <hr>

          <!-- MOTIVAÇÃO -->
          <h5 class="text-primary mb-2"><i class="fas fa-comment-dots"></i> Motivação</h5>
          <div class="form-group">
            <textarea id="motivacao" name="motivacao" rows="3" class="form-control"
                      placeholder="O que motivou a buscar atendimento? (Ex.: ansiedade, depressão, traumas...)"></textarea>
          </div>

          <hr>

          <!-- ANOTAÇÕES -->
          <h5 class="text-primary mb-2"><i class="fas fa-sticky-note"></i> Anotações Internas</h5>
          <div class="form-group">
            <textarea id="notes" name="notes" rows="4" class="form-control"
                      placeholder="Observações adicionais sobre o histórico, percepções iniciais, etc.">@isset($cliente){{$cliente[0]->notes}}@endisset</textarea>
          </div>

          <div id="msg" class="mt-2"></div>
        </form>
      </div>

      <div class="modal-footer bg-light">
        @if(!isset($cliente))
          <button type="button" class="btn btn-success" id="btnAddCliente" onclick="saveCliente()">
            <i class="fas fa-save"></i> Salvar
          </button>
        @else
          <button type="button" class="btn btn-success" id="btnAddCliente" onclick="saveCliente('{{$cliente[0]->id}}')">
            <i class="fas fa-save"></i> Salvar
          </button>
        @endif

        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Fechar
        </button>

        <span id="loadingCliente" style="display:none;">
          <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px;">
        </span>
      </div>

    </div>
  </div>
</div>

<script>
function toggleQtdFilhos() {
  const possui = document.getElementById("possuiFilhos").value;
  const qtd = document.getElementById("qtdFilhosContainer");
  qtd.style.display = (possui === "Sim") ? "block" : "none";
}
</script>
