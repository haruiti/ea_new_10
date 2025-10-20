
<script>
    $(document).ready(function() {
        $("#dataDesp").datetimepicker({
            uiLibrary: 'bootstrap4',
            modal: true,
            footer: true,
            format: 'dd/mm/yyyy HH:MM',
            showOnFocus: true,
            showRightIcon: false
        });
    });

    $(document).ready(function() {
        $("#valorDesp").maskMoney({prefix:'', allowNegative: true, thousands:'', decimal:'.', affixesStay: true, allowZero: true});
    });

    $(document).ready(function() {
        $('#datatable-despesa').DataTable({
            dom: 'Bfrtip',
            buttons: [],
            paging: true,
            lengthMenu: [ [7, 14, 28, -1], [7, 14, 28, "Tudo"] ],
            language: {
                        "lengthMenu": "Exibindo _MENU_ Registros por página",
                        "zeroRecords": "Nenhum registro encontrado",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar: ",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "infoFiltered": "(filtrados de um total de _MAX_ registros)"
                        }
        });

    });
</script>
<div class="modal fade modal bd-example-modal-xl" id="modalDespesa" tabindex="-1" role="dialog"
     aria-labelledby="modalDespesa">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="modal-title">
                    <h4>Despesa</h4>
                </div>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- /.modal-header -->
            <div class="modal-body">
                <div class='x_panel'>
                    <form action="" method="POST" id="formAddServico">
                        <div class="row">
                            <div class="col-md-3">
                                <input class="form-control" style="display:none"
                                    id="idDesp"
                                    name="idDesp"
                                    autocomplete="off">
                                <label>Data:</label>
                                <input class="form-control"
                                    id="dataDesp"
                                    name="dataDesp"
                                    autocomplete="off">
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label>Categoria:</label>
                                    <select name="categoria" id="categoria" class="js-example-basic-single form-control" style="width: 100%">
                                        <option value="Transporte">Transporte</option>
                                        <option value="Sala">Sala</option>
                                        <option value="Marketing">Marketing</option>
                                        <option value="Alimentação">Alimentação</option>
                                        <option value="Material">Material</option>
                                    </select>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <label>Despesa:</label>
                                    <input class="form-control"
                                    id="despesa"
                                    name="despesa"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label>Valor:</label>
                                <input class="form-control"
                                    id="valorDesp"
                                    name="valorDesp"
                                    placeholder="Valor"
                                    autocomplete="off">
                            </div>
                        </div>
                    </form><br>

                    <span id='loadingDespesa' style="display:none">
                        <img src="{{asset('imagens/loading.gif')}}" style="width:35px;height:35px; position:relative; left:0;top: 0px;">
                    </span>

                    <button type="button" class="btn btn-danger float-right"
                    title="Fechar addVenda" alt="Fechar addVenda"
                    id="btncVendaModalClose" onclick="despesaModalClose()">Fechar <i class="fas fa-window-close"></i>
                    </button>
                    <button type="button" class="btn btn-success float-right"
                        title="Salvar Venda" alt="Salvar Venda"
                        id="btnSaveVenda" onclick="saveDespesa()">Salvar <i
                            class="fas fa-save"></i>
                    </button>
                    <div class="col-sm-4 col-md-4" id='msg'></div>
                </div><br><br><br>
                <div class='x_panel'>
                    <div id="tableDespesa">
                </div>
                </div>
            </div> <!-- /.modal-body -->
            <div class="modal-footer">

            </div> <!-- /.modal-footer -->

        </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

