Ext.define('kateglo.borders.Menu', {
    extend: 'Ext.panel.Panel',
    initComponent: function() {
        Ext.apply(this, {
            region: 'west',
            layout: 'accordion',
            title: 'Control Panel',
            margins: '0 0 5 5',
            split: true,
            multi: false,
            animate: false,
            activeOnTop: true,
            collapsible: true,
            hideCollapseTool: true,
            width: 300,
            items:[
                new Ext.tab.Panel({
                    border: false,
                    plain: true,
                    title: 'Basis Data',
                    iconCls: 'cpanel_sprite cpanel_database_gear',
                    items: [
                        new kateglo.menus.SearchEntry(),
                        new kateglo.menus.SearchEquivalent(),
                        new kateglo.menus.SearchSource()
                    ]
                }),
                {
                    title: 'Panel 2',
                    id: 'panel2',
                    html: 'Content'
                },
                {
                    title: 'Panel 3',
                    id: 'panel3',
                    html: 'Content'
                }
            ]
        });
        this.callParent(arguments);
    }
});