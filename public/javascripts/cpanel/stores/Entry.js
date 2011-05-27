Ext.define("kateglo.stores.Entry", {
    extend: 'Ext.data.TreeStore',
    model: 'kateglo.models.Entry',
    pageSize: 500,
    root: {
        expanded: true
    },
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
