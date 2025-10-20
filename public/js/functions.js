

$(document).ready(function() {
    $('.js-example-basic-single').select2({
        width: 'resolve'
    });
});

$(document).ready(function() {

    $("#cliente").on('change', function() {
        getVenda();
    });

});


function getAgendamentos(){
    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });


    $.ajax("agendamentos/show", {
        method: "GET",
        cache: false,
    //   data: { data: data },
        beforeSend: function() {
        $("#table").html("");
        },
        success: function(response) {
        $("#table").html(response);

        $('#datatable-agendamentos').DataTable({
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

        },
        error: function(erro) {
            alert("Erro!");

        }
    });
}
function addClienteModal() {
    $("#loading").show();
    $.ajax({
        type: "GET",
        url: "addcliente",
        success: function(data) {
            $("#loading").hide();
            $("#modal").html(data);
            $("#modaladdcliente").modal("show");

        },
        error: function(data) {
            $("#loading").hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}

function editClienteModal(){
    $("#loading").show();
    var data = {
        id: $("#cliente").val()
    };

    $.ajax({
        type: "GET",
        url: "editcliente",
        data: data,
        success: function(data) {
            $("#loading").hide();
            $("#modal").html(data);
            $("#modaladdcliente").modal("show");
        },
        error: function(data) {
            $("#loading").hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}

function saveCliente(id = null)
{
    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    if($("#nomeCliente").val() == ''){
        $('#msg').html("<div class='alert alert-warning'><strong>O nome do cliente é obrigatório!</strong></div>");
    }
    nome=$("#nomeCliente").val();

    $("#loadingCliente").show();
    if(id==null){
        var data = {
            nome: $("#nomeCliente").val(),
            idade: $("#idadeCliente").val(),
            notes: $("#notes").val()
        };
    }else{
        var data = {
            id: id,
            nome: $("#nomeCliente").val(),
            idade: $("#idadeCliente").val(),
            notes: $("#notes").val()
        };
    }

    $.ajax({
        type: "GET",
        url: "savecliente",
        data: data,
        success: function(response) {

            $("#loadingCliente").hide();

            if(id==null){
                $("#cliente").append(
                    '<option value="' +
                        response +
                        '" selected>' +
                        nome +
                        "</option>"
                );
            }else{
                $("#cliente").append(
                    '<option value="' +
                        id +
                        '" selected>' +
                        nome +
                        "</option>"
                );
            }

            getVenda();
            clienteModalClose();
            $('#msgMain').html("<div class='alert alert-success'><strong>Cliente "+$("#nomeCliente").val()+" cadastrado com sucesso!</strong></div>");
            $("#msgMain").fadeOut(3000);



        },
        error: function(error) {
            $("#loadingCliente").show();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    error.responseJSON.message +
                    "</div>"
            );
        }
    });

}

function saveEditCliente()
{

}

function clienteModalClose()
{
    $('#modaladdcliente').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function vendaModalClose()
{
    $('#modaladdvenda').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function sessaoModalClose(){
    $('#modalSessao').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function despesaModalClose(){
    $('#modalDespesa').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function getVenda()
{

    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });


    $.ajax("vendas/show", {
        method: "GET",
        cache: false,
        data: { id: $("#cliente").val() },
        beforeSend: function() {
            $("#tableSessao").html("");
            $("#table").html("");
            $("#loading").show();
        },
        success: function(response) {
            $("#loading").hide();
            $("#table").html(response);

            $('#datatable-vendas').DataTable({
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

        },
        error: function(erro) {
            $("#loading").hide();
            alert(JSON.stringify(erro, true));

        }
    });
}

function addVendaModal(id=null)
{

    if(id){
        $("#loadingVenda"+id).show();
    }else{
        $("#loading").show();
    }

    $.ajax({
        type: "GET",
        url: "addVendaModal",
        data: { cliente_id: $('#cliente').val(),
                id: id },
        success: function(data) {
            if(id){
                $("#loadingVenda"+id).hide();
            }else{
                $("#loading").hide();
            }
            $("#modal").html(data);
            $("#modaladdvenda").modal("show");
        },
        error: function(data) {
            if(id){
                $("#loadingVenda"+id).hide();
            }else{
                $("#loading").hide();
            }
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}


function excluirVenda(nome=null, id, idsecundario=null)
{
    $("#loadingVenda"+id).show();
    $.ajax({
        type: "GET",
        url: "deleteVenda",
        data: { id: id},
        success: function(data) {
            $("#loadingVenda"+id).hide();
            $('#msgMain').html("<div class='alert alert-success'><strong>Venda excluída com sucesso!</strong></div>");

            $("#msgMain").fadeOut(3000);

            getVenda();
        },
        error: function(data) {
            $("#loadingVenda"+id).hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}


function saveVenda(id , criterio, pacote_id=null, valor_pago=null )
{
    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    if($("#dataVenda").val() == '' || $("#valorPago").val() == '' ){
        $('#msg').html("<div class='alert alert-warning'><strong>Todos os campos são obrigatórios!</strong></div>");
        return;
    }
    $("#loadingVenda").show();

    if(criterio=='cliente'){
        var data = {
            cliente_id: id,
            data: $("#dataVenda").val(),
            pacote_id: $("#pacote").val(),
            forma_pagamento: $("#pagamento").val(),
            valorPacote: $("#pacote").children(":selected").attr("param"),
            valorPago: $("#valorPago").val()
        };
    }else{
        var data = {
            id: id,
            data: $("#dataVenda").val(),
            pacote_id: $("#pacote").val(),
            forma_pagamento: $("#pagamento").val(),
            valorPacote: $("#pacote").children(":selected").attr("param"),
            valorPago: $("#valorPago").val(),
            pacote_id_old: pacote_id,
            valor_pago_old: valor_pago
        };
    }



    $.ajax({
        type: "GET",
        url: "saveVenda",
        data: data,
        success: function(response) {

            $("#loadingVenda").hide();

            $('#msgMain').html("<div class='alert alert-success'><strong>Servço cadastrado com sucesso!</strong></div>");
            $("#msgMain").fadeOut(3000);
            getVenda();
            vendaModalClose();
        },
        error: function(error) {
            $("#loadingVenda").show();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    error.responseJSON.message +
                    "</div>"
            );
        }
    });
}
function getSessao(id)
{

    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });


    $.ajax("sessoes/show", {
        method: "GET",
        cache: false,
        data: { id: id },
        beforeSend: function() {
            $("#tableSessao").html("");
            $("#loadingVenda"+id).show();
        },
        success: function(response) {
            $("#loadingVenda"+id).hide();
            $("#tableSessao").html(response);

            $('#datatable-sessao').DataTable({
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

                $('#datatable-vendas > tbody  > tr').each(function(index, tr) {
                    $(tr).css('background-color','#EBEDEF');
                });

                $("#tr"+id).css('background-color','#FDE284');

        },
        error: function(erro) {
            $("#loadingVenda"+id).hide();
            alert(JSON.stringify(erro, true));

        }
    });
}

function editarSessao(id){
    $("#loadingSessao"+id).show();
    $.ajax({
        type: "GET",
        url: "editarSessao",
        data: { cliente_id: $('#cliente').val(),
                id: id },
        success: function(data) {
            $("#loadingSessao"+id).hide();
            $("#modal").html(data);
            $("#modalSessao").modal("show");
        },
        error: function(data) {
            $("#loadingSessao"+id).hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}

function excluirSessao(nome, id, venda_id){
    $("#loadingSessao"+id).show();
    $.ajax({
        type: "GET",
        url: "deleteSessao",
        data: { id: id },
        success: function(data) {
            $("#loadingSessao"+id).hide();
            getSessao(venda_id);
            $('#msgMain').html("<div class='alert alert-success'><strong>Sessão excluída com sucesso!</strong></div>");

            $("#msgMain").fadeOut(3000);


        },
        error: function(data) {
            $("#loadingSessao"+id).hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}

function saveSessao(id, venda_id)
{
    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $("#loadingSessao").show();

    var data = {
        id: id,
        t_data: $("#dataSessao").val(),
        t_note: $("#noteSessao").val(),
        valor: $("#valorPago").val()
    };

    $.ajax({
        type: "GET",
        url: "saveSessao",
        data: data,
        success: function(response) {

            $("#loadingSessao").hide();
            if(response==1){
                sessaoModalClose();
                getSessao(venda_id);
                $('#msgMain').html("<div class='alert alert-success'><strong>Sessão salva com sucesso!</strong></div>");
                $("#msgMain").fadeOut(3000);
            }


        },
        error: function(error) {
            $("#loadingSessao").show();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    error.responseJSON.message +
                    "</div>"
            );
        }
    });


}

function addSessao(id){
    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $("#loadingSessao").show();

    $.ajax({
        type: "GET",
        url: "addSessao",
        data: {id: id},
        success: function(response) {

            $("#loadingSessao").hide();
            getSessao(id);
            $('#msgMain').html("<div class='alert alert-success'><strong>Sessão criada com sucesso!</strong></div>");
            $("#msgMain").fadeOut(3000);
            sessaoModalClose();

        },
        error: function(error) {
            $("#loadingSessao").show();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    error.responseJSON.message +
                    "</div>"
            );
        }
    });
}

function depsesaModal(){
    $("#loading").show();

    $.ajax({
        type: "GET",
        url: "despesaModal",
        success: function(data) {
            $("#loading").hide();
            $("#modal").html(data);
            $("#modalDespesa").modal("show");
            getDespesa();

        },
        error: function(data) {
            $("#loading").hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}


function confirmaremove(functionnome, nome=null, id=null, idsecundario=null) {

    $("#msgMain").html("<div class='alert alert-warning'>Você tem certeza que deseja excluir " +
        nome +
            " ?&nbsp &nbsp <button id='sim'>Sim</button><button id='nao'>Não</button></div>"
    );
    $("#msgMain").show();

    $("#sim").click(function() {
        $("#msgMain").html("");
        window[functionnome](nome, id, idsecundario);

    });

    $("#nao").click(function() {
        $("#msgMain").html("");
        return;
    });

    return;

}

function deleteCliente(nome=null, id=null, idsecundario=null){

    $("#loading").show();

    id=$("#cliente :selected").val();
    clienteNome=$("#cliente :selected").text();

    var data = {
        id: id
    };

    $.ajax({
        type: "GET",
        url: "deleteCliente",
        data: data,
        success: function(response) {
            $("#loading").hide();
            $("#cliente option[value='"+id+"']").remove();
            $('#msgMain').html("<div class='alert alert-success'><strong>Cliente "+clienteNome+" excluido com sucesso!</strong></div>");
            $("#msgMain").fadeOut(3000);
            $("#table").html("");
            $("#tableSessao").html("");
        },
        error: function(data) {
            $("#loading").hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}


$("#cliente").change(function() {
    getVenda();
});

function saveDespesa(){

    if($('#dataDesp').val()=='' || $('#categoria').val()=='' || $('#despesa').val()=='' || $('#valorDesp').val()==''){
        $('#msg').html("<div class='alert alert-warning'><strong>Todos os campos devem ser preenchidos!</strong></div>");
        return;
    }
    $("#loadingDespesa").show();

    if($('#idDesp').val() != ''){
        var data = {
            id: $('#idDesp').val(),
            data: $('#dataDesp').val(),
            categoria: $("#categoria").children(":selected").text(),
            despesa: $('#despesa').val(),
            valor: $('#valorDesp').val()
        };
    }else{
        var data = {
            data: $('#dataDesp').val(),
            categoria: $("#categoria").children(":selected").text(),
            despesa: $('#despesa').val(),
            valor: $('#valorDesp').val()
        };
    }

    $.ajax({
        type: "GET",
        url: "saveDespesa",
        data: data,
        success: function(response) {
            $("#loadingDespesa").hide();
            $('#msg').html("<div class='alert alert-success'><strong>Despesa cadastarda com sucesso!</strong></div>");
            $("#msg").fadeOut(3000);
            getDespesa();
            $('#despesa').val('');
            $('#valorDesp').val('');
            $('#dataDesp').val('');
            $('#categoria').val('');
            $('#idDesp').val('');
            $("#categoria option[value=Transporte]").attr("selected", "selected");


        },
        error: function(data) {
            $("#loadingDespesa").hide();
            $("#msg").html(
                "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                    data.responseJSON.message +
                    "</div>"
            );
        }
    });
}


function getDespesa()
{

    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });


    $.ajax("despesa/show", {
        method: "GET",
        cache: false,
        beforeSend: function() {
        },
        success: function(response) {


            $("#tableDespesa").html(response);

            $('#datatable-despesa').DataTable({
                dom: 'Bfrtip',
                buttons: [],
                paging: true,
                ordering: false,
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

        },
        error: function(erro) {
            $("#loadingVenda"+id).hide();
            alert(JSON.stringify(erro, true));

        }
    });
}

function excluirDespesa(id){

    $("#msg").html("<div class='alert alert-warning'>Você tem certeza que deseja excluir despesa? &nbsp &nbsp <button id='sim'>Sim</button><button id='nao'>Não</button></div>");
    $("#msg").show();

    $("#sim").click(function() {
        $("#msg").html("");
        $("#loadingDespesa").show();
        var data = {
            id: id
        };

        $.ajax({
            type: "GET",
            url: "deleteDespesa",
            data: data,
            success: function(response) {
                $("#loadingDespesa").hide();
                $('#msg').html("<div class='alert alert-success'><strong>Despesa excluída com sucesso!</strong></div>");
                $("#msg").fadeOut(3000);
                getDespesa();
                $('#despesa').val('');
                $('#valorDesp').val('');


            },
            error: function(data) {
                $("#loadingDespesa").hide();
                $("#msg").html(
                    "<div class='alert alert-danger'><strong>Erro!</strong> Ocorreu um erro. " +
                        data.responseJSON.message +
                        "</div>"
                );
            }
        });

    });

    $("#nao").click(function() {
        $("#msgMain").html("");
        return;
    });


}

function dashboard()
{

    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });


    $.ajax("dashboard", {
        method: "GET",
        cache: false,

        beforeSend: function() {
            $("#tableSessao").html("");
            $("#table").html("");
            $("#loading").show();
        },
        success: function(response) {
            $("#loading").hide();
            $("#table").html(response);

            $('#datatable-dashboard').DataTable({
                dom: 'Bfrtip',
                buttons: [],
                paging: true,
                ordering: false,
                pageLength: 50,
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

        },
        error: function(erro) {
            $("#loading").hide();
            alert(JSON.stringify(erro, true));

        }
    });
}

function editarDespesa(id, data, categoria, despesa, valor){
    $("#idDesp").val(id);
    $("#dataDesp").val(data);
    $("#categoria option[value="+categoria+"]").attr("selected", "selected");
    $("#despesa").val(despesa);
    $("#valorDesp").val(valor);

}




function atendimento()
{

    $.ajaxSetup({
        headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });


    $.ajax("atendimento", {
        method: "GET",
        cache: false,

        beforeSend: function() {
            $("#tableSessao").html("");
            $("#table").html("");
            $("#loading").show();
        },
        success: function(response) {
            $("#loading").hide();
            $("#table").html(response);

            $('#datatable-dashboard').DataTable({
                dom: 'Bfrtip',
                buttons: [],
                paging: true,
                ordering: false,
                pageLength: 50,
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

        },
        error: function(erro) {
            $("#loading").hide();
            alert(JSON.stringify(erro, true));

        }
    });
}
