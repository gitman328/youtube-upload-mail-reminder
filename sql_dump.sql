DROP TABLE IF EXISTS `#channel_list`;
CREATE TABLE IF NOT EXISTS `#channel_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` varchar(255) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
