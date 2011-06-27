Ext.define('kateglo.tabs.Entry', {
    extend: 'Ext.panel.Panel',
    closable: true,
    border: false,
    layout: 'border',
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
                new Ext.form.Panel({
                    border: false,
                    region: 'north',
                    fieldDefaults: {
                        margin: '20 10 10 20'
                    }, items: [new Ext.form.field.Text({
                        name: 'entry',
                        fieldLabel: 'Entri',
                        labelWidth: 30,
                        width: 500,
                        value: this.recordResult.entry
                    })]
                }),
                new Ext.panel.Panel({
                    region: 'center',
                    layout: 'border',
                    border: false,
                    items: [new Ext.tab.Panel({
                        region: 'center',
                        border: false,
                        items:[
                            new kateglo.modules.entry.tabs.Meaning({
                                recordResult: this.recordResult
                            }),
                            {
                                title: 'Padanan',
                                html : 'Padanan'
                            },
                            {
                                title: 'Sumber',
                                html : 'Sumber'
                            }
                        ]})]
                })
            ]
        });
        this.callParent(arguments);
    }

});
