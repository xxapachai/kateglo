Ext.Loader.setConfig({enabled: true, disableCaching: true});
Ext.Loader.setPath({
    'kateglo.borders': '/javascripts/cpanel/borders',
    'kateglo.menus' : '/javascripts/cpanel/menus',
    'kateglo.models' : '/javascripts/cpanel/models',
    'kateglo.stores' : '/javascripts/cpanel/stores',
    'kateglo.utils' : '/javascripts/cpanel/utils',
    'kateglo.grids' : '/javascripts/cpanel/grids',
    'kateglo.tabs' : '/javascripts/cpanel/tabs',
    'kateglo.modules.entry.tabs' : '/javascripts/cpanel/modules/entry/tabs',
    'kateglo.modules.entry.tree' : '/javascripts/cpanel/modules/entry/tree',
    'kateglo.modules.entry.forms' : '/javascripts/cpanel/modules/entry/forms',
    'kateglo.modules.entry.utils' : '/javascripts/cpanel/modules/entry/utils'
});
Ext.require(['*',
    'kateglo.borders.Header',
    'kateglo.borders.Menu',
    'kateglo.borders.Content',
    'kateglo.models.Entry',
    'kateglo.models.Type',
    'kateglo.models.Meaning',
    'kateglo.stores.Entry',
    'kateglo.stores.Type',
    'kateglo.stores.Meaning',
    'kateglo.utils.SearchField',
    'kateglo.utils.BoxSelect',
    'kateglo.utils.RowExpander',
    'kateglo.tabs.Entry',
    'kateglo.modules.entry.utils.MeaningGridBeforeRender',
    'kateglo.modules.entry.tabs.Meaning',
    'kateglo.modules.entry.forms.Type',
    'kateglo.modules.entry.forms.Entry',
    'kateglo.modules.entry.forms.Antonym',
    'kateglo.modules.entry.forms.Synonym',
    'kateglo.modules.entry.forms.Relation',
    'kateglo.modules.entry.forms.MeaningComboBox',
    'kateglo.modules.entry.tree.Explorer',
    'kateglo.menus.Search',
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
