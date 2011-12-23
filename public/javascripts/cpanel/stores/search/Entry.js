Ext.define("kateglo.stores.search.Entry", {
    extend:'Ext.data.Store',
    model:'kateglo.models.Entry',
    pageSize:999999999,
    proxy:{
        type:'rest',
        url:'/cpanel/cari/entri',
        headers:{
            Accept:'application/json'
        },
        reader:{
            type:'json',
            root:'hits',
            totalProperty:'total'
        }
    }
});
