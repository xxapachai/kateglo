Ext.define('kateglo.menus.SearchField', {
    extend:'kateglo.utils.SearchField',

    onTrigger1Click:function () {
        var me = this,
            store = me.store,
            proxy = store.getProxy(),
            resultContainer = this.up().getComponent('resultContainer'),
            val;

        if (me.hasSearch) {
            me.setValue('');
            proxy.extraParams[me.paramName] = '';
            proxy.extraParams.start = 0;
            store.removeAll();
            resultContainer.insert(0, resultContainer.emptyResultText);
            me.hasSearch = false;
            me.triggerEl.item(0).setDisplayed('none');
            me.doComponentLayout();
        }
    },

    onTrigger2Click:function () {
        //console.log(this);
        var me = this,
            store = me.store,
            proxy = store.getProxy(),
            value = me.getValue();

        if (value.length < 1) {
            me.onTrigger1Click();
            return;
        }
        proxy.extraParams[me.paramName] = value;
        proxy.extraParams.start = 0;
        store.load({
            scope:this,
            callback:function (records, operation, success) {
                resultContainer = this.up().getComponent(1);
                if (success) {
                    resultContainer.insert(0, resultContainer.showResultText);
                } else {
                    resultContainer.insert(0, resultContainer.errorResultText);
                }
            }
        });
        me.hasSearch = true;
        me.triggerEl.item(0).setDisplayed('block');
        me.doComponentLayout();
    }
});