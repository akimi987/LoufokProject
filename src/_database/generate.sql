--
-- Base de données : `mvc.generic-demo`
--

-- --------------------------------------------------------

CREATE TABLE `avatar` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `illustration` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `avatar` (`id`, `email`, `password`, `display_name`, `illustration`, `created_at`) VALUES
(7, 'avatar-demo1@gmail.fr', 'avatar1', 'avatar-1', 'avatar01_64c79b7acb622.webp', '2023-07-31 11:31:06'),
(8, 'avatar-demo2@gmail.fr', 'avatar-1', 'avatar-2', 'avatar02_64c79b8ff07b5.webp', '2023-07-31 11:31:28'),
(9, 'avatar-demo3@gmail.fr', 'avatar-1', 'avatar-3', 'avatar03_64c79ba0805d8.webp', '2023-07-31 11:31:44'),
(10, 'avatar-demo4@gmail.fr', 'avatar-1', 'avatar-4', 'avatar05_64c79bb521462.webp', '2023-07-31 11:32:05');

ALTER TABLE `avatar`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `avatar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;
