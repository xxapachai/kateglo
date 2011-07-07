Ext.define("kateglo.stores.Meaning", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Meaning',
    pageSize: 10000000,
    proxy: {
        type: 'rest',
        url : '/kamus/arti',
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            totalProperty: 'numFound'
        }
    }
});