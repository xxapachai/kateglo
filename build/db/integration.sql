insert into kateglox2.entry (entry_name) select phrase from phrase order by phrase

-------------------------------------------------------------------------------------

insert into kateglox2.meaning (meaning_entry_id) select entry_id from kateglox2.entry order by entry_id

-------------------------------------------------------------------------------------

INSERT INTO kateglox2.class (class_name) VALUES
('Nomina'),
('Numeralia'),
('Pronomina'),
('Verba'),
('Adjektiva'),
('Adverbia'),
('Preposisi'),
('Konjungtor'),
('Interjeksi'),
('Partikula Penegas'),
('Awalan'),
('Sisipan'),
('Akhiran'),
('Imbuhan Gabungan');

---------------------------------------------------------------------------------


INSERT INTO kateglox2.class_category (class_category_name) VALUES
('Nomina'),
('Kata Tugas'),
('Imbuhan');

----------------------------------------------------------------------------------

INSERT INTO kateglox2.rel_class_category (rel_class_id, rel_class_category_id) VALUES
(1, 1),
(2, 1),
(3, 1),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 3),
(12, 3),
(13, 3),
(14, 3);

--------------------------------------------------------------------------------------

INSERT INTO kateglox2.source_category (source_category_name) VALUES
('Pusat Bahasa'),
('Sofia Mansoor'),
('Bahtera'),
('Wikipedia'),
('Daisy Subakti'),
('Kamus Besar Bahasa Indonesia'),
('Publik'),
('Kateglo');

--------------------------------------------------------------------------------------

INSERT INTO kateglox2.type (type_name) VALUES
('Alfabet'),
('Bentuk terikat'),
('Kata dasar mandiri'),
('Kata dasar terikat'),
('Kata berimbuhan'),
('Kata ulang'),
('Gabungan kata'),
('Peribahasa'),
('Idiom'),
('Akronim'),
('Singkatan');

---------------------------------------------------------------------------------------

INSERT INTO kateglox2.type_category (type_category_name) VALUES
('Kata dasar'),
('Kata turunan');

---------------------------------------------------------------------------------------

INSERT INTO kateglox2.rel_type_category (rel_type_id, rel_type_category_id) VALUES
(3, 1),
(4, 1),
(5, 2),
(6, 2);

------------------------------------------------------------------------------------------

insert into kateglox2.rel_meaning_type (rel_type_id, rel_meaning_id) select 3, meaning_entry_id from phrase 
left join kateglox2.entry on entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = entry_id
where phrase_type = 'r' order by phrase

-----------------------------------------------------------------------------------------

insert into kateglox2.definition (definition_meaning_id, definition_text) select meaning_id, def_text 
from phrase
left join kateglox2.entry on entry_name = phrase.phrase
left join kateglox2.meaning on entry_id = meaning_entry_id
left join definition on phrase.phrase = definition.phrase
where def_text is not null and meaning_id is not null
order by phrase.phrase

--------------------------------------------------------------------------------------------

insert into kateglox2.rel_definition_class (rel_definition_id, rel_class_id) 
select definition_id, 
	CASE
		WHEN lex_class = 'n' then 1
		WHEN lex_class = 'v' then 4
		WHEN lex_class = 'adj' then 5
		WHEN lex_class = 'adv' then 6
		WHEN lex_class = 'pron' then 3
		WHEN lex_class = 'num' then 2
		WHEN lex_class = 'pre' then 7
		WHEN lex_class = 'i' then 9
		WHEN lex_class = 'k' then 8
	END as class
from (select 
	def.definition_id,
	CASE  
		WHEN definition.lex_class is null THEN phrase.lex_class 
		ELSE definition.lex_class 
	END as lex_class 
FROM definition 
	LEFT JOIN phrase ON definition.phrase = phrase.phrase 
	LEFT JOIN kateglox2.entry ON  definition.phrase = entry.entry_name
	LEFT JOIN kateglox2.meaning ON entry.entry_id = meaning.meaning_entry_id
	LEFT JOIN kateglox2.definition def on definition.def_text = def.definition_text AND def.definition_meaning_id = meaning.meaning_id 
HAVING lex_class != 'bt' and lex_class != 'l'
ORDER by entry.entry_name, definition.def_num) temp
HAVING class is not null
ON DUPLICATE KEY UPDATE rel_class_id=VALUES(rel_class_id);

