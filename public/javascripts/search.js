Ext.BLANK_IMAGE_URL = '/libs/extjs/resources/images/default/s.gif';
context = 'lemma';
function changeContext(newContext){
	context = newContext;
	createComboBox();
}
function createComboBox(){

	var ds = new Ext.data.Store({
        proxy: new Ext.data.ScriptTagProxy({
            url: '/search',
            extraParams: { 
    			output: 'json',
    			context: context
    		}
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
            totalProperty: 'count'
        }, ['id', 'score', 'lemma', 'glossary'])
    });
	
    // Custom rendering Template
    if(context == 'lemma'){
	    var resultTpl = new Ext.XTemplate(
	        '<tpl for="."><div class="search-item">',
	            '<table style="width: 100%;" cellpadding="0" cellspacing="0" border="0"><tr><td style="text-align: left;">&nbsp;{lemma}</td><td style="text-align: right;">{score} Score&nbsp;&nbsp;</td></tr></table>',
	        '</div></tpl>'
	    );
    }else{
	    var resultTpl = new Ext.XTemplate(
	        '<tpl for="."><div class="search-item">',
	            '<table class="search-item" style="width: 100%;" cellpadding="0" cellspacing="0" border="0"><tr><td style="text-align: left;">&nbsp;{glossary}</td><td style="text-align: right;">{lemma}&nbsp;&nbsp;</td></tr></table>',
	        '</div></tpl>'
	    );
    }
	
	var search = new Ext.form.ComboBox({
        store: ds,
        minChars: 2,
        displayField:'title',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 570,
        hideTrigger:true,
        tpl: resultTpl,
        applyTo: 'search',
        itemSelector: 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect
            window.location =
                String.format('/lemma/{0}', record.data.lemma);
        }
    });
}
Ext.onReady(function(){
	createComboBox();
});