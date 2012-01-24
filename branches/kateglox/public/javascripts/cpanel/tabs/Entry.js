Ext.define('kateglo.tabs.Entry', {
    extend: 'Ext.panel.Panel',
    closable: true,
    border: false,
    layout: 'border',
    padding: '5 5 5 5',
    iconCls: 'cpanel_sprite cpanel_application_form',
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new kateglo.modules.entry.tree.Explorer({
                    id: 'entryExplorer'+this.recordResult.id,
                    region: 'east',
                    layout: 'fit',
                    recordResult: this.recordResult
                }),
                new Ext.panel.Panel({
                    id: 'entryContent'+this.recordResult.id,
                    region: 'center',
                    layout:'fit',
                    border: false,
                    items: new kateglo.modules.entry.forms.Entry({
                        recordResult: this.recordResult
                    })
                })
            ]
        });
        this.callParent(arguments);
    }

});
