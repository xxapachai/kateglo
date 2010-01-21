Ext.BLANK_IMAGE_URL = '/libs/extjs/resources/images/default/s.gif';

Ext.onReady(function(){

    Ext.QuickTips.init();

    Ext.Ajax.request({
        url: '/lists?lists[]=type&lists[]=lexical',
        timeout: 3000,
        method: 'GET',
        success: createGrid
    });
    
    function createGrid(xhr){
    	xhrObject = eval("("+xhr.responseText+")");

        // configure whether filter query is encoded or not (initially)
        var encode = false;
        
        var typeOptions = new Array();
        
        for(var i=0; i<xhrObject.type.length; i++){
        	typeOptions[i] = xhrObject.type[i].type;
        }
        
        var lexicalOptions = new Array();
        
        for(var i=0; i<xhrObject.lexical.length; i++){
        	lexicalOptions[i] = xhrObject.lexical[i].lexical;
        }

        store = new Ext.data.JsonStore({
            // store configs
            autoDestroy: true,
            url: '/lists',
            remoteSort: true,
            sortInfo: {
                field: 'lemma',
                direction: 'ASC'
            },
            storeId: 'myStore',
            
            // reader configs
            root: 'data',
            totalProperty: 'totalCount',
            fields: [{
                name: 'id'
            }, {
                name: 'lemma'
            }, {
                name: 'type'
            }, {
                name: 'definition'
            }, {
                name: 'lexical'
            }]
        });

        var filters = new Ext.ux.grid.GridFilters({
            // encode and local configuration options defined previously for easier reuse
            encode: encode, // json encode the filter query
            filters: [{
                type: 'string',
                dataIndex: 'lemma'
            }, {
                type: 'list',
                dataIndex: 'type',
                options: typeOptions,
                phpMode: true
            }, {
                type: 'string',
                dataIndex: 'definition'
            }, {
                type: 'list',
                dataIndex: 'lexical',
                options: lexicalOptions,
                phpMode: true
            }]
        });    
        
        function renderLink(value, p, record){
            return '<a href="/lemma/'+value+'">'+value+'</a>';
        }
        
        var createColModel = function () {

            var columns = [{
                dataIndex: 'lemma',
                header: 'Lemma',
                filterable: true,
                id: 'lemma',
                width: 300,
                renderer: renderLink
            }, {
                dataIndex: 'type',
                header: 'Type',
                filterable: true,
                width: 200,
                sortable: false
            }, {
                dataIndex: 'definition',
                header: 'Definition',
                filterable: true,
                id: 'definition',
                sortable: false
            }, {
                dataIndex: 'lexical',
                header: 'Lexical',
                filterable: true,
                width: 200,
                sortable: false
            }];

            return new Ext.grid.ColumnModel({
                columns: columns,
                defaults: {
                    sortable: true
                }
            });
        };
        
        var grid = new Ext.grid.GridPanel({
            border: false,
            region: 'center',
            store: store,
            colModel: createColModel(),
            plugins: [filters],
            autoExpandColumn: 'definition',
            loadMask: true,
            listeners: {
                render: {
                    fn: function(){
                        store.load({
                            params: {
                                start: 0,
                                limit: 50
                            }
                        });
                    }
                }
            },
            bbar: new Ext.PagingToolbar({
                store: store,
                pageSize: 50,
                plugins: [filters]
            })
        });    
        
        var ct = new Ext.Panel({
    		renderTo: 'gridlist',
    		frame: true, 
    		autoWidth: true,
    		height: 400,
    		title: 'Lemma List',
    		layout: 'border',
    		items: [
    			grid
    		],
    		viewConfig: {
    				forceFit: true
    			}
    	});
    }

});