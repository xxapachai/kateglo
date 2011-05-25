Ext.require(['*']);
Ext.onReady(function() {

    Ext.create('Ext.Viewport', {
        layout: {
            type: 'border',
            padding: 0
        },
        defaults: {
            split: false
        },
        items: [
            {
                region: 'north',
                height: 50,
                border: false,
                padding: '0 0 1 0',
                html: 'north'
            },
            {
                region: 'west',
                split:true,
                border: false,
                animate: false,
                activeOnTop: true,
                collapsible: true,
                hideCollapseTool: true,
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
                    },
                    {
                        title: 'Panel 3',
                        id: 'panel3',
                        html: 'Content'
                    }
                ]
            },
            {
                region: 'center',
                xtype: 'tabpanel',
                border: false,
                activeTab: 0,
                defaults: {
                    border: false
                },
                items: [
                    {
                        title: 'Tab 1',
                        html : 'A simple tab'
                    },
                    {
                        title: 'Tab 2',
                        html : 'Another one'
                    }
                ]
            }
        ]
    });
});
