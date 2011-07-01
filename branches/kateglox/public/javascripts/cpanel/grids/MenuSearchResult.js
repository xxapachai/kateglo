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
                var box = Ext.MessageBox.wait('Loading Entry Object.', 'Please wait!');
                Ext.Ajax.defaultHeaders = {
                    'Accept': 'application/json'
                };
                Ext.Ajax.request({
                    url: '/entri/' + record.get('text'),
                    timeout: 60000,
                    success: function(response, request) {
                        responseObj = Ext.JSON.decode(response.responseText);
                        var entryTab = new kateglo.tabs.Entry({
                            id: 'kateglo.tabs.Entry.' + responseObj.id,
                            title: 'Entri - ' + responseObj.entry,
                            recordResult: responseObj
                        });
                        contentContainer.add(entryTab);
                        entryTab.show();
                        box.hide();
                    },
                    failure: function(response, request) {
                        Ext.MessageBox.alert('Failed', 'Ajax request Error!!');
                    }
                });

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
