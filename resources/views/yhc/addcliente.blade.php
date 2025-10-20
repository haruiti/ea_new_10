<div class="modal fade" id="modaladdcliente" tabindex="-1" role="dialog"
     aria-labelledby="modaladdcliente">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="modal-title">
                    <h3>Novo Cliente</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- /.modal-header -->

            <div class="modal-body">

                <form action="" method="POST" id="formAddCliente">

                    <div class="row">
                        <div class="col-md-4">
                            <label>Nome:</label>
                            <input class="form-control"
                                   id="nomeCliente"
                                   name="nomeCliente"
                                   placeholder="Nome do Cliente"
                                   value="@if(isset($cliente)){{$cliente[0]->nome}}@endif"
                                   autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label>Idade:</label>
                            <input class="form-control"
                                   id="idadeCliente"
                                   name="idadeCliente"
                                   placeholder="Idade do Cliente"
                                   value="@if(isset($cliente)){{$cliente[0]->idade}}@endif"
                                   autocomplete="off">
                        </div>
                    </div>
                    <br>

                    <div class="row">

                        <div class="col-md-6">

                            <label>Anotações:</label>
                            @if(isset($cliente))
                                <textarea id="notes" name="notes" rows="7" cols="70" name="comment">{{$cliente[0]->notes}}</textarea>
                            @else
                                <textarea id="notes" name="notes" rows="7" cols="70" name="comment"></textarea>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div id='msg'></div>
                        </div>
                    </div>
                </form>
            </div> <!-- /.modal-body -->
            <div class="modal-footer">
                @if(!isset($cliente))
                    <button type="button" class="btn btn-success"
                        title="Salvar Cliente" alt="Salvar Cliente"
                        id="btnAddCliente" onclick="saveCliente()">Salvar <i
                            class="fas fa-save"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-success"
                        title="Salvar Cliente" alt="Salvar Cliente"
                        id="btnAddCliente" onclick="saveCliente('{{$cliente[0]->id}}')">Salvar <i
                            class="fas fa-save"></i>
                    </button>
                @endif
                <button type="button" class="btn btn-danger"
                    title="Fechar addCliente" alt="Fechar addCliente"
                    id="btnclienteModalClose" onclick="clienteModalClose()">Fechar <i class="fas fa-window-close"></i>
                </button>
                <div class="col-sm-2 col-md-2">
                    <span id='loadingCliente' style="display:none">
                        <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px; position:relative; left:0;top: 0px;">
                    </span>
                </div>

            </div> <!-- /.modal-footer -->
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
