Ext.define('kateglo.menus.Search', {
    extend: 'Ext.panel.Panel',

    layout: 'hbox',
    initComponent: function() {
        Ext.apply(this, {
            title: 'Entri',
            iconCls: 'cpanel_sprite cpanel_application_form_magnify',
            tbar: [ '->',
                {
                    text: 'Entri Baru',
                    iconCls: 'cpanel_sprite cpanel_application_form_add'
                },
                {
                    text: 'Daftar Entri',
                    iconCls: 'cpanel_sprite cpanel_application_view_detail'
                }
            ],
            defaults: {
                border: false,
                margin: 4
            },
            items:[
                {
                    dockedItems: [
                        {
                            dock: 'top',
                            xtype: 'searchfield',
                            emptyText: 'Ketik yang dicari, kemudian tekan enter',
                            store : this.store
                        }
                    ]
                },
                new Ext.tree.Panel({
                    rootVisible: false,
                    lines: false,
                    store: this.store
                })
            ]
        });
        this.callParent(arguments);
    },

    store: new kateglo.stores.Entry()
});
