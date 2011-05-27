Ext.define('kateglo.borders.Header', {
    extend: 'Ext.Component',

    initComponent: function() {
        Ext.apply(this, {
            region: 'north',
            height: 10,
            html: ''
        });
        this.callParent(arguments);
    }
});
