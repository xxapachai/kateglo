Ext.define('kateglo.menus.Search', {
    extend: 'Ext.panel.Panel',
    layout: 'border',
    initComponent: function() {
        Ext.apply(this, {
            title: 'Entri Basis Data',
            iconCls: 'cpanel_sprite cpanel_database_gear',
            tbar: [ '->',
                {
                    iconCls: 'cpanel_sprite cpanel_application_gear',
                    menu: {
                        style: {
                            overflow: 'visible'
                        },
                        items: [
                            {
                                text: 'Entri Baru',
                                iconCls: 'cpanel_sprite cpanel_application_form_add'
                            },
                            {
                                text: 'Daftar Entri',
                                iconCls: 'cpanel_sprite cpanel_application_view_detail'
                            }
                        ]
                    }
                }
            ],
            items:[
                new kateglo.utils.SearchField({
                    region: 'north',
                    emptyText: 'Ketik yang dicari, kemudian tekan enter',
                    store : this.store
                }),
                {
                    id: 'resultContainer',
                    scope: this,
                    region: 'center',
                    layout: 'fit',
                    border: false,
                    defaults: {
                        border: false
                    },
                    emptyResultText:this.emptyResultText,

                    showResultText: new Ext.grid.Panel({
                        scope: this,
                        border: false,
                        forceFit: true,
                        hideHeaders: true,
                        columns:[
                            {dataIndex: 'text'}
                        ],
                        viewConfig: {
                            emptyText: '<div style="margin: 10px; text-align: center; color: #888;"><i>Penelusuran Anda tidak cocok dengan dokumen apa pun.</i></div>'
                        },
                        store: this.store
                    }),
                    items: [
                        this.emptyResultText
                    ]

                }
            ]
        });
        this.callParent(arguments);
    },

    emptyResultText:{
        html: '<div style="margin: 10px; text-align: center; color: #888;"><i>Gunakan fungsi penelusuran entri diatas untuk menemukan entri yang dicari.</i></div>'
    },
    store: new kateglo.stores.Entry()


});
