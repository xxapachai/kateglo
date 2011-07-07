Ext.define('kateglo.modules.entry.utils.MeaningGridBeforeRender', {
    statics: {
        beforeRender: function(component) {

            var data = new Array();

            for (var i = 0; i < component.recordResult.length; i++) {
                var meaning = new Object();
                meaning.id = component.recordResult[i].id;
                meaning.entryId = component.recordResult[i].meaning.entry.id;
                meaning.entry = component.recordResult[i].meaning.entry.entry;
                meaning.definition = component.recordResult[i].meaning.definitions[0].definition;

                var definitions = '<ul class="rowexpander">';
                for (var j = 0; j < component.recordResult[i].meaning.definitions.length; j++) {
                    definitions += '<li>' + component.recordResult[i].meaning.definitions[j].definition + '</li>';
                }
                definitions += '</ul>';
                meaning.definitions = definitions;

                data.push(meaning);
            }

            var grid = new Ext.grid.Panel({
                region: 'center',
                split: true,
                border: false,
                store: new Ext.data.Store({
                    model: 'kateglo.models.Meaning',
                    data: data
                }),
                plugins: [
                    {
                        ptype: 'rowexpander',
                        rowBodyTpl : [
                            '<p><b>Definisi:</b> {definitions}</p>'
                        ]
                    }
                ],
                anchor: '100%',
                columns: [
                    {
                        text : 'Id',
                        width: 20,
                        sortable: true,
                        dataIndex: 'id'
                    },
                    {
                        text : 'Entri',
                        flex: 1,
                        sortable: true,
                        dataIndex: 'entry'
                    },
                    {
                        text : 'Definisi',
                        flex: 1,
                        sortable: true,
                        dataIndex: 'definition'
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
                                    var rec = grid.store.getAt(rowIndex);
                                    alert("delete " + rec.get('entry'));
                                }
                            }
                        ]
                    }
                ]
            });

            component.add(grid);
        }
    },
    constructor: function() {
    }
});
