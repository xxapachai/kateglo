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

    var detailSearchOptions = {callback: advancedSearch, wait:1, highlight:false, captureLength: -1};

    if ($('#detail-search').length > 0) {
        $('#allWord').typeWatch(detailSearchOptions);
        $('#exactWord').typeWatch(detailSearchOptions);
        $('#someWord0').typeWatch(detailSearchOptions);
        $('#someWord1').typeWatch(detailSearchOptions);
        $('#someWord2').typeWatch(detailSearchOptions);
        $('#noWord').typeWatch(detailSearchOptions);
        $('select[name=rows]').change(advancedSearch);
        $('select[name=siteArea]').change(advancedSearch);
        $('select[name=type]').change(advancedSearch);
        $('select[name=class]').change(advancedSearch);
        $('select[name=discipline]').change(advancedSearch);
        $('select[name=language]').change(advancedSearch);
        $('select[name=source]').change(advancedSearch);
        $('input[name=submitAdvancedSearch]').preventDefault;
        $('input[name=submitAdvancedSearch]').click(function(){
            $('form[name=search]').submit();
            return false;
        })
    }
});

advancedSearch = function() {
    var allWord = $('#allWord').val();
    var exactWord = jQuery.trim($('#exactWord').val()) != '' ? '"' + jQuery.trim($('#exactWord').val()) + '"' : '';
    var someWord = new Array();
    var noWord = $('#noWord').val();
    var element = $('input[name=query]');

    var language = '';
    var type = '';
    var clazz = '';
    var discipline = '';
    var source = '';

    var form = $('form[name=search]')
    var formAction = form.attr('action');
    var formActionSplit = formAction.split('?');
    var formActionURL = formActionSplit[0];
    var formActionParameters = '';
    if (formActionSplit.length > 1) {
        formActionParameters = formActionSplit[1];
    }
    var rows = 10;

    var someWord0 = $('#someWord0').val().split(' ').length > 1 ? '"' + $('#someWord0').val() + '"' : $('#someWord0').val();
    var someWord1 = $('#someWord1').val().split(' ').length > 1 ? '"' + $('#someWord1').val() + '"' : $('#someWord1').val();
    var someWord2 = $('#someWord2').val().split(' ').length > 1 ? '"' + $('#someWord2').val() + '"' : $('#someWord2').val();

    if (jQuery.trim(someWord0) != '') someWord.push(jQuery.trim(someWord0));
    if (jQuery.trim(someWord1) != '') someWord.push(jQuery.trim(someWord1));
    if (jQuery.trim(someWord2) != '') someWord.push(jQuery.trim(someWord2));

    var noWordArray = noWord.split(' ');
    for (var i = 0; i < noWordArray.length; i++) {
        noWordArray[i] = jQuery.trim(noWordArray[i]) != '' ? '-' + jQuery.trim(noWordArray[i]) : '';
    }

    $('select[name=language] option:selected').each(function() {
        language = ($(this).val() == 'all') ? '' : 'language:"'+$(this).val()+'"';
    });

    $('select[name=type] option:selected').each(function() {
        type = ($(this).val() == 'all') ? '' :  'type:"'+$(this).val()+'"';
    });

    $('select[name=class] option:selected').each(function() {
        clazz = ($(this).val() == 'all') ? '' :  'class:"'+$(this).val()+'"';
    });

    $('select[name=discipline] option:selected').each(function() {
        discipline = ($(this).val() == 'all') ? '' :  'discipline:"'+$(this).val()+'"';
    })

     $('select[name=source] option:selected').each(function() {
        source = ($(this).val() == 'all') ? '' :  'sourceCategory:"'+$(this).val()+'"';
    })

    $('select[name=rows] option:selected').each(function() {
        rows = $(this).val();
    });

    $('select[name=siteArea] option:selected').each(function() {
        formActionURL = $(this).val();
    });

    if (rows != 10) {
        form.attr('action', formActionURL + '?' + 'rows='+rows);
    }else{
        form.attr('action', formActionURL);
    }

    var searchTextArray = new Array();

    jQuery.trim(allWord) != '' ? searchTextArray.push(allWord) : '';
    jQuery.trim(exactWord) != '' ? searchTextArray.push(exactWord) : '';
    jQuery.trim(someWord.join(' OR ')) != '' ? searchTextArray.push('('+someWord.join(' OR ')+')') : '';
    jQuery.trim(noWordArray.join(' ')) != '' ? searchTextArray.push(noWordArray.join(' ')) : '';
    jQuery.trim(language) != '' ? searchTextArray.push(language) : '';
    jQuery.trim(type) != '' ? searchTextArray.push(type) : '';
    jQuery.trim(clazz) != '' ? searchTextArray.push(clazz) : '';
    jQuery.trim(discipline) != '' ? searchTextArray.push(discipline) : '';
    jQuery.trim(source) != '' ? searchTextArray.push(source) : '';

    element.val(jQuery.trim(searchTextArray.join(' ')));

    if (element.val() == '') {
        element.val('Ketik yang dicari, kemudian tekan tombol enter');
        element.removeAttr('style');
    } else {
        element.attr('style', 'color:black;');
    }

}