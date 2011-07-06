Ext.define("kateglo.models.Meaning", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id'},
        {name:'entryId'},
        {name:'entry'},
        {name:'definition'},
        {name:'definitions'}
    ],
    idProperty: 'id'
});