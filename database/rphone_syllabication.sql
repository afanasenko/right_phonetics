create database if not exists `rphone`;
use `rphone`;

-- --------------------------------------------------------
--
-- Структура таблицы `syllable_patterns`
--
drop table if exists `syllable_patterns`;
create table `syllable_patterns` (`id` int primary key auto_increment, `pattern` varchar(64));

insert into `syllable_patterns` values
(1,'(c)cvc-v'),
(2, '(c)cv-cv');

-- --------------------------------------------------------
--
-- Структура таблицы `syllable_choose`
--

drop table if exists `syllable_choose`;
create table `syllable_choose` (`id` int primary key auto_increment, `word` varchar(64), `pattern` tinyint);

insert into `syllable_choose` (`word`, `pattern`) values
('mother', 1), ('ladder', 1), ('clever', 1), ('pretty', 1), ('sunny', 1), ('foggy', 1), ('chilly', 1), ('pleasure', 1),
('brother', 1), ('killer', 1), ('richer', 1), ('silly', 1), ('figure', 1), ('lover', 1), ('rally', 1), ('father', 2),
('leader', 2), ('teacher', 2), ('cheaper', 2), ('ruler', 2), ('dealer', 2), ('creamy', 2), ('dreamy', 2), ('greedy', 2),
('garter', 2), ('teaser', 2), ('keeper', 2), ('daughter', 2), ('preacher', 2), ('gloomy', 2), ('reader', 2);

-- --------------------------------------------------------
--
-- Структура таблицы `vowel_duration`
--

drop table if exists `vowel_duration`;
create table `vowel_duration` (`id` int primary key auto_increment, `word` varchar(64), `long_vowel` tinyint);

insert into `vowel_duration` (`long_vowel`, `word`) values
(0, "bit"), (0, "cap"), (0, "cat"), (0, "pack"), (0, "pet"), (0, "foot"), (0, "book"), (0, "look"), (0, "brick"),
(0, "feet"), (0, "sick"), (0, "vet"), (0, "fat"), (0, "dot"), (0, "bought"), (0, "sought"), (0, "nice"), (0, "fight"),
(0, "sight"), (0, "wife"), (0, "dice"), (0, "safe"), (0, "cape"), (0, "trace"), (0, "fate"), (0, "leap"), (1, "seed"),
(1, "bid"), (1, "pig"), (1, "cab"), (1, "bed"), (1, "dad"), (1, "leg"), (1, "food"), (1, "bred"), (1, "bag"),
(1, "beg"), (1, "red"), (1, "feed"), (1, "big"), (1, "dog"), (1, "blog"), (1, "prize"), (1, "bribe"), (1, "dive"),
(1, "strive"), (1, "wise"), (1, "drive"), (1, "live"), (1, "cave"), (1, "gave"), (1, "gaze"), (1, "fade"), (1, "flag"),
(1, "pride"), (1, "side");

-- --------------------------------------------------------
--
-- Структура таблицы `vowel_fill`
--

drop table if exists `vowel_fill`;
create table `vowel_fill` (`id` int primary key auto_increment, `word` varchar(64), `long_vowel` tinyint);

insert into `vowel_fill` (`long_vowel`, `word`) values
(0, "pi[ss|t|ck|p]"),
(0, "bi[t]"),
(0, "ti[c|ck|t|p]"),
(0, "si[p|t|ck|x]"),
(1, "lea[d|se|ve]"),
(1, "tea[se]"),
(1, "bree[d]"),
(1, "bea[d|ch|r]");
