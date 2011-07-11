Ext.define('kateglo.modules.entry.forms.Equivalent', {
    extend: 'Ext.form.Panel',
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
                    recordResult: this.recordResult.antonyms
                })
            ]
        });
        this.callParent(arguments);
    }

});
