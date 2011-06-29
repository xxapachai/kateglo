Ext.define('kateglo.modules.entry.forms.Entry', {
    extend: 'Ext.form.Panel',
    title: 'Entri',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
        }
    ],
    fieldDefaults: {
        margin: '20 10 10 20',
        labelAlign: 'top'
    },
    listeners: {
        beforerender: function(component) {
            //alert(component.recordResult.entry);
        }
    },
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.field.Text({
                    name: 'entry',
                    fieldLabel: 'Entri',
                    labelWidth: 30,
                    anchor: '100%',
                    value: this.recordResult.entry
                })
            ]
        });
        this.callParent(arguments);
    }

});
