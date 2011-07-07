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
    listeners: {
        beforerender: kateglo.modules.entry.utils.MeaningGridBeforeRender.beforeRender
    },
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new kateglo.modules.entry.forms.MeaningComboBox()
            ]
        });
        this.callParent(arguments);
    }

});
