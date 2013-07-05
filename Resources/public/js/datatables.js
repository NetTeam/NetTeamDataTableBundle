$(document).ready(function() {
    $('.nt_datatable-reset-button').click(function(e) {
        form = $(this).closest('form');
            form.find(':input').each(function() {
                var val = $(this).data("filter-default");
                switch(this.type) {
                    case 'password':
                    case 'select-one':
                    case 'text':
                    case 'textarea':
                        $(this).val(val);
                        break;
                    case 'select-multiple':
                        $(this).val(val);
                        break;
                    case 'checkbox':
                    case 'radio':
                        this.checked = false;
                }
            });
        form.find(".chzn-select").trigger("liszt:updated");
        e.preventDefault();
        $('.nt_datatable-apply-button', form).click();
    });
});
