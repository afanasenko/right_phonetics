create database if not exists `rphone`;
use `rphone`;

drop table if exists `users`;
create table `users`(
	id_user int primary key auto_increment, 
	username varchar(64), 
	password varchar(64), 
	access_level int);

insert into `users` (`username`, `password`, `access_level`) values('master', 'zelenka', 1);
insert into `users` (`username`, `password`, `access_level`) values('student', 'pudent', 3);

drop table if exists `units`;
create table `units`(`id_unit` int primary key auto_increment, `name_unit` varchar(64));

insert into `units` (`name_unit`) values
('Syllabication'),
('Stress and rhythm'),
('Reduction'),
('From letter to sound'),
('Allophones');

drop table if exists `lessons`;
create table `lessons`(`id_unit` int, `id_lesson` int, `name_lesson` varchar(64), primary key(`id_unit`, `id_lesson`));

insert into `lessons` values(1, 1, 'Syllable type');
insert into `lessons` values(1, 2, 'Vowel duration');

insert into `lessons` values(2, 1, 'Long words');
insert into `lessons` values(2, 2, 'Noun vs. Verb');
insert into `lessons` values(2, 3, 'Compound nouns');
insert into `lessons` values(2, 4, 'Phrasal verbs and prepositions');
insert into `lessons` values(2, 5, 'Weak forms');
insert into `lessons` values(2, 6, 'Noun vs. Verb');

insert into `lessons` values(3, 1, 'Read-n-say');
insert into `lessons` values(3, 2, 'Listen');

insert into `lessons` values(4, 1, 'Stressed vowels');
insert into `lessons` values(4, 2, 'Consonants');
insert into `lessons` values(4, 3, 'Grammatical suffixes');

insert into `lessons` values(5, 1, 'Palatalized');
insert into `lessons` values(5, 2, 'Aspiration');
insert into `lessons` values(5, 3, 'Various types of plosion');
insert into `lessons` values(5, 4, 'Sonants');

drop table if exists `exercise_types`;
create table `exercise_types`(`id_exertype` int primary key auto_increment, `name_exertype` varchar(32), `description` varchar(128));

insert into `exercise_types` values(1, 'Choose', '');
insert into `exercise_types` values(2, 'Odd one out', '');
insert into `exercise_types` values(3, 'Group', '');
insert into `exercise_types` values(4, 'Build wall', '');
insert into `exercise_types` values(5, 'Maze', '');
insert into `exercise_types` values(6, 'Mark', '');
insert into `exercise_types` values(7, 'Fill the gap', '');
insert into `exercise_types` values(8, 'Custom', '');

drop table if exists `exercises`;
create table `exercises`(`id_unit` int, `id_lesson` int, `id_exercise` int, `id_type` int, `name_exercise` varchar(64), `description` varchar(128), `name_internal` varchar(64), primary key(`id_unit`, `id_lesson`, `id_exercise`));

insert into `exercises` values
(1, 1, 1, 1, '', 'Choose the right syllable pattern for the word.', 'syllable_choose'),
(1, 1, 2, 2, '', 'Find the odd word based on its syllable pattern and drag it out to the bin.', 'syllable_odd'),
(1, 1, 3, 3, '', '', 'syllable_group'),
(1, 1, 4, 5, '', '', 'syllable_maze'),
--
(1, 2, 1, 7, '', '', ''),
(1, 2, 2, 2, '', '', ''),
(1, 2, 3, 5, '', '', ''),
-- ==============================================================================
(2, 1, 1, 1, 'Try to count', 'How many stresses does the word below have?', ''),
(2, 1, 2, 3, 'Choose pattern', 'Group the words according to their stress pattern. Drag them to the right box.', ''),
(2, 1, 3, 2, '', 'Tick the word which has a different stress pattern:', ''),
(2, 1, 4, 6, 'Try to mark', 'Mark the correct stress pattern in the word below:', ''),
--
(2, 2, 1, 8, 'Listen & Distinguish', 'Listen to the recording and choose the correct part of speach.', ''),
(2, 2, 2, 6, 'Mark the stress', 'Put stress signs in the right places.', ''),
--
(2, 3, 1, 1, 'Find a compound noun', 'Which of the following is a compound noun?', ''),
(2, 3, 2, 8, 'Mark stress noun', 'Choose the correct stress pattern for the highlighted part of the sentence in each case.', ''),
--
(2, 4, 1, 1, '', 'Choose the sentence where the highlighted word should be stressed.', ''),
(2, 4, 2, 6, '', 'Check boxes before stressed syllables.', ''),
-- ==============================================================================
(5, 1, 1, 1, '', '', ''),
(5, 1, 2, 4, '', '', ''),
(5, 2, 1, 1, '', '', ''),
(5, 2, 2, 6, '', '', ''),
(5, 2, 3, 4, '', '', ''),
(5, 3, 1, 1, '', '', ''),
(5, 3, 2, 6, '', '', ''),
(5, 4, 1, 1, '"Deafened"', 'Find the word with a "deafened" sonant in the list. The sonant is underlined for you.', 'sonants_deafened'),
(5, 4, 2, 1, 'Forming', 'How many syllables are there in the following word?', 'sonants_forming');

