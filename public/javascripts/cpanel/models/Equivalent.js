Ext.define("kateglo.models.Equivalent", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'foreignId', mapping:'foreignId'},
        {name:'foreign', mapping:'foreign'},
        {name:'languageId', mapping:'languageId'},
        {name:'language', mapping:'language'},
        {name:'disciplines', mapping:'disciplines'}
    ],
    idProperty: 'id'
});