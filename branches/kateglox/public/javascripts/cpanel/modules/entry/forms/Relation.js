Ext.define('kateglo.modules.entry.forms.Relation', {
    extend: 'Ext.form.Panel',
    title: 'Relation',
    layout: 'border',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
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
