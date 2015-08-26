--
-- Feed Module SQLite Database for Phire CMS 2.0
--

--  --------------------------------------------------------

--
-- Set database encoding
--

PRAGMA encoding = "UTF-8";
PRAGMA foreign_keys = ON;

-- --------------------------------------------------------

--
-- Table structure for table "feed"
--

CREATE TABLE IF NOT EXISTS "[{prefix}]feed" (
  "id" integer NOT NULL,
  "type" varchar NOT NULL
  UNIQUE ("id", "type")
) ;

-- --------------------------------------------------------
