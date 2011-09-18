Ext.define('kateglo.modules.wordoftheday.Grid', {
	extend: 'Ext.grid.Panel',
	region: 'center',
	split: true,
	border: false,
	anchor: '100%',
	plugins: [
		{
			ptype: 'rowexpander',
			rowBodyTpl : [
				'<p><b>Definisi:</b> ' +
						'<ul class="rowexpander">' +
						'<tpl for="definitions"><li>{.}</li></tpl>' +
						'</ul>' +
						'</p>'
			]
		}
	],
	constructor: function() {
		this.columns = [
			{
				text : 'Id',
				width: 40,
				sortable: true,
				align: 'right',
				dataIndex: 'id'
			},
			{
				text : 'Tanggal',
				width: 80,
				sortable: true,
				renderer : Ext.util.Format.dateRenderer('d-m-Y'),
				dataIndex: 'date'
			},
			{
				text : 'Entri',
				flex: 1,
				sortable: true,
				dataIndex: 'entry'
			},
			{
				text : 'Definisi',
				flex: 1,
				sortable: true,
				dataIndex: 'definition'
			},
			{
				xtype: 'actioncolumn',
				width: 25,
				items: [
					{
						iconCls   : 'cpanel_sprite cpanel_delete',
						text: 'Delete',
						scope: this,
						tooltip: 'Delete Entry',
						handler: function(grid, rowIndex, colIndex) {
							grid.store.removeAt(rowIndex);
						}
					}
				]
			}
		];
		this.callParent(arguments);
	},
	initComponent: function() {
		Ext.apply(this, {
			store: new Ext.data.Store({
				model: 'kateglo.models.WordOfTheDay'
			}),
			listeners: {
				beforerender: function(component) {
					var data = new Array();
					for (var i = 0; i < component.recordResult.length; i++) {
						var wotd = new Object();
						wotd.id = component.recordResult[i].id;
						wotd.date = component.recordResult[i].date.date;
						wotd.entryId = component.recordResult[i].entry.id;
						wotd.entry = component.recordResult[i].entry.entry;
						wotd.definition = component.recordResult[i].entry.meanings[0].definitions[0].definition;

						var definitions = new Array();
						for (var j = 0; j < component.recordResult[i].entry.meanings.length; j++) {
							for (var k = 0; k < component.recordResult[i].entry.meanings[j].definitions.length; k++) {
								definitions.push(component.recordResult[i].entry.meanings[j].definitions[k].definition);
							}
						}
						wotd.definitions = definitions;

						data.push(wotd);
					}
					component.getStore().loadData(data, false);
				}
			}
		});
		this.callParent(arguments);
	}
})