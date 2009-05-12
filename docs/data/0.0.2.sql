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
alter table sys_session modify user_id varchar(32);
