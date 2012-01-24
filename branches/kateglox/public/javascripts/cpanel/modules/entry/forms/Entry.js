Ext.define('kateglo.modules.entry.forms.Entry', {
    extend:'Ext.form.Panel',
    title:'Entri',
    origTitle:this.title,
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
                var box = Ext.MessageBox.wait('Updating Entry Object.', 'Please wait!');
                Ext.Ajax.defaultHeaders = {
                    'Accept':'application/json',
                    'Content-Type':'application/json'
                };
                Ext.Ajax.request({
                    url:'/cpanel/entri',
                    method:'POST',
                    timeout:60000,
                    jsonData:{
                        id:form.recordResult.id,
                        version:form.recordResult.version,
                        entry:form.getValues().entry
                    },
                    success:function (response, request) {
                        responseObj = Ext.JSON.decode(response.responseText);
                        form.recordResult.id = responseObj.id;
                        form.recordResult.version = responseObj.version;
                        form.recordResult.entry = responseObj.entry;
                        tabPanel.origTitle = 'Entri - ' + responseObj.entry;
                        formPanel.origTitle = responseObj.entry;
                        formPanel.setTitle(formPanel.origTitle);
                        tabPanel.setTitle(tabPanel.origTitle);
                        contentPanel.insert(0, new kateglo.modules.entry.forms.Entry({
                            recordResult:form.recordResult
                        }));
                        treePanel.recordResult = form.recordResult
                        formPanel.destroy();
                        box.hide();
                        kateglo.utils.Message.msg('Success', 'Entry object saved');
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
    initComponent:function () {
        Ext.apply(this, {
            items:[
                new Ext.form.field.Text({
                    margin:'20 10 10 20',
                    name:'entry',
                    anchor:'100%',
                    checkChangeBuffer:1000,
                    value:this.recordResult.entry,
                    allowBlank:false,
                    listeners:{
                        change:kateglo.modules.entry.utils.Form.change
                    }
                })
            ]
        });
        this.callParent(arguments);
    }

});
