$("document").ready(function() {
    $(".date-range-single")
        .daterangepicker({
            orientation: "auto top",
            autoUpdateInput: true,
            singleDatePicker: true,
            drops: "down",
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                applyLabel: "Aplicar",
                cancelLabel: "Limpar",
                clearLabel: "Limpar",
                fromLabel: "De",
                toLabel: "Até",
                customRangeLabel: "Customizado",
                weekLabel: "S",
                daysOfWeek: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                monthNames: [
                    "Janeiro",
                    "Fevereiro",
                    "Março",
                    "Abril",
                    "Maio",
                    "Junho",
                    "Julho",
                    "Agosto",
                    "Setembro",
                    "Outubro",
                    "Novembro",
                    "Dezembro"
                ]
            }
        })
        .on("apply.daterangepicker", function(ev, picker) {
            $(this).val(picker.startDate.format("DD/MM/YYYY"));
        })
        .on("cancel.daterangepicker", function() {
            $(this).val("");
        });
});
