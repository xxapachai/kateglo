Ext.define("kateglo.stores.Entry", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Entry',
    pageSize: 1000,
    proxy: {
        type: 'rest',
        url : '/kamus',
        headers: {
            Accept: 'extjs/json'
        },
        reader: {
            type: 'json',
            root: 'hits'
        }
    }
});
