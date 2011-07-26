Ext.define('kateglo.tabs.NewEntry', {
    extend: 'Ext.panel.Panel',
    closable: true,
    id: 'kateglo.tabs.NewEntry',
    title: 'Entri Baru',
    border: false,
    layout: 'border',
    padding: '5 5 5 5',
    iconCls: 'cpanel_sprite cpanel_application_form',
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.panel.Panel({
                    id: 'entryContent',
                    region: 'center',
                    layout:'fit',
                    border: false,
                    items: new kateglo.forms.NewEntry()
                })
            ]
        });
        this.callParent(arguments);
    }

});
