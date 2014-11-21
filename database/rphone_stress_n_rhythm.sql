-- --------------------------------------------------------
-- 
-- Структура таблицы `long_words`
-- Используется в упражнении: Stress and rhythm / Long words / *
--

drop table if exists `long_words`;
create table if not exists `long_words` (
	`id` int primary key auto_increment,
	`word` varchar(64),
	`pattern` varchar(64),
	`speechpart` tinyint,
	`stress_count` tinyint);
	
insert into `long_words` (`word`, `pattern`, `speechpart`, `stress_count`) values
('university', '_u|ni^ver|si|ty', 1, 2),
('characteristic', '_char|ac|te^ris|tic', 3, 2),
('constitution', '_con|sti^tu|tion', 1, 2),
('information', '_in|for^ma|tion', 1, 2),
('multiplication', '_mul|ti|pli^ca|tion', 1, 2),
('punctuation', '_punc|tu^a|tion', 1, 2),
('conversation', '_con|ver^sa|tion', 1, 2),
('investigation', '|in_ves|ti^ga|tion', 1, 2),
('continuation', '|con_tin|u^a|tion', 1, 2),
('interpretation', '|in_ter|pre^ta|tion', 1, 2),
('accomodation', '|ac_com|o^da|tion', 1, 2),
('consideration', '|con_sid|e^ra|tion', 1, 2),
('responsibility', '|re_spon|si^bil|i|ty', 1, 2),
('familiarity', '|fa_mil|i^ar|i|ty', 1, 2),
('incredibility', '|in_cred|i^bil|i|ty', 1, 2),
('availability', '|a_vai|la^bil|i|ty', 1, 2),
('personification', '|per_son|i|fi^ca|tion', 1, 2),
('communication', '|com_mu|ni^ca|tion', 1, 2),
('education', '_ed|u^ca|tion', 1, 2),
('imagination', '|i_mag|i^na|tion', 1, 2),
('reservation', '_res|er^va|tion', 1, 2),
('appreciation', '|ap_pre|ci^a|tion', 1, 2),
('contradiction', '_con|tra^dic|tion', 1, 2),
('economics', '_e|co^nom|ics', 1, 2),
('hostility', '_hos^til|i|ty', 1, 2),
('identity', '_i^den|ti|ty', 1, 2),
('academic', '_ac|a^dem|ic', 3, 2),
('capitalistic', '_cap|i|ta^lis|tic', 3, 2),
('experimental', '|ex_per|i^men|tal', 3, 2),
('comprehensible', '_com|pre^hen|sible', 3, 2),
('cosmopolitan', '_cos|mo^pol|i|tan', 3, 2),
('identify', '_i^den|ti_fy', 2, 3),
('intensify', '|in^ten|si_fy', 2, 2),
('intoxicate', '|in^tox|i_cate', 2, 2),
('accumulate', '|ac^cu|mu_late', 2, 2),
('commercialise', '|com^mer|cia_lise', 2, 2),
('apologise', '|a^pol|o_gise', 2, 2),
('clarify', '^clar|i_fy', 2, 2),
('criticise', '^crit|i_cize', 2, 2),
('congratulate', '|con^grat|u_late', 2, 2),
('purify', '^pu|ri_fy', 2, 2),
('verify', '^ver|i_fy', 2, 2),
('globalise', '^glo|ba_lise', 2, 2),
('terrify', '^terr|i_fy', 2, 2),
('organise', '^or|ga_nise', 2, 2),
('decorate', '^dec|o_rate', 2, 2),
('emulate', '^em|u_late', 2, 2),
('stimulate', '^stim|u_late', 2, 2),
('crucify', '^cru|ci_fy', 2, 2),
('capitalise', '^cap|i|ta_lise', 2, 2),
('personalise', '^per|so|na_lise', 2, 2),
('vocalise', '^vo|ca_lise', 2, 2),
('negotiate', '|ne^go|ti_ate', 2, 2),
('communicate', '|co^mmu|ni_cate', 2, 2),
('imitate', '^im|i_tate', 2, 2),
('satisfy', '^sat|is_fy', 2, 2),
('justify', '^jus|ti_fy', 2, 2),
('realise', '^rea_lise', 2, 2),
('discriminate', '|dis^crim|i_nate', 2, 2),
('classify', '^class|i_fy', 2, 2),
('operate', '^op|e_rate', 2, 2),
('appreciate', '|ap^pre|ci_ate', 2, 2),
('exaggerate', '|e^xagg|e_rate', 2, 2),
('recognise', '^rec|og_nise', 2, 2),
('electrify', '|e^lec|tri_fy', 2, 2),
('certify', '^cer|ti_fy', 2, 2),
('memorise', '^mem|o_rise', 2, 2),
('qualify', '^qual|i_fy', 2, 2),
('publicise', '^pub|li_cise', 2, 2);

-- --------------------------------------------------------
-- 
-- Структура таблицы `noun_verb_choose`
-- Используется в упражнении: Stress and rhythm / Noun vs. Verb / Listen & Distinguish
--

drop table if exists `noun_verb_choose`;
create table `noun_verb_choose` (
`id` int primary key auto_increment, 
`word` varchar(64), 
`transcription` varchar(64), 
`stress_count` tinyint, 
`speechpart` tinyint, 
`audio` varchar(128));

