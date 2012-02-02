TableTools.BUTTONS.download = {
    "sAction": "text",
    "sFieldBoundary": "",
    "sFieldSeperator": "\t",
    "sNewLine": "<br>",
    "sToolTip": "",
    "sButtonClass": "",
    "sButtonClassHover": "",
    "sButtonText": "Download",
    "mColumns": "all",
    "bHeader": true,
    "bFooter": true,
    "sDiv": "",
    "fnMouseover": null,
    "fnMouseout": null,
    "fnClick": function( nButton, oConfig ) {
        var alias = $(this.dom.table).data('alias');
        var oParams = this.s.dt.oApi._fnAjaxParameters( this.s.dt );
        var filterParams = NtFilters.getFiltersParams(alias);
        $.merge(oParams, filterParams);

        var iframe = document.createElement('iframe');
        iframe.style.height = "0px";
        iframe.style.width = "0px";
        //iframe.src = oConfig.sUrl+"?"+$.param(oParams);
        iframe.src = oConfig.sUrl+"&"+$.param(oParams);
        document.body.appendChild( iframe );
    },
    "fnSelect": null,
    "fnComplete": null,
    "fnInit": null
};