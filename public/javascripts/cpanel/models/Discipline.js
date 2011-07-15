Ext.define("kateglo.models.Discipline", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'name', mapping: 'discipline'}
    ],
    idProperty: 'id'
});