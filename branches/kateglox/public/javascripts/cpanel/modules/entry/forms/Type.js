Ext.define('kateglo.modules.entry.forms.Type', {
    extend: 'Ext.form.Panel',
    title: 'Bentuk Kata',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
        }
    ],
    listeners: {
        beforerender: function(component) {
            var store = new kateglo.stores.Type();
            var box = Ext.MessageBox.wait('Loading Types.', 'Please wait!');
            store.load({
                scope: this,
                callback: function(records, operation, success) {
                    var initVal = new Array();
                    for (var i = 0; i < component.recordResult.types.length; i++) {
                        initVal.push(component.recordResult.types[i].id)
                    }

                    var comboBox = new kateglo.utils.BoxSelect({
                        margin: '20 10 10 20',
                        name: 'type',
                        displayField: 'name',
                        valueField: 'id',
                        queryMode: 'local',
                        anchor: '100%',
                        hideTrigger: true,
                        store: store,
                        recordResult: component.recordResult.types,
                        value: initVal
                    });

                    component.add(comboBox);
                    box.hide();
                }

            });
        }
    },
    initComponent: function() {
        Ext.apply(this, {

        });
        this.callParent(arguments);
    }

});
