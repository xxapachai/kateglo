Ext.define('kateglo.modules.entry.tabs.Type', {
    extend: 'Ext.form.Panel',
    title: 'Bentuk',
    closable: false,
    border: false,
    iconCls: 'cpanel_sprite cpanel_application_form',
    initComponent: function() {
        Ext.apply(this, {
            fieldDefaults: {
                margin: '20 10 10 20'
            }, items: [new Ext.form.field.ComboBox({
                name: 'type',
                fieldLabel: 'Bentuk kata',
                labelWidth: 70,
                width: 300,
                displayField: 'name',
                queryMode: 'local',
                typeAhead: true,
                store: new Ext.data.Store({
                    model: 'kateglo.models.Type',
                    data : [
                        {id: '1', name: 'Spencer'},
                        {id: '2', name: 'Maintz'},
                        {id: '3', name: 'Conran'},
                        {id: '4', name: 'Avins'}
                    ]
                })
            }),{
                border: false,
                html: 'test'
            }]
        });
        this.callParent(arguments);
    }

});
