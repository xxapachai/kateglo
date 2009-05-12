/**
 *
 */

drop table if exists sys_user;

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

alter table definition modify updater varchar(32);
alter table derivation modify updater varchar(32);
alter table derivation_type modify updater varchar(32);
alter table discipline modify updater varchar(32);
alter table lexical_class modify updater varchar(32);
alter table phrase modify updater varchar(32);
alter table relation modify updater varchar(32);
alter table relation_type modify updater varchar(32);

drop table if exists sys_session;

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

drop table if exists sys_action;

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
