Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath({
    'kateglo.borders': '/javascripts/cpanel/borders',
    'kateglo.menus' : '/javascripts/cpanel/menus',
    'kateglo.models' : '/javascripts/cpanel/models',
    'kateglo.stores' : '/javascripts/cpanel/stores',
    'kateglo.utils' : '/javascripts/cpanel/utils',
    'kateglo.grids' : '/javascripts/cpanel/grids',
    'kateglo.tabs' : '/javascripts/cpanel/tabs',
    'kateglo.modules.entry.tabs' : '/javascripts/cpanel/modules/entry/tabs'
});
Ext.require(['*',
    'kateglo.borders.Header',
    'kateglo.borders.Menu',
    'kateglo.borders.Content',
    'kateglo.models.Entry',
    'kateglo.stores.Entry',
    'kateglo.menus.Search',
    'kateglo.utils.SearchField',
    'kateglo.tabs.Entry',
    'kateglo.modules.entry.tabs.Meaning',
    'kateglo.grids.MenuSearchResult'
]);
Ext.onReady(function() {

    Ext.QuickTips.init();
    Ext.create('Ext.Viewport', {
        layout: 'border',
        items: [
            new kateglo.borders.Header(),
            new kateglo.borders.Menu(),
            new kateglo.borders.Content()
        ]
    });
});
