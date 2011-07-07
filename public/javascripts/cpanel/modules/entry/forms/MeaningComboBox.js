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
                    store: new kateglo.stores.Meaning(),
                    listConfig: {
                        emptyText: 'No matching Entries found.',
                        getInnerTpl: function() {
                            return '<div>' +
                                    '<b>{entry}</b>' +
                                    '<ul class="rowexpander">' +
                                    '<tpl for="definitions"><li>{.}</li></tpl>' +
                                    '</ul>' +
                                    '</div>';
                        }
                    }
                })
            ]
        });
        this.callParent(arguments);
    }

});
