Ext.define('kateglo.menus.Content', {
	extend: 'Ext.tree.Panel',
	border: false,
	rootVisible: false,
	root: {
		text: 'Root',
		expanded: true,
		children: [
			{
				id: 'wordOfTheDay',
				text: 'Entri hari ini',
				leaf: true
			},
			{
				text: 'Child 2',
				leaf: true
			}
		]
	},
	listeners: {
		itemdblclick: function(view, record, item, index, event) {
			if (record.raw.id == 'wordOfTheDay') {
				var contentContainer = Ext.getCmp('kateglo.borders.Content');
				if (Ext.getCmp('kateglo.modules.wordoftheday.Panel') == undefined) {
					var wordOfTheDayTab = new kateglo.modules.wordoftheday.Panel();
					contentContainer.add(wordOfTheDayTab);
					wordOfTheDayTab.show();
				} else {
					Ext.getCmp('kateglo.tabs.NewEntry').show();
				}
			}
		}
	},
	initComponent: function() {
		Ext.apply(this, {

		});
		this.callParent(arguments);
	}
});