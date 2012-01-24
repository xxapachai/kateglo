Ext.define('kateglo.modules.entry.utils.Form', {
    statics:{
        change:function (field, newValue, oldValue) {
            if (field.up('panel')) {
                var tabPanel = field.up('panel').up('panel').up('panel');
                var formPanel = field.up('form');
                var saveButton = formPanel.getDockedItems('toolbar')[0].getComponent(0);
                var resetButton = formPanel.getDockedItems('toolbar')[0].getComponent(2);
                if (!tabPanel.origTitle)
                    tabPanel.origTitle = tabPanel.title;
                if (!formPanel.origTitle)
                    formPanel.origTitle = formPanel.title;
                if (field.isDirty() && field.isValid()) {
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
        },
        reset:function (button, event) {
            this.up('form').getForm().reset();
        }
    }
});
