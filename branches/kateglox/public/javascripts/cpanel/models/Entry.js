Ext.define("kateglo.models.Entry", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'text', mapping: 'entry'}
    ],
    idProperty: 'id'
});
