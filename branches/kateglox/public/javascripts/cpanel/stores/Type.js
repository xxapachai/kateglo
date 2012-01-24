Ext.define("kateglo.stores.Type", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Type',
    proxy: {
        type: 'rest',
        url : '/cpanel/static/types',
        noCache: false,
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            model: 'kateglo.models.Type'
        }
    }
});
