-- 
-- Structure de la table `wp_eewee_sellsy_ticket`
--

CREATE TABLE `wp_eewee_sellsy_ticket` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_dt_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ticket_dt_create_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ticket_subject` varchar(255) NOT NULL,
  `ticket_message` text NOT NULL,
  `ticket_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

--
-- Contenu de la table `wp_eewee_sellsy_ticket`
--

INSERT INTO `wp_eewee_sellsy_ticket` VALUES (1, '2017-01-01 20:50:00', '2017-01-01 19:50:00', '[TICKET] - lorem ipsum', 'Pellentesque posuere. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero. Cras varius. Etiam imperdiet imperdiet orci. Morbi mollis tellus ac sapien.', 1);
