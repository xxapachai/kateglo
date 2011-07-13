Ext.define("kateglo.models.Foreign", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'version', mapping: 'version'},
        {name:'foreign', mapping: 'foreign'},
        {name:'language', mapping: 'language'}
    ],
    idProperty: 'id'
});