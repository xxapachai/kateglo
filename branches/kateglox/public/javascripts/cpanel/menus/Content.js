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
					var box = Ext.MessageBox.wait('Loading Entry Object.', 'Please wait!');
					Ext.Ajax.defaultHeaders = {
						'Accept': 'application/json'
					};
					Ext.Ajax.request({
						url: '/entri/hariini/list',
						timeout: 60000,
						success: function(response, request) {
							responseObj = Ext.JSON.decode(response.responseText);
							var wordOfTheDayTab = new kateglo.modules.wordoftheday.Panel({
								recordResult: responseObj
							});
							contentContainer.add(wordOfTheDayTab);
							wordOfTheDayTab.show();
							box.hide();
						},
						failure: function(response, request) {
							Ext.MessageBox.alert('Failed', 'Ajax request Error!!');
						}
					});
				} else {
					Ext.getCmp('kateglo.modules.wordoftheday.Panel').show();
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