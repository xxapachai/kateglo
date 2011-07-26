Ext.define('kateglo.utils.Message', {

    statics: {
        msg : function(title, format) {
            if (!this.msgCt) {
                this.msgCt = Ext.core.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.core.DomHelper.append(this.msgCt, '<div class="msg"><h3>' + title + '</h3><p>' + s + '</p></div>', true);
            m.hide();
            m.slideIn('t').ghost("t", { delay: 1000, remove: true});
        }
    }
});