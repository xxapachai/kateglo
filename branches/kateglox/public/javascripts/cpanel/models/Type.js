Ext.define("kateglo.models.Type", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'version', mapping: 'version'},
        {name:'name', mapping: 'type'},
        {name:'category', mapping: 'category'}
    ],
    idProperty: 'id'
});