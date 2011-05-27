Ext.define('kateglo.borders.Content', {
    extend: 'Ext.tab.Panel',

    initComponent: function() {
        Ext.apply(this, {
            region: 'center',
            activeTab: 0,
            margins: '0 5 5 0',
            split: true,
            defaults: {
            },
            items: [
                {
                    title: 'Tab 1',
                    html : 'A simple tab'
                },
                {
                    title: 'Tab 2',
                    html : 'Another one'
                }
            ]
        });
        this.callParent(arguments);
    }
});