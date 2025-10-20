
<script>
    $(document).ready(function() {
        $("#dataVenda").datetimepicker({
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
<div class="modal fade" id="modaladdvenda" tabindex="-1" role="dialog"
     aria-labelledby="modaladdvenda">
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
                                   id="dataVenda"
                                   name="dataVenda"
                                   @if(isset($venda))
                                   value="{{ date('d/m/Y H:i', strtotime($venda[0]->data)) }}"
                                   @else
                                   value="{{ date('d/m/Y H:i') }}"
                                   @endif
                                   autocomplete="off">
                        </div>
                        <div class="col-sm-5 col-md-5">
                            <label>Pacote:</label>
                            <select name="pacote" id="pacote" class="js-example-basic-single form-control" style="width: 100%">
                            @if(isset($venda))
                                @foreach($pacotes as $pacote)
                                    @if($pacote->id == $venda[0]->pacote_id )
                                        <option param="{{$pacote->valor}}" value="{{$pacote->id}}" selected>{{$pacote->p_nome}} - Qtde {{$pacote->n_sessoes}} - {{$pacote->valor}} </option>
                                    @else
                                        <option param="{{$pacote->valor}}" value="{{$pacote->id}}">{{$pacote->p_nome}} - Qtde {{$pacote->n_sessoes}} - {{$pacote->valor}} </option>
                                    @endif
                                @endforeach
                            @else
                                @foreach($pacotes as $pacote)
                                    <option param="{{$pacote->valor}}" value="{{$pacote->id}}">{{$pacote->p_nome}} - Qtde {{$pacote->n_sessoes}} - {{$pacote->valor}} </option>
                                @endforeach
                            @endif
                            </select>
                        </div>

                    </div>
                    <br>

                    <div class="row">

                        <div class="col-sm-5 col-md-5">
                            <label>Pagamento:</label>
                            <select name="pagamento" id="pagamento" class="js-example-basic-single form-control" style="width: 100%">
                                @if(isset($venda))
                                    @foreach($formaPagamento as $pagamento)
                                        @if($pagamento->forma_pagamento == $venda[0]->forma_pagamento )
                                            <option value="{{$pagamento->id}}" selected>{{$pagamento->forma_pagamento}}</option>
                                        @else
                                            <option value="{{$pagamento->id}}" >{{$pagamento->forma_pagamento}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach($formaPagamento as $pagamento)
                                        <option value="{{$pagamento->id}}">{{$pagamento->forma_pagamento}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Valor Pago:</label>
                            <input class="form-control"
                                   id="valorPago"
                                   name="valorPago"
                                   placeholder="Valor"
                                   value="@if(isset($venda)){{$venda[0]->valor_pago}}@endif"
                                   autocomplete="off">
                        </div>
                    </div>
                </form>
            </div> <!-- /.modal-body -->
            <div class="modal-footer">
                @if(!isset($venda))
                    <button type="button" class="btn btn-success"
                        title="Salvar Venda" alt="Salvar Venda"
                        id="btnSaveVenda" onclick="saveVenda('{{$cliente_id}}', 'cliente')">Salvar <i
                            class="fas fa-save"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-success"
                        title="Salvar Venda" alt="Salvar Venda"
                        id="btnSaveVenda" onclick="saveVenda('{{$venda[0]->id}}', 'venda', '{{$venda[0]->pacote_id}}', {{$venda[0]->valor_pago}})">Salvar <i
                            class="fas fa-save"></i>
                    </button>
                @endif
                <button type="button" class="btn btn-danger"
                    title="Fechar addVenda" alt="Fechar addVenda"
                    id="btncVendaModalClose" onclick="vendaModalClose()">Fechar <i class="fas fa-window-close"></i>
                </button>
                <div class="col-sm-2 col-md-2">
                    <span id='loadingVenda' style="display:none">
                        <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px; position:relative; left:0;top: 0px;">
                    </span>
                </div>
                <div id='msg'></div>
            </div> <!-- /.modal-footer -->
        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

