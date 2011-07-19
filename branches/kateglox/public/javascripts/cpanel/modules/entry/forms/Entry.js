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
                var formPanel = this.up('form').getForm();
                Ext.Ajax.defaultHeaders = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };
                Ext.Ajax.request({
                    url: '/entri',
                    method: 'POST',
                    timeout: 60000,
                    jsonData: {
                        id: formPanel.recordResult.id,
                        version: formPanel.recordResult.version,
                        entry: formPanel.getValues().entry
                    },
                    success: function(form, action) {
                        Ext.Msg.alert('Success', action.result.msg);
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert('Failed', 'something is wrong');
                    }
                });
            }
        },
        '->',
        {
            text: 'Reset',
            iconCls: 'cpanel_sprite cpanel_arrow_undo',
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
                            if (!tabPanel.origTitle)
                                tabPanel.origTitle = tabPanel.title;
                            if (!formPanel.origTitle)
                                formPanel.origTitle = formPanel.title;
                            if (field.isDirty()) {
                                formPanel.setTitle('*' + formPanel.origTitle);
                                tabPanel.setTitle('*' + tabPanel.origTitle);
                                saveButton.enable();
                            } else {
                                formPanel.setTitle(formPanel.origTitle);
                                tabPanel.setTitle(tabPanel.origTitle);
                                saveButton.disable();
                            }
                        }
                    }
                })
            ]
        });
        this.callParent(arguments);
    }

});
