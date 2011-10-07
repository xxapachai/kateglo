Ext.define('kateglo.modules.wordoftheday.Panel', {
	extend: 'Ext.panel.Panel',
	title: 'Kata hari ini',
	id: 'kateglo.modules.wordoftheday.Panel',
	layout: 'border',
    closable: true,
	initComponent: function() {
		Ext.apply(this, {
			items: [
				new kateglo.modules.wordoftheday.Form(),
				new kateglo.modules.wordoftheday.Grid({
					recordResult: this.recordResult
				})
			]
		});
		this.callParent(arguments);
	}

});
