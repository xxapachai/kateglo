Ext.define('kateglo.modules.entry.panels.Relation', {
    extend: 'Ext.panel.Panel',
    title: 'Relation',
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
                new kateglo.modules.entry.forms.MeaningComboBox(),
                new kateglo.modules.entry.grids.Relation({
                    recordResult: this.recordResult.relations
                })
            ]
        });
        this.callParent(arguments);
    }

});
