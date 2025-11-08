<div class="modal fade" id="modaladdcliente" tabindex="-1" role="dialog" aria-labelledby="modaladdcliente">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-title">
                    <h3>Novo Cliente</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="" method="POST" id="formAddCliente">
                    @csrf

                    <!-- Dados Pessoais -->
                    <h5 class="mb-2"><b>Dados Pessoais</b></h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nome:</label>
                            <input class="form-control" id="nomeCliente" name="nomeCliente"
                                placeholder="Nome completo" autocomplete="off"
                                value="@if(isset($cliente)){{$cliente[0]->nome}}@endif">
                        </div>

                        <div class="col-md-2">
                            <label>Idade:</label>
                            <input class="form-control" id="idadeCliente" name="idadeCliente"
                                placeholder="Idade" autocomplete="off"
                                value="@if(isset($cliente)){{$cliente[0]->idade}}@endif">
                        </div>

                        <div class="col-md-3">
                            <label>Sexo:</label>
                            <select class="form-control" id="sexoCliente" name="sexoCliente">
                                <option value="">Selecione</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Estado Civil:</label>
                            <select class="form-control" id="estadoCivil" name="estadoCivil">
                                <option value="">Selecione</option>
                                <option value="Solteiro(a)">Solteiro(a)</option>
                                <option value="Casado(a)">Casado(a)</option>
                                <option value="Divorciado(a)">Divorciado(a)</option>
                                <option value="Viúvo(a)">Viúvo(a)</option>
                                <option value="União Estável">União Estável</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!-- Informações Familiares e Profissionais -->
                    <h5 class="mb-2"><b>Informações Complementares</b></h5>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Possui filhos?</label>
                            <select class="form-control" id="possuiFilhos" name="possuiFilhos" onchange="toggleQtdFilhos()">
                                <option value="">Selecione</option>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>

                        <div class="col-md-3" id="qtdFilhosContainer" style="display:none;">
                            <label>Quantidade de filhos:</label>
                            <input type="number" min="0" class="form-control" id="qtdFilhos" name="qtdFilhos" placeholder="Ex.: 2">
                        </div>

                        <div class="col-md-3">
                            <label>Profissão:</label>
                            <input type="text" class="form-control" id="profissao" name="profissao" placeholder="Profissão atual">
                        </div>

                        <div class="col-md-3">
                            <label>Diagnóstico psiquiátrico (se houver):</label>
                            <input type="text" class="form-control" id="diagnostico" name="diagnostico"
                                placeholder="Ex.: Ansiedade, Depressão, TDAH...">
                        </div>
                    </div>

                    <hr>

                    <!-- Motivação -->
                    <h5 class="mb-2"><b>Motivação</b></h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label>O que motivou a buscar atendimento?</label>
                            <textarea id="motivacao" name="motivacao" rows="3" class="form-control"
                                placeholder="Ex.: Tratar ansiedade, depressão, traumas, fobias, desenvolvimento pessoal..."></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- Anotações -->
                    <h5 class="mb-2"><b>Anotações Internas</b></h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Anotações:</label>
                            <textarea id="notes" name="notes" rows="5" class="form-control"
                                placeholder="Anotações sobre histórico, observações da avaliação, etc.">@if(isset($cliente)){{$cliente[0]->notes}}@endif</textarea>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-9">
                            <div id='msg'></div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                @if(!isset($cliente))
                    <button type="button" class="btn btn-success" title="Salvar Cliente" id="btnAddCliente" onclick="saveCliente()">
                        Salvar <i class="fas fa-save"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-success" title="Salvar Cliente" id="btnAddCliente" onclick="saveCliente('{{$cliente[0]->id}}')">
                        Salvar <i class="fas fa-save"></i>
                    </button>
                @endif

                <button type="button" class="btn btn-danger" title="Fechar" id="btnclienteModalClose" onclick="clienteModalClose()">
                    Fechar <i class="fas fa-window-close"></i>
                </button>

                <div class="col-sm-2 col-md-2">
                    <span id='loadingCliente' style="display:none">
                        <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px;">
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleQtdFilhos() {
        const possuiFilhos = document.getElementById("possuiFilhos").value;
        const qtdContainer = document.getElementById("qtdFilhosContainer");
        qtdContainer.style.display = (possuiFilhos === "Sim") ? "block" : "none";
    }
</script>
