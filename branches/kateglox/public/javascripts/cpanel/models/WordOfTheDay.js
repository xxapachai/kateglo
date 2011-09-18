Ext.define("kateglo.models.WordOfTheDay", {
    extend: 'Ext.data.Model',
    fields: [
        {name:'id', mapping: 'id'},
        {name:'version', mapping: 'version'},
	    {name:'date', mapping: 'date', type: 'date', dateFormat: 'Y-m-d H:i:s'},
	    {name:'entry_id', mapping: 'entry_id'},
	    {name:'entry', mapping: 'entry_name'},
	    {name:'definition', mapping: 'definition'}
    ],
    idProperty: 'id'
});