NtFilters = {

    getFiltersParams: function(alias){
        var fields = $('#datatables_div_'+alias+' form.filters').serializeArray();
        var data = [];
        $(fields).each(function (i, field) {
            data.push({
                'name': field.name,
                'value': field.value
            });
        });
        return data;

    }
}
