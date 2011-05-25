Ext.require(['*']);
Ext.onReady(function() {

    Ext.create('Ext.Viewport', {
        layout: {
            type: 'border',
            padding: 5
        },
        defaults: {
            split: true
        },
        items: [
            {
                region: 'north',
                height: 50,
                html: 'north'
            },
            {
                region: 'west',
                collapsible: true,
                title: 'West',
                width: 250,
                layout: 'accordion',
                items:[
                    {
                        title: 'Panel 1',
                        html: 'Content'
                    },
                    {
                        title: 'Panel 2',
                        id: 'panel2',
                        html: 'Content'
                    }
                ]
            },
            {
                region: 'center',
                border: false,
                html: 'test'
            }
        ]
    });
});
