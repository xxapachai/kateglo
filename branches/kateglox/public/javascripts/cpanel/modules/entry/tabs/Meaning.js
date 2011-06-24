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
            fn: function(component){
                var meaningTabs = null;
                if(component.recordResult.meanings.length > 0){
                    meaningTabs = new Ext.tab.Panel();
                    component.add(meaningTabs);
                }
                for(var i=0; i < component.recordResult.meanings.length; i++){
                   meaningTabs.add({
                        title: 'Arti ' + (i+1),
                        html: 'Arti ' + (i+1)
                   })
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
