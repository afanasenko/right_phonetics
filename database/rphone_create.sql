create database if not exists `rphone`;
use `rphone`;

-- --------------------------------------------------------
--
-- Структура таблицы `users`
--

drop table if exists `users`;
create table `users`(
	id_user int primary key auto_increment, 
	username varchar(64), 
	password varchar(64), 
	first_name varchar(64), 
	middle_name varchar(64), 
	last_name varchar(64), 
	access_level int);

insert into `users` (`username`, `password`, `access_level`) values('master', 'zelenka', 1);
insert into `users` (`username`, `password`, `access_level`) values('teacher', 'geekygeek', 2);
insert into `users` (`username`, `password`, `access_level`) values('student', 'pudent', 3);
insert into `users` (`username`, `password`, `access_level`) values('guest', 'guest', 4);

-- --------------------------------------------------------
--
-- Структура таблицы `units`
--

drop table if exists `units`;
create table `units`(`id_unit` int primary key auto_increment, `name_unit` varchar(64));

insert into `units` (`name_unit`) values
('Syllabication'),
('Stress and rhythm'),
('Reduction'),
('From letter to sound'),
('Allophones');

-- --------------------------------------------------------
--
-- Структура таблицы `lessons`
--

drop table if exists `lessons`;
create table `lessons`(`id_unit` int, `id_lesson` int, `name_lesson` varchar(64), primary key(`id_unit`, `id_lesson`));

insert into `lessons` values(1, 1, 'Syllable type');
insert into `lessons` values(1, 2, 'Vowel duration');

insert into `lessons` values(2, 1, 'Long words');
insert into `lessons` values(2, 2, 'Noun vs. Verb');
insert into `lessons` values(2, 3, 'Compound nouns');
insert into `lessons` values(2, 4, 'Phrasal verbs and prepositions');
insert into `lessons` values(2, 5, 'Weak forms');

insert into `lessons` values(3, 1, 'Read-n-say');
insert into `lessons` values(3, 2, 'Listen');

insert into `lessons` values(4, 1, 'Stressed vowels');
insert into `lessons` values(4, 2, 'Consonants');
insert into `lessons` values(4, 3, 'Grammatical suffixes');

insert into `lessons` values(5, 1, 'Palatalized');
insert into `lessons` values(5, 2, 'Aspiration');
insert into `lessons` values(5, 3, 'Various types of plosion');
insert into `lessons` values(5, 4, 'Sonants');

-- --------------------------------------------------------
--
-- Структура таблицы `exercises`
--

drop table if exists `exercises`;
create table `exercises`(
`id_unit` int, 
`id_lesson` int, 
`id_exercise` int, 
`name_exercise` varchar(64), 
`description` varchar(255), 
`name_internal` varchar(64), 
primary key(`id_unit`, `id_lesson`, `id_exercise`));

