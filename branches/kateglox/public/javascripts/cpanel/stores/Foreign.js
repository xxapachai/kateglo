Ext.define("kateglo.stores.Foreign", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Foreign',
    pageSize: 10000000,
    proxy: {
        type: 'rest',
        url : '/padanan/asing',
        noCache: false,
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            totalProperty: 'numFound'
        }
    }
});
