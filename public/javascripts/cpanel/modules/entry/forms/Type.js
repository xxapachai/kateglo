Ext.define('kateglo.modules.entry.forms.Type', {
    extend: 'kateglo.utils.BoxSelect',
    name: 'type',
    fieldLabel: 'Bentuk kata',
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
    anchor: '100%',
    hideTrigger: true,
    store: new kateglo.stores.Type(),
    listeners:{
        beforerender : {
            fn: function(component) {
                var initVal = new Array();
                for(var i = 0; i < component.recordResult.length; i++){
                    initVal.push(component.recordResult[i].id)
                }
                component.setValue(initVal);
            }
        }
    },
    initComponent: function() {
        Ext.apply(this, {

        });
        this.callParent(arguments);
    }

});