insert into `noun_verb_choose` (`word`, `transcription`, `stress_count`, `audio`, `speechpart`) values
("present", "|pre^sent", 1, "present_v.mp3", 2),
("present", "^pres|ent", 1, "present.mp3", 1),
("import", "|im^port", 1, "import_v.mp3", 2),
("import", "^im_port", 2, "present.mp3", 1),
("export", "|ex^port", 1, "export_v.mp3", 2),
("export", "^ex_port", 2, "export.mp3", 1),
("extract", "|ex^tract", 1, "extract_v.mp3", 2),
("extract", "^ex_tract", 2, "extract.mp3", 1),
("conflict", "|con^flict", 1, "conflict_v.mp3", 2),
("conflict", "^con|flict", 1, "conflict.mp3", 1),
("increase", "|in^crease", 1, "increase_v.mp3", 2),
("increase", "^in_crease", 2, "increase.mp3", 1),
("decrease", "|de^crease", 1, "decrease_v.mp3", 2),
("decrease", "^de_crease", 2, "decrease.mp3", 1),
("object", "|ob^ject", 1, "object_v.mp3", 2),
("object", "^ob|ject", 1, "object.mp3", 1),
("abstract", "_ab^stract", 2, "abstract_v.mp3", 2),
("abstract", "^ab_stract", 2, "abstract.mp3", 1),
("subject", "|sub^ject", 1, "subject_v.mp3", 2),
("subject", "^sub_ject", 2, "subject.mp3", 1),
("project", "|pro^ject", 1, "project_v.mp3", 2),
("project", "^proj_ect", 2, "project.mp3", 1),
("record", "|re^cord", 1, "record_v.mp3", 2),
("record", "^re_cord", 2, "record.mp3", 1),
("conduct", "|con^duct", 1, "conduct_v.mp3", 2),
("conduct", "^con|duct", 1, "conduct.mp3", 1),
("addict", "|a^ddict", 1, "addict_v.mp3", 2),
("addict", "^add|ict", 1, "addict.mp3", 1),
("protest", "|pro^test", 1, "protest_v.mp3", 2),
("protest", "^pro_test", 2, "protest.mp3", 1),
("progress", "|pro^gress", 1, "progress_v.mp3", 2),
("progress", "^prog|ress", 1, "progress.mp3", 1),
("permit", "|per^mit", 1, "permit_v.mp3", 2),
("permit", "^per|mit", 1, "permit.mp3", 1),
("desert", "|de^sert", 1, "desert_v.mp3", 2),
("desert", "^des|ert", 1, "desert.mp3", 1),
("contract", "|con^tract", 1, "contract_v.mp3", 2),
("contract", "^con_tract", 2, "contract.mp3", 1),
("convict", "|con^vict", 1, "convict_v.mp3", 2),
("convict", "^con|vict", 1, "convict.mp3", 1),
("defect", "|de^fect", 1, "defect_v.mp3", 2),
("defect", "^de_fect", 2, "defect.mp3", 1),
("insult", "|in^sult", 1, "insult_v.mp3", 2),
("insult", "^in|sult", 1, "insult.mp3", 1),
("produce", "|pro^duce", 1, "produce_v.mp3", 2),
("produce", "^prod_uce", 2, "produce.mp3", 1),
("rebel", "|re^bel", 1, "rebel_v.mp3", 2),
("rebel", "^reb|el", 2, "rebel.mp3", 1),
("suspect", "|sus^pect", 1, "suspect_v.mp3", 2),
("suspect", "^sus_pect", 2, "suspect.mp3", 1),
("transfer", "_trans^fer", 2, "transfer_v.mp3", 2),
("transfer", "^trans_fer", 2, "transfer.mp3", 1);

-- --------------------------------------------------------
-- 
-- Структура таблицы `noun_verb_stress`
-- Используется в упражнении: Stress and rhythm / Noun vs. Verb / Mark the stress
--

drop table if exists `noun_verb_stress`;
create table `noun_verb_stress` (
`id` int primary key auto_increment, 
`sentence` varchar(256), 
`speechpart` tinyint, 
`audio` varchar(128));

insert into `noun_verb_stress` (`speechpart`, `sentence`, `audio`) values
(1, "I have a small ^pres|ent for you.", "present.mp3"),
(2, "I would like to |pre^sent my wife to you.", "present_v.mp3" ),
(1, "Would you like to take part in this ^proj|ect?", "project.mp3"),
(2, "It is typical to |pro^ject our own emotions to others.", "project_v.mp3"),
(1, "Please find the ^sub|ject in the sentence.", "subject.mp3"),
(2, "You should |sub^ject the idea to scientific scrutiny.", "subject_v.mp3"),
(1, "Now we have a new ^ob|ject to study.", "object.mp3"),
(2, "Are you going to |ob^ject to my plan?", "object_v.mp3"),
(2, "Canada doesn't yet |ex^port grain to Peru.", "export_v.mp3"),
(1, "^Ex|port plays a crucial role in the economy of the country.", "export.mp3"),
(1, "^Im|port should be strictly regulated.", "import.mp3"),
(2, "We need to |im^port a number of products.", "import_v.mp3"),
(1, "I have to write an ^abst|ract of the article.", "abstract.mp3"),
(2, "Sometimes it is better to |abst^ract from the problem.", "abstract_v.mp3"),
(1, "Vanilla ^ext|ract would have made the cake even more delicious.", "extract.mp3"),
(2, "I'm afraid the only way is to |ext^ract the tooth.", "extract_v.mp3"),
(2, "We need to |inc^rease the productivity.", "increase_v.mp3"),
(1, "We expect a considerable ^inc|rease in sales.", "increase.mp3"),
(2, "The labour goverment is expected to |dec^rease tax rates.", "decrease_v.mp3"),
(1, "We faced a steady ^dec|rease in consumer demand.", "decrease.mp3");
