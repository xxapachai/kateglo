Ext.define('kateglo.menus.SearchSource', {
    extend: 'Ext.panel.Panel',
    layout: 'border',
    border: false,
    initComponent: function() {
        Ext.apply(this, {
            title: 'Sumber',
            tbar: [ '->',
                {
                    iconCls: 'cpanel_sprite cpanel_cog',
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
                new kateglo.menus.SearchField({
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
                    errorResultText:this.errorResultText,
                    showResultText: new kateglo.grids.MenuSearchResult({
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
    errorResultText:{
        html: '<div style="margin: 10px; text-align: center; color: #888;"><i>Error pada basis data. Pastikan semua kata dieja dengan benar atau coba beberapa saat lagi.</i></div>'
    },
    store: new kateglo.stores.Entry()


});
