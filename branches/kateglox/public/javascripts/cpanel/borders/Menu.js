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
            width: 270,
            items:[
                new kateglo.menus.Search(),
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