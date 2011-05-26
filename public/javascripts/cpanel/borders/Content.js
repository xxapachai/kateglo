Ext.define('kateglo.borders.Content', {
    extend: 'Ext.tab.Panel',

    initComponent: function() {
        Ext.apply(this, {
            region: 'center',
            border: false,
            activeTab: 0,
            defaults: {
                border: false
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