alter table phrase add phrase_type varchar(16) not null default 'r' comment 'r=root; f=affix; c=compond' after phrase;

create unique index relation_unique on relation
(
   root_phrase,
   related_phrase,
   rel_type
);

drop table if exists phrase_type;

/*==============================================================*/
/* Table: phrase_type                                           */
/*==============================================================*/
create table phrase_type
(
   phrase_type          varchar(16) not null comment 'r=root; f=affix; c=compond',
   phrase_type_name     varchar(255) not null,
   sort_order           tinyint not null default 1,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (phrase_type)
);

update relation_type set rel_type_name = 'Gabungan' where rel_type = 'c';
