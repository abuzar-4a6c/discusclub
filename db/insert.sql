INSERT INTO `role`(`name`) VALUES
('gast'),
('gebruiker'),
('lid'),
('redacteur'),
('admin');

INSERT INTO `image`(`path`) VALUES
('/default.png'),
('/sponsor/ESHA-Banner_NL_discus_03B15.gif'),
('/sponsor/discusmania-toekan.gif'),
('/sponsor/banner-HVP-Aqua.gif'),
('/sponsor/Veldhuis-banner.jpg'),
('/sponsor/Dicuscompleet-banner.jpg'),
('/sponsor/discuspassi-banner-3.jpg'),
('/sponsor/Discusshop-banner.jpg'),
('/sponsor/Aqua-light-banner.jpg'),
('/sponsor/Rockzolid-banner.jpg'),
('/sponsor/osmoseapparaat-banner.jpg'),
('/sponsor/Wesdijk-banner.jpg'),
('/sponsor/Koidream-banner.jpg'),
('/sponsor/DCH-banner-AquaVaria-2014.jpg'),
('/sponsor/RUTO-banner.jpg'),
('/sponsor/discusvistotaal.gif'),
('/messenger_background/default.jpg');

INSERT INTO `user` (`first_name`, `last_name`, `email`, `username`, `password`, `role_id`, `created_at`) VALUES
('john', 'doe', 'john_doe@example.com', 'test', '$2y$10$9UNJC27kiVGmXrn5WUeyPeSktXXF1uTRE2mX8bgOISy2GTLC57pBm', 5, NOW()),
('jane', 'doe', 'jane_doe@example.com', 'bla', '$2y$10$9UNJC27kiVGmXrn5WUeyPeSktXXF1uTRE2mX8bgOISy2GTLC57pBm', 5, NOW());

INSERT INTO `category` (`name`) VALUES
('Mededelingen en Nieuws'),
('Discus Vissen'),
('Wat nergens thuishoort'),
('Foto album (Toon hier uw foto\'s)'),
('Handel'),
('Discus Club Holland');

INSERT INTO `sub_category` (`category_id`, `name`) VALUES
(1, 'Mededelingen'),
(2, 'Algemene Discusvis vragen'),
(3, 'Off Topic'),
(4, 'Foto Album'),
(5, 'Te Koop'),
(6, 'Open Nederlands Kampioenschap Discusvissen 2013'),
(1, 'Even voorstellen');

INSERT INTO `state` (`name`) VALUES
('open'),
('closed'),
('pinned');

INSERT INTO `topic` (`sub_category_id`, `title`, `user_id`, `content`, `created_at`, `last_changed`, `state_id`) VALUES
(1, 'Nieuwe website DCH', 1, '\"Het Water Gala\" komt met rasse schreden dichterbij, binnen iets meer dan een maand is het zo ver!\r\n\r\nEen beurs als deze is niet mogelijk zonder eerst vele handjes te hebben die helpen om op te bouwen.\r\nEr zijn inmiddels een flink aantal mensen die het kunnen beamen: meehelpen aan het opbouwen van dergelijke grootse beurs is een flinke karwij, is lastig, maar het belangrijkste is misschien: het is zo fantastisch mensen achter de schermen te leren kennen, je leert er zelf dingen bij, je leert interessante mensen kennen die je op vlak van de hobby best wel nog kunt contacteren later.\r\n\r\nWelnu, wij hadden jullie er graag bij gehad om te helpen!\r\n\r\nHeb je interesse om te komen helpen op onderstaande data, dan kunnen wij garanderen dat jijzelf achteraf vol fier en trots zal kunnen zeggen: ik heb meegeholpen aan die mega beurs, wat de vele duizenden bezoekers gaan zien, welnu, dat is deels mijn werk!\r\n\r\n\r\nKunt u zich vrij maken op :\r\n\r\nMaandag 19 september (belangrijk)\r\ndinsdag 20 september (belangrijk)\r\nwoensdag 21 september\r\ndonderdag 22 september\r\nvrijdag 23 september\r\n\r\nmaandag 26 september (super belangrijk, de afbraak moet op één dag kunnen gebeuren, hier hebben we NOOIT handen teveel!!!)\r\n\r\nGraag dan een seintje naar iemand van het bestuur, hetzij hier, hetzij via privé bericht, sms, email, facebook ah, van ons part mogen jullie zelfs te tamtam en rooksignalen gebruiken, zolang wij maar weten dat wij op jullie kunnen rekenen!\r\n\r\nNiet alleen namens het ganse bestuur, maar vooral namens een paar duizend bezoekers danken wij jullie alvast van harte voor de hulp!!!!', '2017-10-16 14:35:24', '2017-10-16 14:35:24', 3);

