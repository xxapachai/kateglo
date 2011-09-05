Ext.Loader.setConfig({enabled: true, disableCaching: true});
Ext.Loader.setPath({
    'kateglo.borders': '/javascripts/cpanel/borders',
    'kateglo.menus' : '/javascripts/cpanel/menus',
    'kateglo.models' : '/javascripts/cpanel/models',
    'kateglo.stores' : '/javascripts/cpanel/stores',
    'kateglo.utils' : '/javascripts/cpanel/utils',
    'kateglo.grids' : '/javascripts/cpanel/grids',
    'kateglo.tabs' : '/javascripts/cpanel/tabs',
    'kateglo.forms' : '/javascripts/cpanel/forms',
    'kateglo.modules.entry.tree' : '/javascripts/cpanel/modules/entry/tree',
    'kateglo.modules.entry.forms' : '/javascripts/cpanel/modules/entry/forms',
    'kateglo.modules.entry.grids' : '/javascripts/cpanel/modules/entry/grids',
    'kateglo.modules.entry.panels' : '/javascripts/cpanel/modules/entry/panels',
    'kateglo.modules.entry.utils' : '/javascripts/cpanel/modules/entry/utils',
    'kateglo.modules.wordoftheday' : '/javascripts/cpanel/modules/wordoftheday'
});
Ext.require(['*',
    'kateglo.borders.Header',
    'kateglo.borders.Menu',
    'kateglo.borders.Content',
    'kateglo.models.Entry',
    'kateglo.models.Type',
    'kateglo.models.Discipline',
    'kateglo.models.Language',
    'kateglo.models.Meaning',
    'kateglo.models.Equivalent',
    'kateglo.models.Foreign',
    'kateglo.stores.Entry',
    'kateglo.stores.Type',
    'kateglo.stores.Language',
    'kateglo.stores.Discipline',
    'kateglo.stores.Meaning',
    'kateglo.stores.Foreign',
    'kateglo.utils.SearchField',
    'kateglo.utils.BoxSelect',
    'kateglo.utils.RowExpander',
    'kateglo.utils.Message',
    'kateglo.tabs.Entry',
    'kateglo.tabs.NewEntry',
    'kateglo.forms.NewEntry',
    'kateglo.modules.entry.utils.Form',
    'kateglo.modules.entry.grids.Relation',
    'kateglo.modules.entry.grids.Equivalent',
    'kateglo.modules.entry.forms.Type',
    'kateglo.modules.entry.forms.Entry',
    'kateglo.modules.entry.forms.Equivalent',
    'kateglo.modules.entry.panels.Antonym',
    'kateglo.modules.entry.panels.Synonym',
    'kateglo.modules.entry.panels.Relation',
    'kateglo.modules.entry.forms.MeaningComboBox',
    'kateglo.modules.entry.panels.Equivalent',
    'kateglo.modules.entry.tree.Explorer',
    'kateglo.modules.wordoftheday.Panel',
    'kateglo.modules.wordoftheday.Form',
    'kateglo.modules.wordoftheday.Grid',
    'kateglo.menus.Content',
    'kateglo.menus.SearchEntry',
    'kateglo.menus.SearchEquivalent',
    'kateglo.menus.SearchSource',
    'kateglo.menus.SearchField',
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
