Ext.BLANK_IMAGE_URL = '/images/s.gif';
Ext.onReady(function() {
    if (Ext.get('entry-random') != null) {
        Ext.Ajax.defaultHeaders = {
            'Accept': 'application/json'
        };
        Ext.Ajax.request({
            url: '/',
            success: function(response, opts) {
                var obj = Ext.decode(response.responseText);
                Ext.select('li.loader').remove();
                //Ext.fly('entry-amount').insertHtml('afterBegin', obj.amount);
                
                Ext.each(obj.entry.docs, function(entry, index) {
                    var definition = '';
                    if(Ext.isArray(entry.definition)){
                        definition = entry.definition[0];
                    }else{
                        definition = entry.definition;
                    }
                    Ext.fly('entry-random').insertHtml('beforeEnd', '<li><strong><a href="entri/'+entry.entry+'">'+entry.entry+'</a></strong><p>'+definition+'</p></li>');
                });

                Ext.each(obj.misspelled.docs, function(entry, index) {
                    Ext.fly('misspelled-random').insertHtml('beforeEnd', '<li><strong><a href="entri/'+entry.spelled+'">'+entry.spelled+'</a></strong><p>bukan <a href="entri/'+entry.entry+'">'+entry.entry+'</a></p></li>');
                });

            },
            failure: function(response, opts) {
                console.log('server-side failure with status code ' + response.status);
            }
        });
    }
    if (Ext.select('h1.entry-header:first').elements.length != 0) {
        var el = Ext.select('div.content');
        var tabs = Ext.createWidget('tabpanel', {
            renderTo: Ext.get('content-container'),
            width: 600,
            maxHeight: 5000,
            minHeight: 600,
            plain: true,
            activeTab: 0,
            border: 0,
            bodyBorder: 0,
            defaults :{
                bodyPadding: 10
            },
            items: [
                {
                    title: 'Entri',
                    contentEl: el.elements[0]
                },
                {
                    title: 'Sunting',
                    html: 'test'
                }
            ]
        });
    }
});