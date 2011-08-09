$(document).ready(function() {

	var detailSearchOptions = {callback: advancedSearch, wait:1, highlight:false, captureLength: -1};

	if ($('.advance').length > 0) {
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
		$('input[name=submitAdvancedSearch]').click(function() {
			$('form[name=search]').submit();
			return false;
		})
	}

	if ($('#accordion').length > 0) {
		// Accordion
		$("#accordion").accordion({ header: "h3" });

		//hover states on the static widgets
		$('#dialog_link, ul#icons li').hover(
				function() {
					$(this).addClass('ui-state-hover');
				},
				function() {
					$(this).removeClass('ui-state-hover');
				}
		);
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
		language = ($(this).val() == 'all') ? '' : 'language:"' + $(this).val() + '"';
	});

	$('select[name=type] option:selected').each(function() {
		type = ($(this).val() == 'all') ? '' : 'type:"' + $(this).val() + '"';
	});

	$('select[name=class] option:selected').each(function() {
		clazz = ($(this).val() == 'all') ? '' : 'class:"' + $(this).val() + '"';
	});

	$('select[name=discipline] option:selected').each(function() {
		discipline = ($(this).val() == 'all') ? '' : 'discipline:"' + $(this).val() + '"';
	})

	$('select[name=source] option:selected').each(function() {
		source = ($(this).val() == 'all') ? '' : 'sourceCategory:"' + $(this).val() + '"';
	})

	$('select[name=rows] option:selected').each(function() {
		rows = $(this).val();
	});

	$('select[name=siteArea] option:selected').each(function() {
		formActionURL = $(this).val();
	});

	if (rows != 10) {
		form.attr('action', formActionURL + '?' + 'rows=' + rows);
	} else {
		form.attr('action', formActionURL);
	}

	var searchTextArray = new Array();

	jQuery.trim(allWord) != '' ? searchTextArray.push(allWord) : '';
	jQuery.trim(exactWord) != '' ? searchTextArray.push(exactWord) : '';
	jQuery.trim(someWord.join(' OR ')) != '' ? searchTextArray.push('(' + someWord.join(' OR ') + ')') : '';
	jQuery.trim(noWordArray.join(' ')) != '' ? searchTextArray.push(noWordArray.join(' ')) : '';
	jQuery.trim(language) != '' ? searchTextArray.push(language) : '';
	jQuery.trim(type) != '' ? searchTextArray.push(type) : '';
	jQuery.trim(clazz) != '' ? searchTextArray.push(clazz) : '';
	jQuery.trim(discipline) != '' ? searchTextArray.push(discipline) : '';
	jQuery.trim(source) != '' ? searchTextArray.push(source) : '';

	element.val(jQuery.trim(searchTextArray.join(' ')));


}