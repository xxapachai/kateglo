Ext.define("kateglo.models.Type", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'name', mapping: 'type'}
    ],
    idProperty: 'id'
});
