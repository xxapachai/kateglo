Ext.define("kateglo.stores.search.Source", {
    extend:'Ext.data.Store',
    model:'kateglo.models.Entry',
    pageSize:999999999,
    proxy:{
        type:'rest',
        url:'/cpanel/cari/sumber',
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
