create database if not exists `rphone`;
use `rphone`;

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

