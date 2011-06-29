Ext.define('kateglo.modules.entry.tree.Explorer', {
    extend: 'Ext.tree.Panel',
    title: 'Entri Explorer',
    hideHeaders: true,
    split: true,
    width: 200, id: 'Arti',
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
                        leaf: true
                    });
                }
                var definition = {
                    text: 'Definisi',
                    expanded: false,
                    children: definitions
                };

                meanings.push({
                    text: i + 1,
                    children: [
                        {text: 'Bentuk', leaf: true},
                        definition,
                        {text: 'Sinonim', leaf: true},
                        {text: 'Antonim', leaf: true},
                        {text: 'Relasi', leaf: true},
                        {text: 'Silabel', leaf: true},
                        {text: 'Salah eja', leaf: true}
                    ]
                });
            }

            store.setRootNode({
                expanded: true,
                text:"",
                user:"",
                status:"",
                children: [
                    { text:"Entri", leaf: true },
                    { text: 'Arti', expanded: true,
                        children: meanings
                    },
                    { text: "Padanan", leaf:true },
                    { text: "Sumber", leaf:true }
                ]
            });
        },
        itemdblclick: function(view, record, item, index, event){
            var console = window.console;
            console.log(view);
            console.log(record);
            console.log(item);
            console.log(index);
            console.log(event);
        }
    },
    store: new Ext.data.TreeStore({}),
    initComponent: function() {
        Ext.apply(this, {

        });
        this.callParent(arguments);
    }

});
