Ext.define('kateglo.modules.entry.grids.Equivalent', {
    extend: 'Ext.grid.Panel',
    region: 'center',
    split: true,
    anchor: '100%',
    border: false,
    constructor: function() {
        this.columns = [
            {
                text : 'Id',
                width: 50,
                sortable: true,
                align: 'right',
                dataIndex: 'id'
            },
            {
                text : 'Bahasa',
                flex: 1,
                sortable: true,
                dataIndex: 'language'
            },
            {
                text : 'Disiplin',
                flex: 1,
                sortable: false,
                dataIndex: 'disciplines'
            },
            {
                text : 'Padanan',
                flex: 1,
                sortable: true,
                dataIndex: 'foreign',
                editor: {
                    allowBlank: false
                }
            },
            {
                xtype: 'actioncolumn',
                width: 25,
                items: [
                    {
                        iconCls   : 'cpanel_sprite cpanel_delete',
                        text: 'Delete',
                        scope: this,
                        tooltip: 'Delete Entry',
                        handler: function(grid, rowIndex, colIndex) {
                            grid.store.removeAt(rowIndex);
                        }
                    }
                ]
            }
        ];
        this.callParent(arguments);
    },
    initComponent: function() {
        Ext.apply(this, {
            store: new Ext.data.Store({
                model: 'kateglo.models.Equivalent'
            }),
            listeners: {
                itemClick: function(view, record, item, index, event){
                    var parentComp = view.panel.up();
                    console.log();
                    console.log(record);
                    console.log(item);
                    console.log(index);
                    console.log(event);
                },
                beforerender: function(component) {
                    var data = new Array();
                    for (var i = 0; i < component.recordResult.length; i++) {
                        var equivalent = new Object();
                        equivalent.id = component.recordResult[i].id;
                        equivalent.foreignId = component.recordResult[i].foreign.id;
                        equivalent.foreign = component.recordResult[i].foreign.foreign;
                        equivalent.languageId = component.recordResult[i].foreign.language.id;
                        equivalent.language = component.recordResult[i].foreign.language.language;

                        var disciplines = new Array();
                        for (var j = 0; j < component.recordResult[i].disciplines.length; j++) {
                            disciplines.push(component.recordResult[i].disciplines[j].discipline);
                        }
                        equivalent.disciplines = disciplines;

                        data.push(equivalent);
                    }
                    component.getStore().loadData(data, false);
                }
            }
        });
        this.callParent(arguments);
    }
})