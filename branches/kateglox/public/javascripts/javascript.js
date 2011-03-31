Ext.BLANK_IMAGE_URL = '/images/s.gif';
Ext.onReady(function() {
    if (Ext.select('h1.entry-header:first').elements.length != 0) {
        var el = Ext.select('div.content');
        var tabs = Ext.createWidget('tabpanel', {
            renderTo: Ext.get('content-container'),
            width: 600,
            maxHeight: 5000,
            minHeight: 600,
            activeTab: 0,
            defaults :{
                bodyPadding: 10
            },
            items: [
                {
                    title: 'Short Text',
                    contentEl: el.elements[0]
                },
                {
                    title: 'Long Text',
                    html: 'test'
                }
            ]
        });
    }
});