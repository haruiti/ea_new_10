
<script>
    $(document).ready(function() {
        $("#dataSessao").datetimepicker({
            uiLibrary: 'bootstrap4',
            modal: true,
            footer: true,
            format: 'dd/mm/yyyy HH:MM'
        });
    });

    $(document).ready(function() {
        $("#valorPago").maskMoney({prefix:'', allowNegative: true, thousands:'', decimal:'.', affixesStay: true, allowZero: true});
    });
</script>
<div class="modal fade" id="modalSessao" tabindex="-1" role="dialog"
     aria-labelledby="modalSessao">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="modal-title">
                    <h3>Editar Sessão</h3>
                </div>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- /.modal-header -->

            <div class="modal-body">

                <form action="" method="POST" id="formAddServico">

                    <div class="row">
                        <div class="col-md-4">
                            <label>Data:</label>
                            <input class="form-control"
                                   id="dataSessao"
                                   name="dataSessao"
                                   @if(isset($sessaoInfo[0]->t_data))
                                   value="{{ date('d/m/Y H:i', strtotime($sessaoInfo[0]->t_data)) }}"
                                   @else
                                   value="{{ date('d/m/Y H:i') }}"
                                   @endif
                                   autocomplete="off">
                        </div>
                        <div class="col-sm-7 col-md-7">
                            <label>Anotação:</label>
                            @if(isset($sessaoInfo[0]->t_note))
                                <textarea class="form-control" rows="7" cols="70" autocomplete="off" id="noteSessao" name="noteSessao">{{ $sessaoInfo[0]->t_note }}</textarea>
                            @else
                                <textarea class="form-control" rows="7" cols="70" autocomplete="off" id="noteSessao" name="noteSessao"></textarea>
                            @endif
                        </div>

                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Valor:</label>
                            <input class="form-control"
                                   id="valorPago"
                                   name="valorPago"
                                   placeholder="Valor"
                                   @if(isset($sessaoInfo[0]->valor))
                                   value="{{$sessaoInfo[0]->valor}}"
                                   @else
                                   value=""
                                   @endif
                                   autocomplete="off">
                        </div>
                    </div>
                </form>
            </div> <!-- /.modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success"
                    title="Salvar Sessao" alt="Salvar Sessao"
                    id="btnSaveSessao" onclick="saveSessao('{{$sessaoInfo[0]->id}}','{{$sessaoInfo[0]->venda_id}}')">Salvar <i
                        class="fas fa-save"></i>
                </button>
                <button type="button" class="btn btn-danger"
                    title="Fechar addSessao" alt="Fechar addSessao"
                    id="btncSessaoModalClose" onclick="sessaoModalClose()">Fechar <i class="fas fa-window-close"></i>
                </button>
                <div class="col-sm-2 col-md-2">
                    <span id='loadingSessao' style="display:none">
                        <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px; position:relative; left:0;top: 0px;">
                    </span>
                </div>
                <div id='msg'></div>
            </div> <!-- /.modal-footer -->
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