-----------------------------------------------------------------

insert into kateglox2.sample (sample_definition_id, sample_text) 
select 
	def.definition_id,
	REPLACE(REPLACE(definition.sample, '--', phrase.phrase), '~', phrase.phrase) as sample 
FROM definition 
	LEFT JOIN phrase ON definition.phrase = phrase.phrase 
	LEFT JOIN kateglox2.entry ON  definition.phrase = entry.entry_name
	LEFT JOIN kateglox2.meaning ON entry.entry_id = meaning.meaning_entry_id
	LEFT JOIN kateglox2.definition def on definition.def_text = def.definition_text AND def.definition_meaning_id = meaning.meaning_id 
WHERE sample is not null
ORDER by entry.entry_name, definition.def_num

--------------------------------------------------------------------

insert into kateglox2.discipline (discipline_name) select distinct name from
((select abbrev as abbr, label as name from sys_abbrev where type in ('discipline', 'religion')  order by label)
union
(select discipline as abbr, discipline_name as name from discipline order by discipline_name)) as temp where name not like '%*%' order by name

--------------------------------------------------------------------------

insert into kateglox2.rel_definition_discipline (rel_definition_id, rel_discipline_id) 
select 
	def.definition_id,
	disc.discipline_id
FROM definition 
	LEFT JOIN phrase ON definition.phrase = phrase.phrase 
	LEFT JOIN kateglox2.entry ON  definition.phrase = entry.entry_name
	LEFT JOIN kateglox2.meaning ON entry.entry_id = meaning.meaning_entry_id
	LEFT JOIN kateglox2.definition def on definition.def_text = def.definition_text AND def.definition_meaning_id = meaning.meaning_id 
	LEFT JOIN ((select abbrev as abbr, label as name from sys_abbrev where type in ('discipline', 'religion') order by label) union (select discipline as abbr, discipline_name as name from discipline order by discipline_name)) as temp on definition.discipline = temp.abbr
	LEFT JOIN kateglox2.discipline disc on disc.discipline_name = temp.name
WHERE disc.discipline_id is not null and def.definition_id is not null and definition.discipline is not null and definition.discipline not like '%,%' and definition.discipline not like '%*%'
Group by def.definition_id, disc.discipline_id
ORDER by entry.entry_name, definition.def_num

-------------------------------------------------------

insert into kateglox2.antonym (antonym_meaning_id, antonym_antonym_id) select root_meaning_id, related_meaning_id from 
((select root_meaning.meaning_id as root_meaning_id, root_phrase as root, related_phrase as related, related_meaning.meaning_id as related_meaning_id, rel_type
from relation
left join kateglox2.entry root_entry on root_entry.entry_name = root_phrase
left join kateglox2.meaning root_meaning on root_meaning.meaning_entry_id = root_entry.entry_id
left join kateglox2.entry related_entry on related_entry.entry_name = related_phrase
left join kateglox2.meaning related_meaning on related_meaning.meaning_entry_id = related_entry.entry_id
where rel_type = 'a'
order by root_phrase)
union
(select related_meaning.meaning_id as root_meaning_id, related_phrase as root, root_phrase as related, root_meaning.meaning_id as related_meaning_id, rel_type
from relation
left join kateglox2.entry root_entry on root_entry.entry_name = root_phrase
left join kateglox2.meaning root_meaning on root_meaning.meaning_entry_id = root_entry.entry_id
left join kateglox2.entry related_entry on related_entry.entry_name = related_phrase
left join kateglox2.meaning related_meaning on related_meaning.meaning_entry_id = related_entry.entry_id
where rel_type = 'a'
order by root_phrase)) as temp
where root_meaning_id is not null and related_meaning_id is not null
order by root_meaning_id

