$(document).ready(function() {
    if ($('#entry-random') != null){
        $.ajax({
            url: '/',
            type: 'GET',
            dataType: 'json',
            success : function(data, status, xhr){
                $('li.loader').remove();
                $.each(data.entry.docs, function(index, entry){
                    if($.isArray(entry.definition)){
                        definition = entry.definition[0];
                    }else{
                        definition = entry.definition;
                    }
                    $('#entry-random').append('<li><strong><a href="entri/'+entry.entry+'">'+entry.entry+'</a></strong><p>'+definition+'</p></li>');

                });

                $.each(data.misspelled.docs, function(index, entry) {
                    $('#misspelled-random').append('<li><strong><a href="entri/' + entry.spelled + '">' + entry.spelled + '</a></strong><p>bukan <a href="entri/' + entry.entry + '">' + entry.entry + '</a></p></li>');
                });
            },
            error: function(xhr, status, errorThrown){
                //console log? console.log('server-side failure with status code ' + response.status);
            }
        })
    }
});