INSERT INTO `topic` (`sub_category_id`, `title`, `user_id`, `content`, `created_at`, `last_changed`) VALUES
(1, 'test topic', 1, "bla", '2017-10-18 10:57:41', '2017-10-18 10:57:41'),
(1, 'Nieuw test topic', 1, '<p>fdsfsdfsdfsdfgsdfsd</p>', '2017-10-18 12:44:06', '2017-10-18 12:44:06'),
(1, 'een nieuw test topic', 1, '<p><i>sdujfnsdipfpoakfdnfjksdnf</i></p>', '2017-10-18 12:44:42', '2017-10-18 12:44:42');

INSERT INTO `album`(`title`, `user_id`, `created_at`) VALUES
('dinges', 1, NOW());

INSERT INTO `album_reply`(`content`, `user_id`, `album_id`, `created_at`) VALUES
('geile fotos jonge', 2, 1, NOW());

INSERT INTO `image`(`path`, `album_id`) VALUES
('/default.png', 1);

INSERT INTO `message`(`message`, `user_id_1`, `user_id_2`, `created_at`) VALUES
('bla', 1, 2, NOW()),
('dinges', 2, 1, NOW());

INSERT INTO `sponsor`(`image_id`, `name`, `url`, `created_at`) VALUES
(2, 'eSHa Aquariumproducten', 'http://www.eshalabs.eu/nederlands/', NOW()),
(3, 'discus mania', 'http://discusmania.nl/', NOW()),
(4, 'hvp aqua', 'http://www.hvpaqua.nl/', NOW()),
(5, 'aquaria veldhuis', 'https://www.aquariaveldhuis.nl/nl/', NOW()),
(7, 'discus passie', 'http://www.discuspassie.nl/', NOW()),
(8, 'discusshop', 'https://discusshop.nl/', NOW()),
(9, 'jmd aqua light', 'http://www.jmbaqualight.nl/', NOW()),
(10, 'rock zolid', 'http://www.rockzolid.nl/', NOW()),
(11, 'osmose apparaat', 'https://www.osmoseapparaat.nl/', NOW()),
(12, 'Wesdijk', 'https://www.wesdijk.nl/', NOW()),
(13, 'koidream', 'https://www.koidream.com/', NOW()),
(14, 'daphnia boxtel', 'http://www.daphniaboxtel.nl/', NOW()),
(15, 'ruto', 'https://www.ruto.nl/', NOW()),
(16, 'discus vis totaal', 'http://www.discusvistotaal.nl/', NOW()),
(6, 'Discuscompleet', 'http://www.discuscompleet.nl/', NOW());

INSERT INTO `news`(`sub_category_id`, `title`, `content`, `created_at`) VALUES
(1, 'dinges enzo', 'bla bla bla...', NOW());

INSERT INTO `news_reply`(`user_id`, `news_id`, `content`, `created_at`) VALUES
(1, 1, "reactie dinges enzo bla bla", NOW());

INSERT INTO `page`(`uri`, `name`, `content`, `image_id`) VALUES
("houden-van", "houden van", "<p>You think water moves fast? You should see ice. It moves like it has a mind.
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.<br><br>
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.<br><br>
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.</p>", 1),
("kweken", "kweken", "<p>You think water moves fast? You should see ice. It moves like it has a mind.
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.<br><br>
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.<br><br>
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.</p>", 1),
("ziektes", "ziektes", "<p>You think water moves fast? You should see ice. It moves like it has a mind.
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.<br><br>
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.<br><br>
                Like it knows it killed the world once and got a taste for murder. After the avalanche,
                it took us a week to climb out. Now, I don't know exactly when we turned on each other,
                but I know that seven of us survived the slide... and only five made it out. Now we took an oath,
                that I'm breaking now. We said we'd say it was the snow that killed the other two, but it wasn't.
                Nature is lethal but it doesn't hold a candle to man.</p>", 1);