Ext.define('kateglo.modules.entry.tree.Explorer', {
    extend: 'Ext.tree.Panel',
    title: 'Entri Explorer',
    hideHeaders: true,
    split: true,
    width: 200,
    rootVisible: false,
    collapsible: true,
    hideCollapseTool: true,
    tools: [
        {
            type: 'help',
            handler: function() {
                Ext.MessageBox.alert('Info', 'Not yet implemented. Stay tuned!');
            }
        }
    ],
    bbar: [
        '->',
        {
            text: 'Delete',
            iconCls: 'cpanel_sprite cpanel_delete'
        }
    ],
    listeners: {
        beforerender: function(component) {
            var store = component.getStore();

            meanings = new Array();
            for (var i = 0; i < component.recordResult.meanings.length; i++) {
                definitions = new Array();
                for (var j = 0; j < component.recordResult.meanings[i].definitions.length; j++) {
                    definitions.push({
                        text: component.recordResult.meanings[i].definitions[j].definition,
                        obj: "definition",
                        leaf: true
                    });
                }
                var definition = {
                    text: 'Definisi',
                    obj: "definitions",
                    expanded: true,
                    children: definitions
                };

                meanings.push({
                    text: i + 1,
                    obj: "meaning",
                    expanded: true,
                    children: [
                        {text: 'Bentuk', obj: "type", leaf: true},
                        definition,
                        {text: 'Sinonim', obj: "synonym", leaf: true},
                        {text: 'Antonim', obj: "antonym", leaf: true},
                        {text: 'Relasi', obj: "relation", leaf: true},
                        {text: 'Silabel', obj: "syllabel", leaf: true},
                        {text: 'Salah eja', obj: "misspelled", leaf: true}
                    ]
                });
            }

            store.setRootNode({
                expanded: true,
                text:"",
                user:"",
                status:"",
                children: [
                    { text:"Entri", obj: "entry", leaf: true },
                    { text: 'Arti', obj: "meanings", expanded: true,
                        children: meanings
                    },
                    { text: "Padanan", obj: "equivalent", leaf:true },
                    { text: "Sumber", obj: "source", leaf:true }
                ]
            });
        },
        itemdblclick: function(view, record, item, index, event) {
            if (record.raw.obj) {
                var comp = Ext.getCmp('entryContent' + view.panel.recordResult.id);
                switch (record.raw.obj) {
                    case "entry":
                        comp.removeAll();
                        comp.add(new kateglo.modules.entry.forms.Entry({
                            recordResult: view.panel.recordResult
                        }));
                        break;
                    case "type":
                        comp.removeAll();
                        comp.add(new kateglo.modules.entry.forms.Type({
                            recordResult: view.panel.recordResult.meanings[record.parentNode.raw.text - 1].types
                        }));
                        break;
                    case "antonym":
                        comp.removeAll();
                        comp.add(new kateglo.modules.entry.forms.Antonym({
                            recordResult: view.panel.recordResult.meanings[record.parentNode.raw.text - 1].antonyms
                        }));
                        break;
                    case "synonym":
                        comp.removeAll();
                        comp.add(new kateglo.modules.entry.forms.Synonym({
                            recordResult: view.panel.recordResult.meanings[record.parentNode.raw.text - 1].synonyms
                        }));
                        break;
                    case "relation":
                        comp.removeAll();
                        comp.add(new kateglo.modules.entry.forms.Relation({
                            recordResult: view.panel.recordResult.meanings[record.parentNode.raw.text - 1].relations
                        }));
                        break;
                    default:

                }
            }
        }
    },
    initComponent: function() {
        Ext.apply(this, {
            store: new Ext.data.TreeStore({
                storeId: 'Entry'+this.recordResult.id
            })
        });
        this.callParent(arguments);
    }

});
