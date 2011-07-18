Ext.define('kateglo.modules.entry.forms.MeaningComboBox', {
    extend: 'Ext.form.Panel',
    initComponent: function() {
        Ext.apply(this, {
            border: false,
            split: true,
            region: 'north',
            collapsible: true,
            hideCollapseTool: true,
            items: [
                new Ext.form.field.ComboBox({
                    margin: '20 10 10 20',
                    name: 'entry',
                    anchor: '100%',
                    hideTrigger: true,
                    store: new kateglo.stores.Meaning(),
                    emptyText: 'Ketik yang dicari, pilih salah satu dari hasil yang ditampilkan, kemudian tekan enter',
                    listConfig: {
                        getInnerTpl: function() {
                            return '<div>' +
                                    '<b>{entry}</b>' +
                                    '<ul class="rowexpander">' +
                                    '<tpl for="definitions"><li>{.}</li></tpl>' +
                                    '</ul>' +
                                    '</div>';
                        }
                    },
                    listeners:{
                        scope: this,
                        select: function(field, value) {
                            var store = field.up().up().getComponent(1).getStore();
                            if (store.getById(value[0].getId()) == null) {
                                store.add(value[0]);
                            }
                        }
                    }
                })
            ]
        });
        this.callParent(arguments);
    }

});
