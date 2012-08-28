$(document).ready(function() {
    $('.nt_datatable-reset-button').click(function(e) {
        form = $(this).closest('form');
        form[0].reset();
        form.find(".chzn-select").trigger("liszt:updated");
        e.preventDefault();
        $('.nt_datatable-apply-button', form).click();
    });
});
