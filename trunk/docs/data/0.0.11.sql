alter table lexical_class add lex_class_ref varchar(255) after lex_class_name;

update lexical_class set lex_class_ref = 'nomina' where lex_class = 'n';
update lexical_class set lex_class_ref = 'verba' where lex_class = 'v';
update lexical_class set lex_class_ref = 'adjektiva' where lex_class = 'adj';
update lexical_class set lex_class_ref = 'adverbia' where lex_class = 'adv';
update lexical_class set lex_class_ref = 'numeralia' where lex_class = 'num';
update lexical_class set lex_class_ref = 'pronomina' where lex_class = 'pron';