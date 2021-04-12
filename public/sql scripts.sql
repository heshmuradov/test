INSERT INTO "spr_royhat" ("id", "name_uz", "name_ru", "pf_close_reason") VALUES
(16,	'Рўйхатда туради(Ногирон Бола)',	'Рўйхатда туради(Ногирон Бола)',	'00'),
(17,	'Рўйхатда турмайди(Согломлаштирилди)',	'Рўйхатда турмайди(Согломлаштирилди)',	'00');



ALTER TABLE "mijoz_ill_history"
ADD COLUMN "degree_violation" smallint DEFAULT '0';