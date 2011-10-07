Ext.define("kateglo.modules.wordoftheday.Stores", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Entry',
    pageSize: 1000,
    proxy: {
        type: 'rest',
        url : '/cpanel/entri',
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            root: 'hits'
        }
    }
});