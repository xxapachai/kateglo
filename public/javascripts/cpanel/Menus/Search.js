Ext.define('kateglo.menus.Search', {
    extend: 'Ext.panel.Panel',
    layout: 'border',
    initComponent: function() {
        Ext.apply(this, {
            title: 'Entri',
            iconCls: 'cpanel_sprite cpanel_application_form_magnify',
            tbar: [
                {
                    text: 'Entri Baru',
                    iconCls: 'cpanel_sprite cpanel_application_form_add'
                },
                {
                    text: 'Daftar Entri',
                    iconCls: 'cpanel_sprite cpanel_application_view_detail'
                }
            ],
            items:[
                new kateglo.utils.SearchField({
                    region: 'north',
                    emptyText: 'Ketik yang dicari, kemudian tekan enter',
                    store : this.store
                }),
                new Ext.grid.Panel({
                    region: 'center',
                    border: false,
                    forceFit: true,
                    hideHeaders: true,
                    columns:[
                        {dataIndex: 'text'}
                    ],
                    viewConfig: {
                        emptyText: '<div style="text-align: center;"><i>No Records Found</i></div>'
                    },
                    store: this.store
                })
            ]
        });
        this.callParent(arguments);
    },

    store: new kateglo.stores.Entry()
});
