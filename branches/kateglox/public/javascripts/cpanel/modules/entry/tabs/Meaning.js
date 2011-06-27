Ext.define('kateglo.modules.entry.tabs.Meaning', {
    extend: 'Ext.panel.Panel',
    title: 'Arti',
    layout: 'fit',
    closable: false,
    border: false,
    iconCls: 'cpanel_sprite cpanel_application_form',
    tbar: [
        {
            text: 'New Meaning',
            iconCls: 'cpanel_sprite cpanel_add'
        }
    ],
    listeners:{
        beforerender : {
            fn: function(component) {
                var meaningTabs = null;
                if (component.recordResult.meanings.length > 0) {
                    meaningTabs = new Ext.tab.Panel({
                        border: false
                    });
                    component.add(meaningTabs);
                }
                for (var i = 0; i < component.recordResult.meanings.length; i++) {
                    var tab = new Ext.panel.Panel({
                        title: 'Arti ' + (i + 1),
                        border: false,
                        layout: 'border',
                        tbar: [
                            '->',
                            {
                                text: 'Delete Meaning',
                                iconCls: 'cpanel_sprite cpanel_delete'
                            }
                        ],
                        items: [new Ext.tab.Panel({
                            region: 'center',
                            items: [
                            new kateglo.modules.entry.tabs.Type(),
                            {
                                title: 'Definisi'
                            },
                            {
                                title: 'Thesaurus'
                            },
                            {
                                title: 'Syllabel'
                            },
                            {
                                title: 'Salah Eja'
                            }]})
                        ]
                    });
                    meaningTabs.add(tab);
                    if(i == 0){
                        meaningTabs.setActiveTab(tab);
                    }
                }
                component.doLayout();
            }
        }
    },
    initComponent: function() {
        Ext.apply(this, {

        });
        this.callParent(arguments);
    }

});
