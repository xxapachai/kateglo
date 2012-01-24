Ext.define('kateglo.modules.entry.tree.Explorer', {
    extend:'Ext.tree.Panel',
    title:'Entri Explorer',
    hideHeaders:true,
    split:true,
    width:200,
    rootVisible:false,
    collapsible:true,
    hideCollapseTool:true,
    tools:[
        {
            type:'help',
            handler:function () {
                Ext.MessageBox.alert('Info', 'Not yet implemented. Stay tuned!');
            }
        }
    ],
    bbar:[
        '->',
        {
            text:'Delete',
            iconCls:'cpanel_sprite cpanel_delete',
            handler:function () {
                var confirmBox = new Ext.window.MessageBox({
                    buttonText:{
                        yes:'Ya',
                        no:'Tidak'
                    },
                    defaultButton:2
                });
                confirmBox.confirm('Delete Entry', 'Kamu yakin? Entri akan hilang semua!',
                    function (buttonId, text, option) {
                        if (buttonId == 'yes') {
                            var box = Ext.MessageBox.wait('Deleting Entry Object.', 'Please wait!');
                            var tabPanel = this.up('panel').up('panel');
                            Ext.Ajax.defaultHeaders = {
                                'Accept':'application/json',
                                'Content-Type':'application/json'
                            };
                            Ext.Ajax.request({
                                url:'cpanel/entri/id/' + tabPanel.recordResult.id,
                                method:'DELETE',
                                timeout:60000,
                                success:function (response, request) {
                                    tabPanel.destroy();
                                    kateglo.utils.Message.msg('Success', 'Entry object deleted');
                                    box.hide();
                                },
                                failure:function (response, request) {
                                    box.hide();
                                    Ext.Msg.alert('Failed', 'something is wrong');
                                }
                            });
                        }
                    }, this);

            }
        }
    ],
    listeners:{
        beforerender:function (component) {
            var store = component.getStore();

            meanings = new Array();
            for (var i = 0; i < component.recordResult.meanings.length; i++) {
                definitions = new Array();
                for (var j = 0; j < component.recordResult.meanings[i].definitions.length; j++) {
                    definitions.push({
                        text:component.recordResult.meanings[i].definitions[j].definition,
                        obj:"definition",
                        leaf:true
                    });
                }
                var definition = {
                    text:'Definisi',
                    obj:"definitions",
                    expanded:true,
                    children:definitions
                };

                meanings.push({
                    text:i + 1,
                    obj:"meaning",
                    expanded:true,
                    children:[
                        {text:'Bentuk', obj:"type", leaf:true},
                        definition,
                        {text:'Sinonim', obj:"synonym", leaf:true},
                        {text:'Antonim', obj:"antonym", leaf:true},
                        {text:'Relasi', obj:"relation", leaf:true},
                        {text:'Silabel', obj:"syllabel", leaf:true},
                        {text:'Salah eja', obj:"misspelled", leaf:true}
                    ]
                });
            }

            store.setRootNode({
                expanded:true,
                text:"",
                user:"",
                status:"",
                children:[
                    { text:"Entri", obj:"entry", leaf:true },
                    { text:'Arti', obj:"meanings", expanded:true,
                        children:meanings
                    },
                    { text:"Padanan", obj:"equivalent", leaf:true },
                    { text:"Sumber", obj:"source", leaf:true }
                ]
            });
        },
        itemdblclick:function (view, record, item, index, event) {
            if (record.raw.obj) {
                var comp = Ext.getCmp('entryContent' + view.panel.recordResult.id);
                switch (record.raw.obj) {
                    case "entry":
                        comp.removeAll(true);
                        comp.add(new kateglo.modules.entry.forms.Entry({
                            recordResult:view.panel.recordResult
                        }));
                        break;
                    case "type":
                        comp.removeAll(true);
                        comp.add(new kateglo.modules.entry.forms.Type({
                            recordResult:view.panel.recordResult.meanings[record.parentNode.raw.text - 1],
                            recordNode: record.parentNode.raw.text - 1
                        }));
                        break;
                    case "antonym":
                        comp.removeAll(true);
                        comp.add(new kateglo.modules.entry.panels.Antonym({
                            recordResult:view.panel.recordResult.meanings[record.parentNode.raw.text - 1],
                            recordNode: record.parentNode.raw.text - 1
                        }));
                        break;
                    case "synonym":
                        comp.removeAll(true);
                        comp.add(new kateglo.modules.entry.panels.Synonym({
                            recordResult:view.panel.recordResult.meanings[record.parentNode.raw.text - 1],
                            recordNode: record.parentNode.raw.text - 1
                        }));
                        break;
                    case "relation":
                        comp.removeAll(true);
                        comp.add(new kateglo.modules.entry.panels.Relation({
                            recordResult:view.panel.recordResult.meanings[record.parentNode.raw.text - 1],
                            recordNode: record.parentNode.raw.text - 1
                        }));
                        break;
                    case "equivalent":
                        comp.removeAll(true);
                        comp.add(new kateglo.modules.entry.panels.Equivalent({
                            recordResult:view.panel.recordResult.equivalents
                        }));
                        break;
                    default:

                }
            }
        }
    },
    initComponent:function () {
        Ext.apply(this, {
            store:new Ext.data.TreeStore({
                storeId:'Entry' + this.recordResult.id
            })
        });
        this.callParent(arguments);
    }

});
