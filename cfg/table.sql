CREATE TABLE `registration` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `adressid` int(11) default NULL,
  `jahr` int(4) default NULL,
  `ts_update` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `kontingent` int(11) NOT NULL,
  `angemeldet` int(11) NOT NULL,
  `registrationskey` varchar(10) NOT NULL,
  `bemerkung` text,
  PRIMARY KEY   (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1

insert into registration set adressid=571,jahr=2010,kontingent=5,angemeldet=0,registrationskey='12qwasyx';