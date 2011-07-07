Ext.define('kateglo.modules.entry.forms.Relation', {
    extend: 'Ext.form.Panel',
    title: 'Relation',
    layout: 'border',
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk'
        }
    ],
    listeners: {
        beforerender: function(component) {
            var data = new Array();

            for (var i = 0; i < component.recordResult.length; i++) {
                var meaning = Array();
                meaning.push(component.recordResult[i].id);
                meaning.push(component.recordResult[i].meaning.entry.id);
                meaning.push(component.recordResult[i].meaning.entry.entry);
                meaning.push(component.recordResult[i].meaning.definitions[0].definition);

                var definitions = '<ul class="rowexpander">';
                for (var j = 0; j < component.recordResult[i].meaning.definitions.length; j++) {
                    definitions += '<li>' + component.recordResult[i].meaning.definitions[j].definition + '</li>';
                }
                definitions += '</ul>';
                meaning.push(definitions);

                data.push(meaning);
            }

            var grid = new Ext.grid.Panel({
                region: 'center',
                split: true,
                border: false,
                store: new Ext.data.ArrayStore({
                    model: 'kateglo.models.Meaning',
                    data: data
                }),
                height: 300,
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
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.Panel({
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
                            store: new kateglo.stores.Entry()
                        })]
                })
            ]
        });
        this.callParent(arguments);
    }

});
