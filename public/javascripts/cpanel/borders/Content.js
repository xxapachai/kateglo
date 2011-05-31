Ext.define('kateglo.borders.Content', {
    extend: 'Ext.tab.Panel',
    id: 'kateglo.borders.Content',
    initComponent: function() {
        Ext.apply(this, {
            region: 'center',
            activeTab: 0,
            margins: '0 5 5 0',
            split: true,
            defaults: {
            },
            items: [
                new Ext.Component({
                    title: 'Beranda',
                    html : 'Beranda Content',
                    iconCls: 'cpanel_sprite cpanel_house',
                    closable: true
                })
            ]
        });
        this.callParent(arguments);
    }
});