insert into `exercises` values
(1, 1, 1, 'Choose', 'Choose the right syllable pattern for the word.', 'syllable_choose'),
(1, 1, 2, 'Odd one out', 'Find the odd word based on its syllable pattern and drag it out to the bin.', 'syllable_odd'),
(1, 1, 3, 'Group', 'Drag all words to right columns.', 'syllable_group'),
(1, 1, 4, 'Maze', 'Find the way through the maze. You can move only vertically or horizontally, your steps being guided by the same syllable pattern (i. e. cvc-v or cv-cv).', 'syllable_maze'),
--
(1, 2, 1, 'Fill the gap', 'Type a letter or two to form a word with the vowel of the same type (long or short) as in the words given.', 'voweldur_fill'),
(1, 2, 2, 'Odd one out', 'Tick the word that is odd in the list below. Pay attention to the duration of the vowels.', 'voweldur_odd'),
(1, 2, 3, 'Maze', 'Find the way through the maze. You can move only vertically or horizontally, your steps being guided by the same vowel duration (i. e. long or short vowel).', 'voweldur_maze'),
-- ===========================================================================
(2, 1, 1, 'Try to count', 'How many stresses does the word below have?', 'longword_count'),
(2, 1, 2, 'Choose pattern', 'Group the words according to their stress pattern. Drag them to the right box.', 'longword_group'),
(2, 1, 3, 'Tick', 'Tick the word which has a different stress pattern:', 'longword_odd'),
(2, 1, 4, 'Try to mark', 'Mark the correct stress pattern in the word below:', 'longword_mark'),
--
(2, 2, 1, 'Listen & Distinguish', 'Listen to the recording and choose the correct part of speach.', 'nounverb_choose'),
(2, 2, 2, 'Mark the stress', 'Put stress signs in the right places.', 'nounverb_mark'),
--
(2, 3, 1, 'Find a compound noun', 'Which of the following is a compound noun?', ''),
(2, 3, 2, 'Mark stress noun', 'Choose the correct stress pattern for the highlighted part of the sentence in each case.', ''),
--
(2, 4, 1, 'Choose', 'Choose the sentence where the highlighted word should be stressed.', ''),
(2, 4, 2, 'Mark', 'Check boxes before stressed syllables.', ''),
--
(2, 5, 1, 'Weak form vs Strong form', 'In which sentence the highlighted word is used in the strong form?', ''),
(2, 5, 2, 'Match stress patterns', 'Pair the sentences with the same rhythm patterns(oO, oOo, etc.). Take into account both stressed and unstressed syllables.', ''),
-- ===========================================================================
(3, 1, 1, 'Unstressed vowel', 'What vowel should be pronounced in the unstressed(underlined) syllable in the following words?', 'readsay_choose'),
(3, 1, 2, 'Missing vowel', 'What vowel is missing in the transcription of the word provided that the word has only one stress?', 'readsay_missing'),
--
(3, 2, 1, 'Listen', 'Listen to the audio file and choose the vowel pronounced in the underlined syllable.', 'readsay_listen'),
-- ===========================================================================
(5, 1, 1, 'Choose', 'Choose the word where the palatalized allophone of the underlined consonant is pronounced.', 'palat_choose'),
(5, 1, 2, 'Build wall', 'Build a solid wall as high as possible. Each brick should have a word with a palatalized sound. The first step was done for you. You have 30 seconds.', 'palat_wall'),
(5, 2, 1, 'Choose', 'Choose the word where the aspirated allophone of the underlined consonant is pronounced.', 'aspire_choose'),
(5, 2, 2, 'Mark', 'Check all the cases where the sound in the next-to-checkbox position should be articulated with aspiration.', 'aspire_mark'),
(5, 2, 3, 'Build wall', 'Build a solid wall as high as possible. Each brick should have a word with an aspirated sound. The first step was done for you. You have 30 seconds.', 'aspire_wall'),
(5, 3, 1, 'Choose', 'Choose the word where the underlined consonant is articulated with postalveolar plosion.', 'plosion_choose'),
(5, 3, 2, 'Mark', 'Mark all the cases where a sound in the next-to-checkbox position should be articulated with postalveolar plosion.', 'plosion_mark'),
(5, 4, 1, '"Deafened"', 'Find the word with a "deafened" sonant in the list. The sonant is underlined for you.', 'sonants_deafened'),
(5, 4, 2, 'Forming', 'How many syllables are there in the following word?', 'sonants_forming');

-- --------------------------------------------------------
--
-- Структура таблицы `parts_of_speech`
--

drop table if exists `parts_of_speech`;
create table `parts_of_speech`(
	`id` int primary key,
	`speechpart` varchar(64));
	
insert into `parts_of_speech` values
(1,'noun'),
(2,'verb'),
(3,'adjective');

-- --------------------------------------------------------
--
-- Структура таблицы `cheers`
--

drop table if exists `cheers`;
create table `cheers`(`id_cheers` int, `success` int, `cheers_text` varchar(128));

insert into `cheers` (`success`, `cheers_text`) values
(0, 'Oops! You''ve made a mistake!' ),
(0, 'Don''t distress us with such answers again!' ),
(1, 'Perfect answer!' ),
(1, 'You are correct!' ),
(1, 'Good job!' ),
(2, 'Ummm... some of answers are not correct.'),
(2, 'Your answer is partially correct.');

-- --------------------------------------------------------
--
-- Структура таблицы `rules`
--

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
(1,2,1,'Voice noise consonants in the English language are considered to be weak, consequently, a vowel before such a consonant is a long one.'),
(1,2,2,'Voiceless noise consonants in the English language are considered to be strong, consequently, a vowel before such a consonant is a short one.'),
(2,1,1,'Long (three and more syllables) names (nouns and adjectives) of Romance origin have at least two stresses, the second being the primary stress.'),
(2,1,2,'Long (three and more syllables) verbs of Romance origin have two stresses. The secondary stress falls on suffix (-ate, -ise/-ize, -fy), the primary stress - on the third syllable, being counted from the suffix forward to the beginning of the verb.'),
(2,2,1,'Noun/Verb two-syllable "graphic twins" (words of the same visual form) of non-English origin usually have different stress patterns: in verbs the stress, as a rule, falls on the second syllable, whereas in nouns - on the first one. Moreover, such nouns often have secondary stress on the second syllable.'),
(5,4,1,'Sonants /r/, /l/, /w/, /j/ are pronounced with noise if preceded by a strong consonant, i. d. /p/, /t/, /k/.'),
(5,4,2,'Sonants /n/, /l/, /m/ form a separate syllable if they are found in one of the following positions: 1) at the end of the word after a noise consonant (/p/, /t/, /k/, /b/, /d/, /g/); 2) in the middle of the word after a noise consonant (/p/, /t/, /k/, /b/, /d/, /g/).');
