-- buang

update phrase set phrase = replace(phrase, '-', 'buang ') where phrase like '-hamil%';
update relation set related_phrase = replace(related_phrase, '-', 'buang '), rel_type = 'c' where related_phrase like '-hamil%';

-- 2tani

update definition set phrase = 'tani' where phrase = '2tani';
delete from relation where related_phrase = '2tani';
delete from phrase where phrase = '2tani';

-- rename

rename table translation to glossary;
alter table glossary change tr_uid glo_uid int(11) not null auto_increment;
alter table glossary change translation original varchar(255) not null;

-- phrase type

insert into phrase_type (sort_order, phrase_type, phrase_type_name) values
(1, 'r', 'Kata dasar'),
(2, 'f', 'Kata berimbuhan'),
(3, 'c', 'Gabungan kata');