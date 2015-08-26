--
-- Feed Module MySQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------

--
-- Table structure for table `feed`
--

CREATE TABLE IF NOT EXISTS `[{prefix}]feed` (
  `id` int(16) NOT NULL,
  `type` varchar(255) NOT NULL,
  UNIQUE (`id`, `type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;