-------------------------------------------------------

insert into kateglox2.synonym (synonym_meaning_id, synonym_synonym_id) select root_meaning_id, related_meaning_id from 
((select root_meaning.meaning_id as root_meaning_id, root_phrase as root, related_phrase as related, related_meaning.meaning_id as related_meaning_id, rel_type
from relation
left join kateglox2.entry root_entry on root_entry.entry_name = root_phrase
left join kateglox2.meaning root_meaning on root_meaning.meaning_entry_id = root_entry.entry_id
left join kateglox2.entry related_entry on related_entry.entry_name = related_phrase
left join kateglox2.meaning related_meaning on related_meaning.meaning_entry_id = related_entry.entry_id
where rel_type = 'a'
order by root_phrase)
union
(select related_meaning.meaning_id as root_meaning_id, related_phrase as root, root_phrase as related, root_meaning.meaning_id as related_meaning_id, rel_type
from relation
left join kateglox2.entry root_entry on root_entry.entry_name = root_phrase
left join kateglox2.meaning root_meaning on root_meaning.meaning_entry_id = root_entry.entry_id
left join kateglox2.entry related_entry on related_entry.entry_name = related_phrase
left join kateglox2.meaning related_meaning on related_meaning.meaning_entry_id = related_entry.entry_id
where rel_type = 'a'
order by root_phrase)) as temp
where root_meaning_id is not null and related_meaning_id is not null
order by root_meaning_id

---------------------------------------------------------

insert into kateglox2.relation (relation_meaning_id, relation_relation_id)
select root_meaning_id, related_meaning_id from 
((select root_meaning.meaning_id as root_meaning_id, root_phrase as root, related_phrase as related, related_meaning.meaning_id as related_meaning_id, rel_type
from relation
left join kateglox2.entry root_entry on root_entry.entry_name = root_phrase
left join kateglox2.meaning root_meaning on root_meaning.meaning_entry_id = root_entry.entry_id
left join kateglox2.entry related_entry on related_entry.entry_name = related_phrase
left join kateglox2.meaning related_meaning on related_meaning.meaning_entry_id = related_entry.entry_id
where rel_type in ('r', 'd', 'c')
order by root_phrase)
union
(select related_meaning.meaning_id as root_meaning_id, related_phrase as root, root_phrase as related, root_meaning.meaning_id as related_meaning_id, rel_type
from relation
left join kateglox2.entry root_entry on root_entry.entry_name = root_phrase
left join kateglox2.meaning root_meaning on root_meaning.meaning_entry_id = root_entry.entry_id
left join kateglox2.entry related_entry on related_entry.entry_name = related_phrase
left join kateglox2.meaning related_meaning on related_meaning.meaning_entry_id = related_entry.entry_id
where rel_type in ('r', 'd', 'c')
order by root_phrase)) as temp
where root_meaning_id is not null and related_meaning_id is not null
order by root_meaning_id

---------------------------------------------------------------

insert into kateglox2.syllabel (syllabel_meaning_id, syllabel_text) select meaning_id, syllabel_name 
from kateglox.syllabel
left join kateglox.lemma on lemma_id = syllabel_lemma_id
left join kateglox2.entry on lemma_name = entry_name
left join kateglox2.meaning on entry_id = meaning_entry_id
where meaning_id is not null

----------------------------------------------------------------------

insert into kateglox2.misspelled (misspelled_meaning_id, misspelled_misspelled_id) select orig.meaning_id, miss.meaning_id 
from phrase 
	left join kateglox2.entry miss_ent on miss_ent.entry_name = phrase
	left join kateglox2.meaning miss on miss_ent.entry_id = miss.meaning_entry_id
	left join kateglox2.entry orig_ent on orig_ent.entry_name = actual_phrase
	left join kateglox2.meaning orig on orig_ent.entry_id = orig.meaning_entry_id
where actual_phrase is not null and orig.meaning_id is not null and miss.meaning_id is not null
order by actual_phrase;

----------------------------------------------------------------------------

