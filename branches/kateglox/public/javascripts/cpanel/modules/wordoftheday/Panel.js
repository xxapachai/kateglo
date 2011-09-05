Ext.define('kateglo.modules.wordoftheday.Panel', {
    extend: 'Ext.panel.Panel',
    title: 'Kata hari ini',
	id: 'kateglo.modules.wordoftheday.Panel',
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
                    recordResult: {}
                })
            ]
        });
        this.callParent(arguments);
    }

});
