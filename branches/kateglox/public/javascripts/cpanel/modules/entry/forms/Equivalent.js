Ext.define('kateglo.modules.entry.forms.Equivalent', {
    extend: 'Ext.form.Panel',
    region: 'north',
    split: true,
    collapsible: true,
    border: false,
    hideCollapseTool: true,
    listeners: {
    },
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.field.ComboBox({
                    margin: '20 10 5 20',
                    labelAlign: 'top',
                    name: 'Foreign',
                    displayField: 'foreign',
                    valueField: 'id',
                    fieldLabel: 'Equivalent',
                    anchor: '100%',
                    hideTrigger: true,
                    forceSelection: true,
                    listConfig: {
                        getInnerTpl: function() {
                            return '<div>' +
                                    '<i>[{language.language}]</i>' +
                                    ' {foreign}' +
                                    '</div>';
                        }
                    },
                    store: new kateglo.stores.Foreign()
                }),
                new kateglo.utils.BoxSelect({
                    margin: '5 10 20 20',
                    labelAlign: 'top',
                    name: 'disciplines',
                    displayField: 'name',
                    valueField: 'id',
                    queryMode: 'local',
                    fieldLabel: 'Disiplin',
                    anchor: '100%',
                    hideTrigger: true,
                    store: new kateglo.stores.Discipline(),
                    listeners:{
                        beforerender: function(component) {
                            component.store.load();
                        }
                    }
                })
            ]
        });
        this.callParent(arguments);
    }

});
