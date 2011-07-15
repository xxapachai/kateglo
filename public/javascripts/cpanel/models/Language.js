Ext.define("kateglo.models.Language", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'name', mapping: 'language'}
    ],
    idProperty: 'id'
});