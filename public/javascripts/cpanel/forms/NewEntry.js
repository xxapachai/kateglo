Ext.define('kateglo.forms.NewEntry', {
    extend: 'Ext.form.Panel',
    origTitle: this.title,
    tbar: [
        {
            text: 'Save',
            iconCls: 'cpanel_sprite cpanel_disk',
            disabled: true,
            handler: function() {
                var form = this.up('form').getForm();
                var formPanel = this.up('form');
                var tabPanel = this.up('panel').up('panel').up('panel');
                var contentContainer = this.up('panel').up('panel').up('panel').up();
                var saveButton = formPanel.getDockedItems('toolbar')[0].getComponent(0);
                var resetButton = formPanel.getDockedItems('toolbar')[0].getComponent(2);
                var contentPanel = this.up('panel').up('panel');
                var box = Ext.MessageBox.wait('Creating Entry Object.', 'Please wait!');
                Ext.Ajax.defaultHeaders = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };
                Ext.Ajax.request({
                    url: '/cpanel/entri',
                    method: 'PUT',
                    timeout: 60000,
                    jsonData: {
                        entry: form.getValues().entry
                    },
                    success: function(response, request) {
                        responseObj = Ext.JSON.decode(response.responseText);
                        var entryTab = new kateglo.tabs.Entry({
                            id: 'kateglo.tabs.Entry.' + responseObj.id,
                            title: 'Entri - ' + responseObj.entry,
                            recordResult: responseObj
                        });
                        contentContainer.add(entryTab);
                        entryTab.show();
                        tabPanel.destroy();
                        kateglo.utils.Message.msg('Success', 'Entry object created');
                        box.hide();
                    },
                    failure: function(response, request) {
                        box.hide();
                        Ext.Msg.alert('Failed', 'something is wrong');
                    }
                });
            }
        },
        '->',
        {
            text: 'Reset',
            iconCls: 'cpanel_sprite cpanel_arrow_undo',
            disabled: true,
            handler: function() {
                this.up('form').getForm().reset();
            }
        }
    ],
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.field.Text({
                    margin: '20 10 10 20',
                    name: 'entry',
                    anchor: '100%',
                    checkChangeBuffer: 1000,
                    allowBlank: false,
                    listeners: {
                        change: function(field, newValue, oldValue) {
                            var tabPanel = field.up('panel').up('panel').up('panel');
                            var formPanel = field.up('form');
                            var saveButton = formPanel.getDockedItems('toolbar')[0].getComponent(0);
                            var resetButton = formPanel.getDockedItems('toolbar')[0].getComponent(2);
                            if (!tabPanel.origTitle)
                                tabPanel.origTitle = tabPanel.title;
                            if (field.isDirty() && field.isValid()) {
                                tabPanel.setTitle('*' + tabPanel.origTitle);
                                saveButton.enable();
                                resetButton.enable();
                            } else {
                                tabPanel.setTitle(tabPanel.origTitle);
                                saveButton.disable();
                                resetButton.disable();
                            }
                        }
                    }
                })
            ]
        });
        this.callParent(arguments);
    }

});