insert into kateglox2.source (source_entry_id, source_category_id, source_text) select entry_id, 6, content
from sys_cache
left join kateglox2.entry on phrase = entry_name
where content is not null
order by phrase

--------------------------------------------------------------------------------

insert into kateglox2.definition (definition_meaning_id, definition_text) select meaning_id, meaning
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is null
group by proverb
order by phrase

-------------------------------------------------------------------------------

insert into kateglox2.meaning (meaning_entry_id) select entry_id
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is not null
group by proverb
order by phrase

-------------------------------------------------------------------------------

insert into kateglox2.definition (definition_meaning_id, definition_text) select ( select max(meaning_id) from kateglox2.meaning where meaning_entry_id = entry_id) new_mean, meaning from ( 
select entry_id, meaning
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is not null
group by proverb
order by phrase) temp

---------------------------------------------------------------------------------

insert into kateglox2.rel_meaning_type (rel_type_id, rel_meaning_id) select 8, ( select max(meaning_id) from kateglox2.meaning where meaning_entry_id = entry_id) new_mean from ( 
select entry_id, meaning
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is not null
group by proverb
order by phrase) temp

--------------------------------------------------------------------------------

insert into kateglox2.relation (relation_meaning_id, relation_relation_id) select * from (( select ( select max(meaning_id) from kateglox2.meaning where meaning_entry_id = temp.entry_id) new_mean, meaning_id from ( 
select entry_id, phrase
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is not null
group by proverb
order by phrase) temp
left join kateglox2.entry ent on ent.entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = ent.entry_id)

union

( select meaning_id, ( select max(meaning_id) from kateglox2.meaning where meaning_entry_id = temp.entry_id) new_mean from ( 
select entry_id, phrase
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is not null
group by proverb
order by phrase) temp
left join kateglox2.entry ent on ent.entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = ent.entry_id)) temp2

----------------------------------------------------------------------------------

insert into kateglox2.relation (relation_meaning_id, relation_relation_id) (select mean_id, meaning_id from (select meaning_id as mean_id, phrase
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is null
group by proverb
order by phrase) temp
left join kateglox2.entry on entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = entry_id)

union

(select meaning_id, mean_id from (select meaning_id as mean_id, phrase
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is null
group by proverb
order by phrase) temp
left join kateglox2.entry on entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = entry_id)
ON DUPLICATE KEY UPDATE relation_meaning_id=VALUES(relation_meaning_id), relation_relation_id=VALUES(relation_relation_id);

------------------------------------------------------------------------------------------------------

insert into kateglox2.rel_meaning_type (rel_type_id, rel_meaning_id)  select 8, meaning_id
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.rel_meaning_type on rel_meaning_id = meaning_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null  and entry_name is not null and rel_type_id is null
group by proverb
order by phrase

------------------------------------------------------------------------------------------------------

insert into kateglox2.entry (entry_name) select proverb
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
where meaning is not null  and entry_name is null 
group by proverb
order by phrase

------------------------------------------------------------------------------------------

insert into kateglox2.meaning (meaning_entry_id) select entry_id
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
where meaning is not null and meaning_entry_id is null
group by proverb
order by phrase

---------------------------------------------------------------------------------------------

insert into kateglox2.rel_meaning_type (rel_type_id, rel_meaning_id) select 8, meaning_id
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null and definition_id is null
group by proverb
order by phrase

-----------------------------------------------------------------------------------------------

insert into kateglox2.relation (relation_meaning_id, relation_relation_id) 
select *  from ((select new_mean as relation_meaning_id, meaning_id as relation_relation_id from ( select meaning_id as new_mean, phrase
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null and definition_id is null
group by proverb
order by phrase) temp
left join kateglox2.entry on entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = entry_id)

union

(select meaning_id as relation_meaning_id, new_mean as relation_relation_id from ( select meaning_id as new_mean, phrase
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null and definition_id is null
group by proverb
order by phrase) temp
left join kateglox2.entry on entry_name = phrase
left join kateglox2.meaning on meaning_entry_id = entry_id)) plemp
where relation_relation_id is not null and relation_meaning_id is not null
ON DUPLICATE KEY UPDATE relation_meaning_id=VALUES(relation_meaning_id), relation_relation_id=VALUES(relation_relation_id);

