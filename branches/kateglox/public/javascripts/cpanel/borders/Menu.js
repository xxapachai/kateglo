Ext.define('kateglo.borders.Menu', {
	extend: 'Ext.panel.Panel',
	initComponent: function() {
		Ext.apply(this, {
			region: 'west',
			layout: 'accordion',
			title: 'Control Panel',
			margins: '0 0 5 5',
			split: true,
			multi: false,
			animate: false,
			activeOnTop: true,
			collapsible: true,
			hideCollapseTool: true,
			width: 300,
			items:[
				new Ext.tab.Panel({
					border: false,
					plain: true,
					title: 'Basis Data',
					iconCls: 'cpanel_sprite cpanel_database_gear',
					items: [
						new kateglo.menus.SearchEntry(),
						new kateglo.menus.SearchEquivalent(),
						new kateglo.menus.SearchSource()
					]
				}),
				{
					layout: 'fit',
					title: 'Konten',
					border: false,
					items: new kateglo.menus.Content
				},
				{
					title: 'Sistem',
					html: 'Sistem'
				}
			]
		});
		this.callParent(arguments);
	}
});