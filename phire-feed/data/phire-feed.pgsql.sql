--
-- Feed Module PostgreSQL Database for Phire CMS 2.0
--

-- --------------------------------------------------------

--
-- Table structure for table "feed"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]feed" (
  "id" integer NOT NULL,
  "type" varchar(255) NOT NULL,
  UNIQUE ("id", "type")
) ;

-- --------------------------------------------------------
