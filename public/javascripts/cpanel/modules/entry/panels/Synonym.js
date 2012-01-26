Ext.define('kateglo.modules.entry.panels.Synonym', {
    extend:'Ext.panel.Panel',
    title:'Synonym',
    layout:'border',
    initComponent:function () {
        Ext.apply(this, {
            items:[
                new kateglo.modules.entry.forms.MeaningComboBox({
                    store:new Ext.data.Store({
                        model:'kateglo.models.Meaning',
                        pageSize:10000000,
                        proxy:{
                            type:'rest',
                            url:'/cpanel/cari/sinonim/' + this.recordResult.id,
                            noCache:false,
                            headers:{
                                Accept:'application/json'
                            },
                            reader:{
                                type:'json',
                                totalProperty:'numFound'
                            }
                        }
                    }),
                    selectCallback:function (field, value) {
                        console.log(field.getStore());
                        var gridStore = field.up().up().getComponent(1).getStore();
                        if (gridStore.getById(value[0].getId()) == null) {
                            gridStore.add(value[0]);
                            field.getStore().removeAt(field.getStore().indexOfId(value[0].getId));
                        }
                        field.selectText(0, field.value.length);
                    }
                }),
                new kateglo.modules.entry.grids.Relation({
                    recordResult:this.recordResult.synonyms
                })
            ]
        });
        this.callParent(arguments);
    }

});