drop table if exists `syllable_patterns`;
create table `syllable_patterns` (`id` int primary key auto_increment, `pattern` varchar(64));

insert into `syllable_patterns` values
(1,'(c)cvc-v'),
(2, '(c)cv-cv');

drop table if exists `syllable_choose`;
create table `syllable_choose` (`id` int primary key auto_increment, `word` varchar(64), `pattern` tinyint);

insert into `syllable_choose` (`word`, `pattern`) values
('mother', 1),
('ladder', 1),
('clever', 1),
('pretty', 1),
('sunny', 1),
('foggy', 1),
('chilly', 1),
('pleasure', 1),
('brother', 1),
('killer', 1),
('richer', 1),
('silly', 1),
('figure', 1),
('lover', 1),
('rally', 1),
('father', 2),
('leader', 2),
('teacher', 2),
('cheaper', 2),
('ruler', 2),
('dealer', 2),
('creamy', 2),
('dreamy', 2),
('greedy', 2),
('garter', 2),
('teaser', 2),
('keeper', 2),
('daughter', 2),
('preacher', 2),
('gloomy', 2),
('reader', 2);

-- --------------------------------------------------------
--
-- Структура таблицы `sonants_deafened`
--

drop table if exists `sonants_deafened`;
CREATE TABLE `sonants_deafened` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deafened` tinyint(4) DEFAULT NULL,
  `word` varchar(64) DEFAULT NULL,
  `transcription` varchar(64) DEFAULT NULL,
  `sound` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- Дамп данных таблицы `sonants_deafened`
--

INSERT INTO `sonants_deafened` (`id`, `deafened`, `word`, `transcription`, `sound`) VALUES
(1, 1, 'pray', 'p[r]eɪ', NULL),
(2, 0, 'rare', '[r]eə', NULL),
(3, 0, 'yawn', '[j]ɔːn', NULL),
(4, 1, 'please', 'p[l]iːz', NULL),
(5, 0, 'wind', '[w]ɪnd', NULL),
(6, 0, 'lack', '[l]æk', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `sonants_forming`
--
drop table if exists `sonants_forming`;
CREATE TABLE `sonants_forming` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(64) NOT NULL,
  `transcription` varchar(64) NOT NULL,
  `sound` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- Дамп данных таблицы `sonants_forming`
--

INSERT INTO `sonants_forming` (`id`, `word`, `transcription`, `sound`) VALUES
(3, 'suddenly', 'ˈsʌd|ən|li', ''),
(4, 'garden', 'ˈgɑː|dn', ''),
(5, 'medal', 'med|l', ''),
(6, 'kitten', 'ˈkɪt|n', '');

drop table if exists `cheers`;
create table `cheers`(`id_cheers` int, `success` int, `cheers_text` varchar(128));

insert into `cheers` (`success`, `cheers_text`) values
(0, 'Oops! You''ve made a mistake!' ),
(0, 'Don''t answer like that again!' ),
(1, 'Perfect answer!' ),
(1, 'You are correct!' ),
(1, 'Good job!' ),
(2, 'Some of answers are correct.');

drop table if exists `rules`;
create table `rules`(
`id_rule` int primary key auto_increment,
`rule_unit` int, 
`rule_lesson` int, 
`rule_number` int, 
`rule_text` blob);

insert into `rules` (`rule_unit`, `rule_lesson`, `rule_number`, `rule_text`) values
(1,1,1,'The checked vowels (/æ/, /ɒ/, /ɪ/, /e/, /ʊ/, /ʌ/) can be found only in a close syllable (cvc). While the free vowels (diphthongs + tense vowels + /ə/ + /ɪ/, /ʊ/ in unstressed position) can be found both in a close syllable (cvc) and in an open syllable (cv).'),
(1,1,2,'The intense checked vowels (/æ/, /ɒ/, /ɪ/, /e/, /ʊ/, /ʌ/) can be found only in a close syllable (cvc). While the free vowels (diphthongs + tense vowels + /ə/ + /ɪ/, /ʊ/ in unstressed position) can be found both in a close syllable (cvc) and in an open syllable (cv).'),
(1,1,3,'Voice noise consonants in the English language are considered to be weak, consequently, a vowel before such a consonant is a long one. Voiceless noise consonants in the English language are considered to be strong, consequently, a vowel before such a consonant is a short one.'),
(5,4,1,'Sonants /r/, /l/, /w/, /j/ are pronounced with noise if preceded by a strong consonant, i. d. /p/, /t/, /k/.'),
(5,4,2,'Sonants /n/, /l/, /m/ form a separate syllable if they are found in one of the following positions:&lt;br&gt;1) at the end of the word after a noise consonant (/p/, /t/, /k/, /b/, /d/, /g/);&lt;br&gt;2) in the middle of the word after a noise consonant (/p/, /t/, /k/, /b/, /d/, /g/).');
