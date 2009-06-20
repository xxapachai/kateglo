-- merge derived word

insert into relation_type (rel_type, rel_type_name) values ('d', 'Turunan');
delete from relation_type where rel_type in ('f', 'c');
update relation set rel_type = 'd' where rel_type in ('f', 'c');

insert into phrase_type (phrase_type, phrase_type_name, sort_order) values ('d', 'Kata turunan', 2);
delete from phrase_type where phrase_type in ('f', 'c');
update phrase set phrase_type = 'd' where phrase_type in ('f', 'c');

-- new type: affix

insert into phrase_type (phrase_type, phrase_type_name, sort_order) values ('a', 'Imbuhan', 3);
update phrase set phrase_type = 'a' where lex_class = 'l' and phrase regexp '-';
update phrase set phrase_type = 'd' where phrase regexp '[[:alnum:]]-[[:alnum:]]';

-- delete error

delete from phrase where phrase = 'ber- (be-';
delete from definition where phrase = 'ber- (be-';
delete from relation where root_phrase = 'ber- (be-';
delete from relation where related_phrase = 'ber- (be-';

-- find error

SELECT * FROM `phrase` WHERE phrase regexp '[^[:alpha:] -]';

SELECT b.*, a.actual_phrase from phrase a, definition b where a.phrase = b.phrase and not isnull(a.actual_phrase) and a.actual_phrase <> b.see;