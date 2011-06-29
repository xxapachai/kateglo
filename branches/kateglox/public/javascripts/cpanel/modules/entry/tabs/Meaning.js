Ext.define('kateglo.modules.entry.tabs.Meaning', {
    extend: 'Ext.panel.Panel',
    title: 'Arti',
    layout: 'fit',
    closable: false,
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
                    var tab = new Ext.form.Panel({
                        title: 'Arti ' + (i + 1),
                        border: false,
                        tbar: [
                            '->',
                            {
                                text: 'Delete Meaning',
                                iconCls: 'cpanel_sprite cpanel_delete'
                            }
                        ],
                        fieldDefaults: {
                            labelAlign: 'top',
                            margin: '20 10 10 20'
                        },
                        items: [new kateglo.modules.entry.forms.Type({
                            recordResult: component.recordResult.meanings[i].types
                        })]
                    });
                    meaningTabs.add(tab);
                    if (i == 0) {
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
