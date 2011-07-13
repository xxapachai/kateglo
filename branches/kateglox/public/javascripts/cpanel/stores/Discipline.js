Ext.define("kateglo.stores.Discipline", {
    extend: 'Ext.data.Store',
    model: 'kateglo.models.Static',
    proxy: {
        type: 'rest',
        url : '/cpanel/static',
        noCache: false,
        headers: {
            Accept: 'application/json'
        },
        reader: {
            type: 'json',
            root: 'discipline'
        }
    }
});
