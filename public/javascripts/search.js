Ext.BLANK_IMAGE_URL = '/libs/extjs/resources/images/default/s.gif';

function changeContext(newContext){
	if(newContext=='lemma'){
		document.getElementsByName('searchLemma')[0].style.display = "block";
		document.getElementsByName('searchGlossary')[0].style.display = "none";
	}else{
		document.getElementsByName('searchLemma')[0].style.display = "none";
		document.getElementsByName('searchGlossary')[0].style.display = "block";
	}
}

Ext.onReady(function(){

	var ds = new Ext.data.Store({
        proxy: new Ext.data.ScriptTagProxy({
            url: '/search',
            extraParams: { 
    			output: 'json',
    			context: 'lemma'
    		}
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
            totalProperty: 'count'
        }, ['id', 'score', 'lemma', 'glossary'])
    });
	
    // Custom rendering Template
    var resultTpl = new Ext.XTemplate(
        '<tpl for="."><div class="search-item">',
            '<table style="width: 100%;" cellpadding="0" cellspacing="0" border="0"><tr><td style="text-align: left;">&nbsp;{lemma}</td><td style="text-align: right;">{score} Score&nbsp;&nbsp;</td></tr></table>',
        '</div></tpl>'
    );
	
	var searchLemma = new Ext.form.ComboBox({
        store: ds,
        minChars: 2,
        displayField:'title',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 570,
        hideTrigger:true,
        tpl: resultTpl,
        applyTo: 'searchLemma',
        itemSelector: 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect
            window.location =
                String.format('/lemma/{0}', record.data.lemma);
        }
    });
	
	var dsGlo = new Ext.data.Store({
        proxy: new Ext.data.ScriptTagProxy({
            url: '/search',
            extraParams: { 
    			output: 'json',
    			context: 'glossary'
    		}
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
            totalProperty: 'count'
        }, ['id', 'score', 'lemma', 'glossary'])
    });
	
    var resultTplGlo = new Ext.XTemplate(
        '<tpl for="."><div class="search-item">',
            '<table class="search-item" style="width: 100%;" cellpadding="0" cellspacing="0" border="0"><tr><td style="text-align: left;">&nbsp;{glossary}</td><td style="text-align: right;">{lemma}&nbsp;&nbsp;</td></tr></table>',
        '</div></tpl>'
    );
    
    var searchGlo = new Ext.form.ComboBox({
        store: dsGlo,
        minChars: 2,
        displayField:'title',
        typeAhead: false,
        loadingText: 'Searching...',
        width: 470,
        hideTrigger:true,
        tpl: resultTplGlo,
        applyTo: 'searchGlossary',
        itemSelector: 'div.search-item',
        onSelect: function(record){ // override default onSelect to do redirect
            window.location =
                String.format('/lemma/{0}', record.data.lemma);
        }
    });
});