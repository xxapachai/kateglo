Ext.define('kateglo.modules.wordoftheday.Form', {
	extend: 'Ext.form.Panel',
	initComponent: function() {
		Ext.apply(this, {
			border: false,
			split: true,
			region: 'north',
			collapsible: true,
			hideCollapseTool: true,
			items: [
				new Ext.form.field.Date({
					margin: '20 10 10 20',
					name: 'date',
					emptyText: 'Tentukan tanggal',
					minValue: new Date(),
					format: 'd-m-Y',
					allowBlank: false,
					msgTarget: 'side'
				}),
				new Ext.form.field.ComboBox({
					margin: '20 10 10 20',
					name: 'entry',
					displayField: 'text',
					valueField: 'id',
					anchor: '100%',
					hideTrigger: true,
					forceSelection: true,
					store: new kateglo.modules.wordoftheday.Stores(),
					emptyText: 'Ketik yang dicari, pilih salah satu dari hasil yang ditampilkan, kemudian tekan enter',
					listConfig: {
						getInnerTpl: function() {
							return '<div>' +
									'<b>{text}</b>' +
									'<ul class="rowexpander">' +
									'<tpl for="definitions"><li>{.}</li></tpl>' +
									'</ul>' +
									'</div>';
						}
					},
					listeners:{
						scope: this,
						select: function(field, value) {
							var box = Ext.MessageBox.wait('Insert Word of the Day.', 'Please wait!');
							Ext.Ajax.defaultHeaders = {
								'Accept': 'application/json'
							};
							if (field.up().getForm().isValid()) {
								field.up().getForm().submit({
									url: '/cpanel/entri',
									submitEmptyText: false,
									waitMsg: 'Saving Data...',
									success: function(form, action) {
										console.log(action);
										responseObj = Ext.JSON.decode(response.responseText);
										box.hide();
										kateglo.utils.Message.msg('Success', 'Entry object saved');
										var store = field.up().up().getComponent(1).getStore();
										wotd = responseObj;
										wotd.entry = responseObj.text;
										wotd.date = responseObj.date.date;

										if (store.getById(value[0].getId()) == null) {
											store.add(wotd);
										}
										field.selectText(0, field.value.length);
									},
									failure: function(form, action) {
										box.hide();
									}
								});
							}
						}
					}
				})
			]
		});
		this.callParent(arguments);
	}

});
