/**
 *
 */

-- Kelas kata

update lexical_class set lex_class_name = 'Nomina (kata benda)', sort_order = 1 where lex_class = 'n';
update lexical_class set lex_class_name = 'Verba (kata kerja)', sort_order = 2 where lex_class = 'v';
insert into lexical_class (lex_class, lex_class_name, sort_order) values ('adj', 'Adjektiva (kata sifat)', 3);
insert into lexical_class (lex_class, lex_class_name, sort_order) values ('adv', 'Adverbia (kata keterangan)', 4);
insert into lexical_class (lex_class, lex_class_name, sort_order) values ('pron', 'Pronomina (kata ganti)', 5);
insert into lexical_class (lex_class, lex_class_name, sort_order) values ('num', 'Numeralia (kata bilangan)', 6);
insert into lexical_class (lex_class, lex_class_name, sort_order) values ('l', 'Lain-lain (preposisi, artikula, dll)', 7);

-- Bidang

insert into discipline (discipline, discipline_name) values ('dok', 'Kedokteran');
insert into discipline (discipline, discipline_name) values ('huk', 'Hukum');
insert into discipline (discipline, discipline_name) values ('mgmt', 'Manajemen');

drop table if exists ref_source;

/*==============================================================*/
/* Table: ref_source                                            */
/*==============================================================*/
create table ref_source
(
   ref_source           varchar(16) not null,
   ref_source_name      varchar(255) not null,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (ref_source)
)
comment = "Reference source";

-- Additional column for translation

alter table translation add ref_source varchar(16) after lang;
alter table translation add wpid varchar(255) after ref_source;
alter table translation add wpen varchar(255) after wpid;

-- Reference source

insert into ref_source (ref_source, ref_source_name) values ('Pusba', 'Pusat Bahasa');
insert into ref_source (ref_source, ref_source_name) values ('SM', 'Sofia Mansoor');

update translation set ref_source = 'Pusba' where isnull(ref_source);
