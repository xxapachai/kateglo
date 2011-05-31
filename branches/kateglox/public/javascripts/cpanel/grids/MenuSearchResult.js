Ext.define('kateglo.grids.MenuSearchResult', {
    extend: 'Ext.grid.Panel',
    border: false,
    forceFit: true,
    hideHeaders: true,
    columns:[
        {
            dataIndex: 'text',
            iconCls:'cpanel_sprite cpanel_application_form'
        }
    ],
    viewConfig: {
        emptyText: '<div style="margin: 10px; text-align: center; color: #888;"><i>Penelusuran Anda tidak cocok dengan dokumen apa pun. Pastikan semua kata dieja dengan benar.</i></div>'
    },
    listeners:{
        itemdblclick : function(me, record, item, index, event) {
            var contentContainer = Ext.getCmp('kateglo.borders.Content');
            if (Ext.getCmp('kateglo.tabs.Entry.' + record.getId()) == undefined) {
                var entryTab = new kateglo.tabs.Entry({
                    id: 'kateglo.tabs.Entry.' + record.getId(),
                    title: 'Entri - ' + record.get('text'),
                    recordResult: record
                });
                contentContainer.add(entryTab);
                contentContainer.doLayout();
                entryTab.show();
            }
            else {
                Ext.getCmp('kateglo.tabs.Entry.' + record.getId()).show();
            }
        }
    },
    initComponent: function() {
        Ext.apply(this, {

        });
        this.callParent(arguments);
    }

})
        ;
