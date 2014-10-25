SET NAMES 'utf8';

INSERT INTO `tbl_federation` (`name`, `lib`)
VALUES ('FFST', 'Fédération officielle');

INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('15', '1', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('25', '2', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('35', '3', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('45', '4', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('15', '5', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('25', '6', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('35', '7', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('45', '8', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('40', '9', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('40', '10', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('20', '11', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('15', '12', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('25', '13', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('15', '14', '1');
INSERT INTO `tbl_prixlicence` (`prix`, `id_typelicence`, `id_federation`)
VALUES ('15', '15', '1');

UPDATE tbl_club SET id_federation = 1;
