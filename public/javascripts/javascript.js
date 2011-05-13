$(document).ready(function() {
    if ($('#entry-random').length > 0) {
        $.ajax({
            url: '/',
            type: 'GET',
            dataType: null,
            cache: false,
            beforeSend: function(request) {
                request.setRequestHeader('Accept', 'application/json');
            },
            success : function(data, status, xhr) {
                $('.loader').remove();
                $.each(data.entry.docs, function(index, entry) {
                    if ($.isArray(entry.definition)) {
                        definition = entry.definition[0];
                    } else {
                        definition = entry.definition;
                    }
                    $('#entry-random').append('<li><strong><a href="entri/' + entry.entry + '">' + entry.entry + '</a></strong><p>' + definition + '</p></li>');

                });

                $.each(data.misspelled.docs, function(index, entry) {
                    $('#misspelled-random').append('<li><strong><a href="entri/' + entry.spelled + '">' + entry.spelled + '</a></strong><p>bukan <a href="entri/' + entry.entry + '">' + entry.entry + '</a></p></li>');
                });
            },
            error: function(xhr, status, errorThrown) {
                //console log? console.log('server-side failure with status code ' + response.status);
            }
        })
    }

    if ($('input[name=query]').length > 0) {

        var element = $('input[name=query]');

        if (element.val() == '') {
            element.val('Ketik yang dicari, kemudian tekan tombol enter');
        } else {
            element.attr('style', 'color:black;');
        }

        element.focus(function() {
            if ($(this).val() == 'Ketik yang dicari, kemudian tekan tombol enter') {
                $(this).val('');
                $(this).attr('style', 'color:black;');
            }
        });
        element.blur(function() {
            if ($(this).val() == '') {
                $(this).val('Ketik yang dicari, kemudian tekan tombol enter');
                $(this).removeAttr('style');
            }
        });

    }

    var detailSearchOptions = {callback: advancedSearch, wait:20, highlight:false, captureLength: 1};

    if ($('#detail-search').length > 0) {
        $('#allWord').typeWatch(detailSearchOptions);
        $('#exactWord').typeWatch(detailSearchOptions);
        $('#someWord0').typeWatch(detailSearchOptions);
        $('#someWord1').typeWatch(detailSearchOptions);
        $('#someWord2').typeWatch(detailSearchOptions);
        $('#noWord').typeWatch(detailSearchOptions);
    }
});

advancedSearch = function() {
    var allWord = $('#allWord').val();
    var exactWord = jQuery.trim($('#exactWord').val()) != '' ? '"' + jQuery.trim($('#exactWord').val()) + '"' : '';
    var someWord = new Array();
    var noWord = $('#noWord').val();
    var element = $('input[name=query]');

    var someWord0 = $('#someWord0').val().split(' ').length > 1 ? '"'+$('#someWord0').val()+'"': $('#someWord0').val();
    var someWord1 = $('#someWord1').val().split(' ').length > 1 ? '"'+$('#someWord1').val()+'"': $('#someWord1').val();
    var someWord2 = $('#someWord2').val().split(' ').length > 1 ? '"'+$('#someWord2').val()+'"': $('#someWord2').val();

    if (jQuery.trim(someWord0) != '') someWord.push(jQuery.trim(someWord0));
    if (jQuery.trim(someWord1) != '') someWord.push(jQuery.trim(someWord1));
    if (jQuery.trim(someWord2) != '') someWord.push(jQuery.trim(someWord2));

    var noWordArray = noWord.split(' ');
    for (var i = 0; i < noWordArray.length; i++) {
        noWordArray[i] = jQuery.trim(noWordArray[i]) != '' ? '-' + jQuery.trim(noWordArray[i]) : '';
    }
    

    element.val(jQuery.trim(allWord + ' ' + exactWord + ' ' + someWord.join(' OR ') + ' ' + noWordArray.join(' ')));

    if (element.val() == '') {
        element.val('Ketik yang dicari, kemudian tekan tombol enter');
        $(this).removeAttr('style');
    } else {
        element.attr('style', 'color:black;');
    }

}