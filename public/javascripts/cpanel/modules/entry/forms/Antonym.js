Ext.define('kateglo.modules.entry.forms.Antonym', {
    extend: 'Ext.form.Panel',
    title: 'Antonym',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
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
                new Ext.form.field.ComboBox({
                    listeners: {
                        scope: this,
                        select: function(field, value){
                            window.console.log(field);
                            window.console.log(value);
                        }
                    },
                    margin: '20 10 10 20',
                    name: 'entry',
                    anchor: '100%',
                    store: new kateglo.stores.Entry()
                }),
                new Ext.grid.Panel({
                    margin: '20 10 10 20',
                    store: new Ext.data.Store({
                        model: 'kateglo.models.Type'
                    }),
                    anchor: '100%',
                    columns: [
                        {
                            text     : 'Company',
                            flex     : 1,
                            sortable : false,
                            dataIndex: 'company'
                        },
                        {
                            text     : 'Price',
                            width    : 75,
                            sortable : true,
                            renderer : 'usMoney',
                            dataIndex: 'price'
                        }
                    ]
                })
            ]
        });
        this.callParent(arguments);
    }

});
