Ext.define('kateglo.borders.Header', {
    extend: 'Ext.panel.Panel',

    initComponent: function() {
        Ext.apply(this, {
            region: 'north',
            height: 30,
            border: false,
            padding: 0,
            margin: 0,
            html: 'north'
        });
        this.callParent(arguments);
    }
});
