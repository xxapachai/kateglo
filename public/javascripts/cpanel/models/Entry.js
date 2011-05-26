Ext.define("kateglo.models.Entry", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'entry', mapping: 'entry'}
    ],
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
