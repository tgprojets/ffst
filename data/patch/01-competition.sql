UPDATE `tbl_grouplicence` SET  `code` =  'COM' WHERE  `tbl_grouplicence`.`code` = 'ATT' or `tbl_grouplicence`.`code` = 'MON';

INSERT INTO `tbl_grouplicence` (lib, code) VALUES ('Licence Toutes Compétitions', 'COM');