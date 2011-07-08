Ext.define('kateglo.modules.entry.forms.Synonym', {
    extend: 'Ext.form.Panel',
    title: 'Synonym',
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
                    recordResult: this.recordResult.synonyms
                })
            ]
        });
        this.callParent(arguments);
    }

});
