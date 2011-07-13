Ext.define('kateglo.modules.entry.panels.Equivalent', {
    extend: 'Ext.panel.Panel',
    title: 'Equivalent',
    layout: 'border',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
        },
        '->',
        {
            text: 'Reset',
            iconCls: 'cpanel_sprite cpanel_arrow_undo'
        }
    ],
    initComponent: function() {
        Ext.apply(this, {
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
