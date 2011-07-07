Ext.define("kateglo.models.Meaning", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'entryId', mapping:'entryId'},
        {name:'entry', mapping:'entry'},
        {name:'definition', mapping:'definition'},
        {name:'definitions', mapping:'definitions'}
    ],
    idProperty: 'id'
});