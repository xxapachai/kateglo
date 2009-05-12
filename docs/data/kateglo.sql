drop table if exists definition;

drop table if exists discipline;

drop table if exists language;

drop table if exists lexical_class;

drop table if exists phrase;

drop table if exists relation;

drop table if exists relation_type;

drop table if exists sys_action;

drop table if exists sys_session;

drop table if exists sys_user;

drop table if exists translation;

/*==============================================================*/
/* Table: definition                                            */
/*==============================================================*/
create table definition
(
   def_uid              int not null auto_increment,
   phrase               varchar(255) not null,
   def_num              tinyint not null default 1,
   discipline           varchar(16),
   def_text             varchar(4000) not null,
   sample               varchar(4000),
   updated              datetime,
   updater              varchar(32) not null,
   primary key (def_uid)
);

/*==============================================================*/
/* Table: discipline                                            */
/*==============================================================*/
create table discipline
(
   discipline           varchar(16) not null,
   discipline_name      varchar(255) not null,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (discipline)
);

/*==============================================================*/
/* Table: language                                              */
/*==============================================================*/
create table language
(
   lang                 varchar(16) not null,
   lang_name            varchar(255),
   updated              datetime,
   updater              varchar(32) not null,
   primary key (lang)
);

/*==============================================================*/
/* Table: lexical_class                                         */
/*==============================================================*/
create table lexical_class
(
   lex_class            varchar(16) not null,
   lex_class_name       varchar(255) not null,
   sort_order           tinyint not null default 1,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (lex_class)
);

/*==============================================================*/
/* Table: phrase                                                */
/*==============================================================*/
create table phrase
(
   phrase               varchar(255) not null,
   lex_class            varchar(16) not null,
   pronounciation       varchar(4000),
   etymology            varchar(4000),
   actual_phrase        varchar(255),
   updated              datetime,
   updater              varchar(32) not null,
   primary key (phrase)
);

/*==============================================================*/
/* Table: relation                                              */
/*==============================================================*/
create table relation
(
   rel_uid              int not null auto_increment,
   root_phrase          varchar(255) not null,
   related_phrase       varchar(255) not null,
   rel_type             varchar(16) not null,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (rel_uid)
);

/*==============================================================*/
/* Table: relation_type                                         */
/*==============================================================*/
create table relation_type
(
   rel_type             varchar(16) not null comment 's=synonym, a=antonym, o=other',
   rel_type_name        varchar(255) not null,
   sort_order           tinyint not null default 1,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (rel_type)
);

/*==============================================================*/
/* Table: sys_action                                            */
/*==============================================================*/
create table sys_action
(
   ses_id               varchar(32) not null,
   action_time          datetime not null,
   action_type          varchar(16),
   module               varchar(16),
   description          varchar(4000),
   primary key (action_time, ses_id)
);

/*==============================================================*/
/* Table: sys_session                                           */
/*==============================================================*/
create table sys_session
(
   ses_id               varchar(32) not null,
   ip_address           varchar(16) not null,
   user_id              varchar(32),
   started              datetime,
   ended                datetime,
   primary key (ses_id)
);

/*==============================================================*/
/* Table: sys_user                                              */
/*==============================================================*/
create table sys_user
(
   user_id              varchar(32) not null,
   pass_key             varchar(32) not null,
   full_name            varchar(255),
   last_access          datetime,
   updated              datetime,
   updater              varchar(32) not null,
   primary key (user_id)
);

/*==============================================================*/
/* Table: translation                                           */
/*==============================================================*/
create table translation
(
   tr_uid               int not null auto_increment,
   phrase               varchar(255) not null,
   translation          varchar(255) not null,
   discipline           varchar(16),
   lang                 varchar(16) not null default 'en',
   updated              datetime,
   updater              varchar(32) not null,
   primary key (tr_uid)
);

/*==============================================================*/
/* Index: phrase                                                */
/*==============================================================*/
create index phrase on translation
(
   phrase
);

/*==============================================================*/
/* Index: translation                                           */
/*==============================================================*/
create index translation on translation
(
   translation
);
