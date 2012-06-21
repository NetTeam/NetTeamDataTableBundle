$(document).ready(function() {
    $('.nt_datatable-reset-button').click(function(e) {
        this.form.reset();
        e.preventDefault();
        $('.nt_datatable-apply-button').click();
    });
});
