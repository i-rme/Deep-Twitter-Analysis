<?php

/*
CREATE TABLE `tweets` (
  `created_at` varchar(64) NOT NULL,
  `id` bigint(20) NOT NULL,
  `text` varchar(512) NOT NULL,
  `hashtags` varchar(256) NOT NULL,
  `user` varchar(32) NOT NULL,
  `retweet_count` int(11) NOT NULL,
  `favorite_count` int(11) NOT NULL,
  `lang` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(64) NOT NULL,
  `screen_name` varchar(128) NOT NULL,
  `location` varchar(128) NOT NULL,
  `description` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);
ALTER TABLE `tweets` ADD FULLTEXT KEY `index_text` (`text`);
ALTER TABLE `tweets` ADD FULLTEXT KEY `index_hashtags` (`hashtags`);
ALTER TABLE `tweets` ADD FULLTEXT KEY `index_user` (`user`);
ALTER TABLE `tweets` ADD FULLTEXT KEY `index_tweetsall` (`text`,`hashtags`,`user`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`name`),
  ADD KEY `screen_name` (`screen_name`);
ALTER TABLE `users` ADD FULLTEXT KEY `index_name` (`name`);
ALTER TABLE `users` ADD FULLTEXT KEY `index_screen_name` (`screen_name`);
ALTER TABLE `users` ADD FULLTEXT KEY `index_location` (`location`);
ALTER TABLE `users` ADD FULLTEXT KEY `index_description` (`description`);
ALTER TABLE `users` ADD FULLTEXT KEY `index_usersall` (`name`,`screen_name`,`location`,`description`);


ALTER TABLE `tweets`
  ADD CONSTRAINT `tweets_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`screen_name`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;
*/

  return [
    "db" => [
      "server" => "localhost",
      "database" => "[REDACTED]",
      "username" => "[REDACTED]",
      "password" => "[REDACTED]"
    ]
  ];
?>
