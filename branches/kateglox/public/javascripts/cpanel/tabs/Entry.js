Ext.define('kateglo.tabs.Entry', {
    extend: 'Ext.form.Panel',
    closable: true,
    border: false,
    fieldDefaults: {
        labelAlign: 'top',
        margin: '20 10 10 20'
    },
    iconCls: 'cpanel_sprite cpanel_application_form',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
        },
            '->',
        {
            text: 'Delete',
            iconCls: 'cpanel_sprite cpanel_delete'
        }
    ],
    bbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
        },
            '->',
        {
            text: 'Delete',
            iconCls: 'cpanel_sprite cpanel_delete'
        }
    ],
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.field.Text({
                    name: 'entry',
                    fieldLabel: 'Entri',
                    value: this.recordResult.get('text')
                })
            ]
        });
        this.callParent(arguments);
    }

});
