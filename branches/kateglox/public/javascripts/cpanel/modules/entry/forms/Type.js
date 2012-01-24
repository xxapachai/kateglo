Ext.define('kateglo.modules.entry.forms.Type', {
    extend:'Ext.form.Panel',
    title:'Bentuk Kata',
    tbar:[
        {
            text:'Save',
            iconCls:'cpanel_sprite cpanel_disk',
            disabled:true,
            handler:function () {
                var form = this.up('form').getForm();
                var formPanel = this.up('form');
                var tabPanel = this.up('panel').up('panel').up('panel');
                var contentPanel = this.up('panel').up('panel');
                var treePanel = contentPanel.up().getComponent(0);
                var box = Ext.MessageBox.wait('Updating Types for Entry Object.', 'Please wait!');
                Ext.Ajax.defaultHeaders = {
                    'Accept':'application/json',
                    'Content-Type':'application/json'
                };
                Ext.Ajax.request({
                    url:'/cpanel/entri/meaning/' + form.recordResult.id + '/types',
                    method:'PUT',
                    timeout:60000,
                    jsonData:{
                        id:form.recordResult.id,
                        version:form.recordResult.version,
                        types:form.getValues().type
                    },
                    success:function (response, request) {
                        responseObj = Ext.JSON.decode(response.responseText);
                        form.recordResult = responseObj;
                        formPanel.setTitle(formPanel.origTitle);
                        tabPanel.setTitle(tabPanel.origTitle);
                        contentPanel.insert(0, new kateglo.modules.entry.forms.Type({
                            recordResult:form.recordResult,
                            recordNode: formPanel.recordNode
                        }));
                        treePanel.recordResult.meanings[formPanel.recordNode] = form.recordResult
                        formPanel.destroy();
                        box.hide();
                        kateglo.utils.Message.msg('Success', 'Types saved');
                    },
                    failure:function (response, request) {
                        box.hide();
                        Ext.Msg.alert('Failed', 'something is wrong');
                    }
                });
            }
        },
        '->',
        {
            text:'Reset',
            iconCls:'cpanel_sprite cpanel_arrow_undo',
            disabled:true,
            handler:kateglo.modules.entry.utils.Form.reset
        }
    ],
    listeners:{
        beforerender:function (component) {
            var store = new kateglo.stores.Type();
            var box = Ext.MessageBox.wait('Loading Types.', 'Please wait!');
            store.load({
                scope:this,
                callback:function (records, operation, success) {
                    var initVal = new Array();
                    for (var i = 0; i < component.recordResult.types.length; i++) {
                        initVal.push(component.recordResult.types[i].id)
                    }

                    var comboBox = new kateglo.utils.BoxSelect({
                        margin:'20 10 10 20',
                        name:'type',
                        displayField:'name',
                        valueField:'id',
                        queryMode:'local',
                        anchor:'100%',
                        hideTrigger:true,
                        store:store,
                        recordResult:component.recordResult.types,
                        value:initVal,
                        listeners:{
                            change:{
                                scope:this,
                                fn:kateglo.modules.entry.utils.Form.change
                            }
                        }
                    });

                    component.add(comboBox);
                    box.hide();
                }

            });
        }
    },
    initComponent:function () {
        Ext.apply(this, {

        });
        this.callParent(arguments);
    }

});
