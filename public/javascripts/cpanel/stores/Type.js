Ext.define("kateglo.stores.Type", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Type',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url : '/static',
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            root: 'type'
        }
    }
});
