Ext.define('kateglo.modules.entry.forms.Entry', {
    extend: 'Ext.form.Panel',
    title: 'Entri',
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
                var saveButton = formPanel.getDockedItems('toolbar')[0].getComponent(0);
                var resetButton = formPanel.getDockedItems('toolbar')[0].getComponent(2);
                var contentPanel = this.up('panel').up('panel');
                Ext.Ajax.defaultHeaders = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };
                Ext.Ajax.request({
                    url: '/entri',
                    method: 'POST',
                    timeout: 60000,
                    jsonData: {
                        id: form.recordResult.id,
                        version: form.recordResult.version,
                        entry: form.getValues().entry
                    },
                    success: function(response, request) {
                        responseObj = Ext.JSON.decode(response.responseText);
                        form.recordResult.id = responseObj.id;
                        form.recordResult.version = responseObj.version;
                        form.recordResult.entry = responseObj.entry;
                        tabPanel.origTitle = 'Entri - ' + responseObj.entry;
                        formPanel.origTitle = responseObj.entry;
                        formPanel.setTitle(formPanel.origTitle);
                        tabPanel.setTitle(tabPanel.origTitle);
                        contentPanel.insert(0, new kateglo.modules.entry.forms.Entry({
                            recordResult: form.recordResult
                        }));
                        formPanel.destroy();
                    },
                    failure: function(response, request) {
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
    listeners: {
    },
    initComponent: function() {
        Ext.apply(this, {
            items: [
                new Ext.form.field.Text({
                    margin: '20 10 10 20',
                    name: 'entry',
                    anchor: '100%',
                    checkChangeBuffer: 1000,
                    value: this.recordResult.entry,
                    listeners: {
                        change: function(field, newValue, oldValue) {
                            var tabPanel = field.up('panel').up('panel').up('panel');
                            var formPanel = field.up('form');
                            var saveButton = formPanel.getDockedItems('toolbar')[0].getComponent(0);
                            var resetButton = formPanel.getDockedItems('toolbar')[0].getComponent(2);
                            if (!tabPanel.origTitle)
                                tabPanel.origTitle = tabPanel.title;
                            if (!formPanel.origTitle)
                                formPanel.origTitle = formPanel.title;
                            if (field.isDirty()) {
                                formPanel.setTitle('*' + formPanel.origTitle);
                                tabPanel.setTitle('*' + tabPanel.origTitle);
                                saveButton.enable();
                                resetButton.enable();
                            } else {
                                formPanel.setTitle(formPanel.origTitle);
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