-----------------------------------------------------------------------------------------------------------------------------------

insert into kateglox2.definition (definition_meaning_id, definition_text) select meaning_id, meaning
from  proverb 
left join kateglox2.entry on proverb = entry_name
left join kateglox2.meaning on meaning_entry_id = entry_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where meaning is not null and definition_id is null
group by proverb
order by phrase

-------------------------------------------------------------------------------------------------

insert into kateglox2.meaning (meaning_entry_id) select entry_id
from abbr_entry
left join phrase on phrase = abbr_key
left join kateglox2.entry on entry_name = phrase
where phrase is not null

--------------------------------------------------------------------------------------------------------------------------------


insert into kateglox2.definition (definition_meaning_id, definition_text) select (select max( meaning_id ) from kateglox2.meaning where meaning_entry_id = entry_id)  new_mean, Case
	when abbr_id is null then abbr_en
	when abbr_id = '' then abbr_en
	when abbr_id is not null then abbr_id
end as definition
from abbr_entry
left join phrase on phrase = abbr_key
left join kateglox2.entry on entry_name = phrase
where phrase is not null

-----------------------------------------------------------------------------------

insert into kateglox2.rel_meaning_type (rel_meaning_id, rel_type_id) select (select max( meaning_id ) from kateglox2.meaning where meaning_entry_id = entry_id)  new_mean, 10
from abbr_entry
left join phrase on phrase = abbr_key
left join kateglox2.entry on entry_name = phrase
where phrase is not null
group by new_mean

--------------------------------------------------------------------------------------

insert into kateglox2.entry (entry_name) select abbr_key
from abbr_entry
left join phrase on phrase = abbr_key
where phrase is null
group by abbr_key
ON DUPLICATE KEY UPDATE entry_name=entry_name;

---------------------------------------------------------------------------------------------------------

insert into kateglox2.meaning (meaning_entry_id) select entry_id
from abbr_entry
left join kateglox2.entry on convert( abbr_key using utf8 ) = convert( entry_name using utf8)
left join kateglox2.meaning on entry_id = meaning_entry_id
where meaning_id is null
group by abbr_key
ON DUPLICATE KEY UPDATE meaning_entry_id=meaning_entry_id;

-----------------------------------------------------------------------------------------------------------

insert into kateglox2.rel_meaning_type (rel_meaning_id, rel_type_id) select meaning_id, 10
from abbr_entry
left join kateglox2.entry on convert( abbr_key using utf8 ) = convert( entry_name using utf8)
left join kateglox2.meaning on entry_id = meaning_entry_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where definition_id is null
group by abbr_key
ON DUPLICATE KEY UPDATE rel_meaning_id=rel_meaning_id, rel_type_id=rel_type_id;

--------------------------------------------------------------------------------------------------------------

insert into kateglox2.definition (definition_meaning_id, definition_text) select meaning_id, 
Case
	when abbr_id is null then abbr_en
	when abbr_id = '' then abbr_en
	when abbr_id is not null then abbr_id
end as definition
from abbr_entry
left join kateglox2.entry on convert( abbr_key using utf8 ) = convert( entry_name using utf8)
left join kateglox2.meaning on entry_id = meaning_entry_id
left join kateglox2.definition on definition_meaning_id = meaning_id
where definition_id is null
group by abbr_key
having definition is not null

-----------------------------------------------------------------------------------------------------------------

INSERT INTO kateglox2.foreign (foreign_name, foreign_language_id) SELECT  REPLACE(REPLACE(REPLACE(convert(original using utf8), '&#257;', '?'), '&#299;', '?'), '&#363;', '?') as neworiginal, 1 from glossary where original != '' group by neworiginal
ON DUPLICATE KEY UPDATE foreign_name=foreign_name, foreign_language_id=foreign_language_id

