Ext.define("kateglo.stores.Entry", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Entry',
    pageSize: 10000000,
    data: {'hits' : []},
    proxy: {
        type: 'rest',
        url : '/kamus',
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            root: 'hits',
            totalProperty: 'numFound'
        }
    }
});
