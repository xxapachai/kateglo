Ext.define('kateglo.modules.entry.forms.Entry', {
    extend: 'Ext.form.Panel',
    title: 'Entri',
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
    listeners: {
        beforerender: function(component) {
            //alert(component.recordResult.entry);
        }
    },
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.field.Text({
                    margin: '20 10 10 20',
                    name: 'entry',
                    anchor: '100%',
                    value: this.recordResult.entry
                })
            ]
        });
        this.callParent(arguments);
    }

});
