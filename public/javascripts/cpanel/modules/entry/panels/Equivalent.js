Ext.define('kateglo.modules.entry.panels.Equivalent', {
    extend: 'Ext.panel.Panel',
    title: 'Equivalent',
    layout: 'border',
    initComponent: function() {
        Ext.apply(this, {
            tbar: [
                {
                    text: 'Add',
                    iconCls: 'cpanel_sprite cpanel_add'
                },
                '->',
                {
                    text: 'Reset',
                    iconCls: 'cpanel_sprite cpanel_arrow_undo',
                    scope: this,
                    handler: function() {
                        this.down('form').getForm().reset();
                    }

                }
            ],
            items: [
                new kateglo.modules.entry.grids.Equivalent({
                    recordResult: this.recordResult
                }),new kateglo.modules.entry.forms.Equivalent({
                    recordResult: this.recordResult
                })
            ]
        });
        this.callParent(arguments);
    }

});
