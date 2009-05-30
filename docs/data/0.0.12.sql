-- buang

update phrase set phrase = replace(phrase, '-', 'buang ') where phrase like '-hamil%';
update relation set related_phrase = replace(related_phrase, '-', 'buang '), rel_type = 'c' where related_phrase like '-hamil%';

-- 2tani

update definition set phrase = 'tani' where phrase = '2tani';
delete from relation where related_phrase = '2tani';
delete from phrase where phrase = '2tani';