----------------------------------------------------------------------------------------------------------------

insert into kateglox2.entry (entry_name) select REPLACE(REPLACE(REPLACE(convert(phrase using utf8), '&#257;', '?'), '&#299;', '?'), '&#363;', '?') as newphrase from glossary 
ON DUPLICATE KEY UPDATE entry_name=entry_name

------------------------------------------------------------------------------------------------------------------


CREATE TABLE IF NOT EXISTS `testa` (
  `phra` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `orig` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disci` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  KEY `phra` (`phra`),
  KEY `orig` (`orig`),
  KEY `disci` (`disci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `testa`
--
ALTER TABLE `testa`
  ADD CONSTRAINT `testa_ibfk_3` FOREIGN KEY (`orig`) REFERENCES `foreign` (`foreign_name`),
  ADD CONSTRAINT `testa_ibfk_1` FOREIGN KEY (`phra`) REFERENCES `entry` (`entry_name`),
  ADD CONSTRAINT `testa_ibfk_2` FOREIGN KEY (`disci`) REFERENCES `discipline` (`discipline_name`);

----------------------------------------------------------------------------------------------------

INSERT
INTO
	testa 
	(
		phra,
		orig,
		disci) 	SELECT
				REPLACE
					(
					REPLACE
						(
						REPLACE
							(
								CONVERT(phrase USING utf8) , '&#257;', '?'), 
								'&#299;', '?'), '&#363;', '?') phra, 
								REPLACE
									(
									REPLACE
										(
										REPLACE
											(
												CONVERT(original USING utf8), 
												'&#257;', '?'), '&#299;', '?') , 
												'&#363;', '?') orig, 
												REPLACE
													(
													REPLACE
														(
														REPLACE
															(
															REPLACE
																(
																REPLACE
																	(
																	REPLACE
																		(
																		REPLACE
																			(
																				discipline
																				,
																				'agamaislam' 
																				,
																				'Agama Islam' 
																			)
																			,
																			'kedokteranhewan' 
																			,
																			'Kedokteran Hewan' 
																		)
																		,
																		'komunikasimassa' 
																		,
																		'Komunikasi Massa' 
																	)
																	,
																	'minyakgas',
																	'Minyak & Gas' 
																)
																,
																'teknikkimia' ,
																'Teknik Kimia' 
															)
															,
															'teknologiinformasi' 
															,
															'Teknologi Informasi' 
														)
														,
														'*umum*',
														NULL 
													)
													disci 
												FROM
													kateglo.glossary

---------------------------------------------------------------------------------------

INSERT
INTO
	equivalent 
	(
		equivalent_entry_id,
		equivalent_foreign_id) SELECT
									(	SELECT
											entry_id 
										FROM
											entry 
										WHERE
											entry_name = phra) AS entryid,
											(	SELECT
													foreign_id 
												FROM
													`foreign` 
												WHERE
													foreign_name = orig) AS 
													languageid 
														FROM
															testa ON DUPLICATE 
															KEY 
														UPDATE
															equivalent_entry_id=
															equivalent_entry_id,
															equivalent_foreign_id=
															equivalent_foreign_id

----------------------------------------------------------------------------------------------

INSERT
INTO
	rel_equivalent_discipline 
	(
		rel_equivalent_id,
		rel_discipline_id) 	SELECT
								equivalent_id,
								discipline_id 
							FROM
								equivalent 
								LEFT JOIN entry 
								ON entry_id = equivalent_entry_id 
								LEFT JOIN `foreign` 
								ON foreign_id = equivalent_foreign_id 
								LEFT JOIN testa 
								ON phra = entry_name AND
								orig = foreign_name 
								LEFT JOIN discipline 
								ON discipline_name = disci 
							WHERE
								disci IS NOT NULL ON DUPLICATE KEY 
							UPDATE
								rel_equivalent_id= rel_equivalent_id,
								rel_discipline_id= rel_discipline_id

-----------------------------------------------------------------------------------------------

DROP TABLE `testa`

------------------------------------------------------------------------------------------------
