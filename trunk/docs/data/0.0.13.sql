select a.phrase, count(*) from phrase a, definition b where a.phrase = b.phrase group by a.phrase order by 2 desc;

alter table phrase add def_count int not null default 0 after ref_source;

create index phrase on definition (phrase);

update phrase set def_count = 0;
update phrase a
set a.def_count = (SELECT COUNT(b.def_uid) FROM definition b WHERE a.phrase = b.phrase);
