-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 04:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moviestream_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL COMMENT 'Font Awesome class or emoji',
  `color` varchar(20) DEFAULT '#c8ff00' COMMENT 'Hex color for UI badge',
  `sort_order` int(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `color`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'New Releases', 'new-releases', 'The latest movies added to CineVerse.', '🆕', '#c8ff00', 1, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(2, 'Award Winners', 'award-winners', 'Oscar, BAFTA, and Golden Globe winning films.', '🏆', '#ffd700', 2, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(3, 'Critically Acclaimed', 'critically-acclaimed', 'Movies with a rating of 8.0 or above.', '⭐', '#ff9f43', 3, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(4, 'Blockbusters', 'blockbusters', 'The biggest box office hits of all time.', '💥', '#ff6b6b', 4, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(5, 'Science Fiction', 'science-fiction', 'Explore future worlds, space, and technology.', '🚀', '#48dbfb', 5, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(6, 'Thriller & Horror', 'thriller-horror', 'Edge-of-your-seat suspense and terror.', '😱', '#6c5ce7', 6, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(7, 'Classic Cinema', 'classic-cinema', 'Timeless masterpieces from before the year 2000.', '🎞️', '#a29bfe', 7, '2026-02-20 02:07:56', '2026-02-20 02:07:56'),
(8, 'Family & Animation', 'family-animation', 'Fun for the whole family.', '🎨', '#55efc4', 8, '2026-02-20 02:07:56', '2026-02-20 02:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `movie_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(4, '2026-02-16-032500', 'App\\Database\\Migrations\\CreateMoviesTable', 'default', 'App', 1771516386, 1),
(5, '2026-02-16-145000', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1771516386, 1),
(6, '2026-02-18-000001', 'App\\Database\\Migrations\\AddTmdbIdToMovies', 'default', 'App', 1771516386, 1),
(7, '2026-02-20-000001', 'App\\Database\\Migrations\\CreateFavoritesTable', 'default', 'App', 1771553261, 2),
(8, '2026-02-20-000002', 'App\\Database\\Migrations\\CreateWatchHistoryTable', 'default', 'App', 1771553261, 2),
(9, '2026-02-20-000003', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1771553262, 2),
(10, '2026-02-24-000001', 'App\\Database\\Migrations\\AddForeignKeyConstraints', 'default', 'App', 1771920915, 3);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) UNSIGNED NOT NULL,
  `tmdb_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `genre` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `duration` int(5) NOT NULL COMMENT 'Duration in minutes',
  `rating` decimal(3,1) NOT NULL DEFAULT 0.0,
  `poster_url` varchar(500) DEFAULT NULL,
  `backdrop_url` varchar(500) DEFAULT NULL,
  `trailer_url` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `cast` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_trending` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `tmdb_id`, `title`, `slug`, `description`, `genre`, `year`, `duration`, `rating`, `poster_url`, `backdrop_url`, `trailer_url`, `video_url`, `director`, `cast`, `is_featured`, `is_trending`, `created_at`, `updated_at`) VALUES
(1, 278, 'The Shawshank Redemption', 'the-shawshank-redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency. A timeless story of hope and friendship inside the walls of Shawshank State Penitentiary.', 'Drama', 1994, 142, 9.3, 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg', 'https://image.tmdb.org/t/p/w1280/avedvodAZUcwqevBfm8p4G2NziQ.jpg', 'https://www.youtube.com/watch?v=6hB3S9bIaco', '', 'Frank Darabont', 'Tim Robbins, Morgan Freeman, Bob Gunton, William Sadler', 1, 1, '2026-02-19 15:53:14', '2026-02-27 09:05:30'),
(2, 1241982, 'Moana 2', 'moana-2', 'Moana sets out on a new voyage, sailing to the far seas of Oceania and into the dangerous, long-lost seas of her ancestors for an adventure unlike anything she has ever faced before.', 'Animation', 2024, 100, 7.0, 'https://image.tmdb.org/t/p/w500/aLVkiINlIeCkcZIzb7XHzPYgO6L.jpg', 'https://image.tmdb.org/t/p/w1280/tElnmtQ6yz1PjN1kePNl8yMSb59.jpg', 'https://www.youtube.com/watch?v=OKZ4RVQM65o', '', 'Dave Derrick Jr.', 'Auli\'i Cravalho, Dwayne Johnson, Alan Tudyk', 1, 1, '2026-02-19 15:53:14', '2026-02-27 09:05:30'),
(3, 155, 'The Dark Knight', 'the-dark-knight', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 'Action', 2008, 152, 9.0, 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg', 'https://image.tmdb.org/t/p/w1280/nMKdUFyrkzxzM8fmDWFaFVIWF5P.jpg', 'https://www.youtube.com/watch?v=EXeTwQWrcwY', '', 'Christopher Nolan', 'Christian Bale, Heath Ledger, Aaron Eckhart, Maggie Gyllenhaal', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(4, 424, 'Schindler\'s List', 'schindlers-list', 'In German-occupied Poland during World War II, industrialist Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing the persecution of Jews in the streets.', 'Drama', 1993, 195, 9.0, 'https://image.tmdb.org/t/p/w500/sF1U4EUQS8YHUYjNl3pMGNIQyr0.jpg', 'https://image.tmdb.org/t/p/w1280/zb6fM1CX41D9rF9hdgclu0peUmy.jpg', 'https://www.youtube.com/watch?v=mxphAlJID9U', '', 'Steven Spielberg', 'Liam Neeson, Ralph Fiennes, Ben Kingsley, Embeth Davidtz', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(5, 533535, 'Deadpool & Wolverine', 'deadpool-wolverine-2024', 'Deadpool teams up with Wolverine to save the multiverse. Reynolds and Jackman reunite in the MCU\'s most irreverent adventure yet.', 'Action', 2024, 128, 7.9, 'https://image.tmdb.org/t/p/w500/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg', 'https://image.tmdb.org/t/p/w1280/yDHYTfA3R0jFYba16jBB1ef8oIt.jpg', 'https://www.youtube.com/watch?v=73_1biulkYk', '', 'Shawn Levy', 'Ryan Reynolds, Hugh Jackman, Emma Corrin, Matthew Macfadyen', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(6, 27205, 'Inception', 'inception', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O., but his tragic past may doom the project and his team.', 'Sci-Fi', 2010, 148, 8.8, 'https://image.tmdb.org/t/p/w500/ljsZTbVsrQSqZgWeep2B1QiDKuh.jpg', 'https://image.tmdb.org/t/p/w1280/8ZTVqvKDQ8emSGPountmZXSfcRf.jpg', 'https://www.youtube.com/watch?v=YoHD9XEInc0', '', 'Christopher Nolan', 'Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page, Tom Hardy', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(7, 122, 'The Lord of the Rings: The Return of the King', 'lotr-return-of-the-king', 'Gandalf and Aragorn lead the World of Men against Sauron\'s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.', 'Fantasy', 2003, 201, 9.0, 'https://image.tmdb.org/t/p/w500/rCzpDGLbOoPwLjy3OAm5NUPOTrC.jpg', 'https://image.tmdb.org/t/p/w1280/pm0A8VE0cXx0G6UYkSblAIRtTDJ.jpg', 'https://www.youtube.com/watch?v=r5X-hFf6Bwo', '', 'Peter Jackson', 'Elijah Wood, Ian McKellen, Viggo Mortensen, Liv Tyler', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(8, 13, 'Forrest Gump', 'forrest-gump', 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an extraordinary story.', 'Drama', 1994, 142, 8.8, 'https://image.tmdb.org/t/p/w500/arw2vcBveWOVZr6pxd9XTd1TdQa.jpg', 'https://image.tmdb.org/t/p/w1280/3hoJluxQpVXaKci5sF6kyzjUFrj.jpg', 'https://www.youtube.com/watch?v=bLvqoHBptjg', '', 'Robert Zemeckis', 'Tom Hanks, Robin Wright, Gary Sinise, Sally Field', 1, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(9, 550, 'Fight Club', 'fight-club', 'An insomniac office worker and a devil-may-care soap maker form an underground fight club that evolves into something much more sinister. A raw, anarchic exploration of masculinity and identity.', 'Drama', 1999, 139, 8.8, 'https://image.tmdb.org/t/p/w500/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg', 'https://image.tmdb.org/t/p/w1280/hZkgoQYus5vegHoetLkCJzb17zJ.jpg', 'https://www.youtube.com/watch?v=qtRKdVHc-cE', '', 'David Fincher', 'Brad Pitt, Edward Norton, Helena Bonham Carter, Meat Loaf', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(10, 769, 'Goodfellas', 'goodfellas', 'The story of Henry Hill and his life in the mob, covering his relationship with his wife Karen Hill and his mob partners Jimmy Conway and Tommy DeVito.', 'Crime', 1990, 146, 8.7, 'https://image.tmdb.org/t/p/w500/aKuFiU82s5ISJpGZp7YkIr3kCUd.jpg', 'https://image.tmdb.org/t/p/w1280/sw7mordbZxgITU877yTpZCud90M.jpg', 'https://www.youtube.com/watch?v=2ilzidi_J8Q', '', 'Martin Scorsese', 'Robert De Niro, Ray Liotta, Joe Pesci, Lorraine Bracco', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(11, 603, 'The Matrix', 'the-matrix', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers. Reality is not what it seems.', 'Sci-Fi', 1999, 136, 8.7, 'https://image.tmdb.org/t/p/w500/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg', 'https://image.tmdb.org/t/p/w1280/fNG7i7RqMErkcqhohV2a6cV1Ehy.jpg', 'https://www.youtube.com/watch?v=vKQi3bBA1y8', '', 'Lana Wachowski, Lilly Wachowski', 'Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss, Hugo Weaving', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(12, 402431, 'Wicked', 'wicked-2024', 'The story of the unlikely friendship between Glinda and Elphaba before they became the Good and Wicked Witches of Oz.', 'Drama', 2024, 160, 7.8, 'https://image.tmdb.org/t/p/w500/c5Tqxeo1UpBvnAc3csUm7j3hlQl.jpg', 'https://image.tmdb.org/t/p/w1280/uVlUu174iiKLBkfaULCkpazmsN6.jpg', 'https://www.youtube.com/watch?v=6COmYeLsz4c', '', 'Jon M. Chu', 'Cynthia Erivo, Ariana Grande, Jeff Goldblum, Jonathan Bailey', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(13, 496243, 'Parasite', 'parasite', 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan. A biting social satire from Bong Joon-ho.', 'Thriller', 2019, 132, 8.5, 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg', 'https://image.tmdb.org/t/p/w1280/TU9NIjwzjoKPwQHoHshkFcQUCG8.jpg', 'https://www.youtube.com/watch?v=5xH0HfJHsaY', '', 'Bong Joon-ho', 'Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong, Choi Woo-shik', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(14, 475557, 'Joker', 'joker', 'A mentally troubled stand-up comedian embarks on a downward spiral that leads to the creation of an iconic villain. Arthur Fleck\'s transformation into chaos incarnate.', 'Drama', 2019, 122, 8.4, 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg', 'https://image.tmdb.org/t/p/w1280/n6bUvigpRFqSwmPp1m2YMDm2A01.jpg', 'https://www.youtube.com/watch?v=zAGVQLHvwOY', '', 'Todd Phillips', 'Joaquin Phoenix, Robert De Niro, Zazie Beetz, Frances Conroy', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(15, 299534, 'Avengers: Endgame', 'avengers-endgame', 'After the devastating events of Infinity War, the Avengers assemble once more to reverse Thanos\'s actions and restore balance to the universe. The ultimate Marvel showdown.', 'Action', 2019, 181, 8.4, 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg', 'https://image.tmdb.org/t/p/w1280/7RyHsO4yDXtBv1zUU3mTpHeQ0d5.jpg', 'https://www.youtube.com/watch?v=TcMBFSGVi1c', '', 'Anthony Russo, Joe Russo', 'Robert Downey Jr., Chris Evans, Mark Ruffalo, Chris Hemsworth', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(16, 238, 'The Godfather', 'the-godfather', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 'Crime', 1972, 175, 9.2, 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg', 'https://image.tmdb.org/t/p/w1280/tSPT36ZKlP2WVHJLM4cQPLSzv3b.jpg', 'https://www.youtube.com/watch?v=sY1S34973zA', '', 'Francis Ford Coppola', 'Marlon Brando, Al Pacino, James Caan, Diane Keaton', 1, 0, '2026-02-19 15:53:14', '2026-02-27 09:16:54'),
(17, 361743, 'Top Gun: Maverick', 'top-gun-maverick', 'After more than thirty years of service as a top naval aviator, Pete Mitchell is pushing the envelope as a courageous test pilot while dodging the advancement in rank that would ground him.', 'Action', 2022, 130, 8.3, 'https://image.tmdb.org/t/p/w500/62HCnUTziyWcpDaBO2i1DX17ljH.jpg', 'https://image.tmdb.org/t/p/w1280/AaV1YIdWKRTTbghOERHl4t0t12d.jpg', 'https://www.youtube.com/watch?v=giXco2jaZ_4', '', 'Joseph Kosinski', 'Tom Cruise, Jennifer Connelly, Miles Teller, Val Kilmer', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(18, 872585, 'Oppenheimer', 'oppenheimer', 'The story of American scientist J. Robert Oppenheimer and his role in the development of the atomic bomb during World War II. A masterwork from Christopher Nolan.', 'Drama', 2023, 180, 8.4, 'https://image.tmdb.org/t/p/w500/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg', 'https://image.tmdb.org/t/p/w1280/rLb2cwF3Pazuxaj0sRXQ037tGI1.jpg', 'https://www.youtube.com/watch?v=uYPbbksJxIg', '', 'Christopher Nolan', 'Cillian Murphy, Emily Blunt, Matt Damon, Robert Downey Jr.', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(19, 1022789, 'Inside Out 2', 'inside-out-2', 'Now a teenager, Riley has new emotions joining her mental headquarters: Anxiety, Ennui, Envy, Embarrassment and Nostalgia. Joy, Sadness and their friends aren\'t sure how to deal with this change.', 'Animation', 2024, 100, 7.8, 'https://image.tmdb.org/t/p/w500/vpnVM9B6NMmQpWeZvzLvDESb2QY.jpg', 'https://image.tmdb.org/t/p/w1280/p5ozvmdgsmbWe0H8Xk7Rc8SCwAB.jpg', 'https://www.youtube.com/watch?v=LEjhY15eCx0', '', 'Kelsey Mann', 'Amy Poehler, Maya Hawke, Kensington Tallman, Liza Lapira', 1, 1, '2026-02-19 15:53:14', '2026-02-27 09:16:54'),
(20, 634649, 'Spider-Man: No Way Home', 'spider-man-no-way-home', 'With Spider-Man\'s identity now revealed, Peter asks Doctor Strange for help. When a spell goes wrong, dangerous foes from other worlds start to appear.', 'Action', 2021, 148, 8.2, 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg', 'https://image.tmdb.org/t/p/w1280/iQFcwSGbZXMkeyKrxbPnwnRo5fl.jpg', 'https://www.youtube.com/watch?v=JfVOs4VSpmA', '', 'Jon Watts', 'Tom Holland, Zendaya, Benedict Cumberbatch, Willem Dafoe', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(21, 98, 'Gladiator', 'gladiator', 'A former Roman General sets out to exact vengeance against the corrupt emperor who murdered his family and sent him into slavery.', 'Action', 2000, 155, 8.5, 'https://image.tmdb.org/t/p/w500/ty8TGRuvJLPUmAR1H1nRIsgwvim.jpg', 'https://image.tmdb.org/t/p/w1280/gF4gKFPrVehUCwyL4BqSqFDwMom.jpg', 'https://www.youtube.com/watch?v=owK1qxDselE', '', 'Ridley Scott', 'Russell Crowe, Joaquin Phoenix, Connie Nielsen, Oliver Reed', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:46:50'),
(22, 597, 'Titanic', 'titanic', 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic. An epic romance against the backdrop of the greatest maritime disaster.', 'Romance', 1997, 194, 7.9, 'https://image.tmdb.org/t/p/w500/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg', 'https://image.tmdb.org/t/p/w1280/4qCqAdHcNKeAHcK8tJ8wNJZa9cx.jpg', 'https://www.youtube.com/watch?v=kVrqfYjkTdQ', '', 'James Cameron', 'Leonardo DiCaprio, Kate Winslet, Billy Zane, Frances Fisher', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(23, 244786, 'Whiplash', 'whiplash', 'A promising young drummer enrolls at a cut-throat music conservatory where his dreams of greatness are mentored by an instructor who will stop at nothing to realize a student\'s potential.', 'Drama', 2014, 106, 8.5, 'https://image.tmdb.org/t/p/w500/7fn624j5lj3xTme2SgiLCeuedmO.jpg', 'https://image.tmdb.org/t/p/w1280/6bbZ6XyvgfjhQwbplnUh1LSj1uu.jpg', 'https://www.youtube.com/watch?v=7d_jQycdQGo', '', 'Damien Chazelle', 'Miles Teller, J.K. Simmons, Melissa Benoist, Paul Reiser', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(24, 274, 'The Silence of the Lambs', 'the-silence-of-the-lambs', 'A young FBI cadet must receive the help of an incarcerated and manipulative cannibal killer to help catch another serial killer, a madman who skins his victims.', 'Thriller', 1991, 118, 8.6, 'https://image.tmdb.org/t/p/w500/uS9m8OBk1A8eM9I042bx8XXpqAq.jpg', 'https://image.tmdb.org/t/p/w1280/mfwq2nMBmAT7VCSLX7LQKJBZOEQ.jpg', 'https://www.youtube.com/watch?v=W6Mm8Sbe__o', '', 'Jonathan Demme', 'Jodie Foster, Anthony Hopkins, Lawrence A. Bonney, Kasi Lemmons', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(25, 19995, 'Avatar', 'avatar', 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders and protecting the world he feels is his home.', 'Sci-Fi', 2009, 162, 7.9, 'https://image.tmdb.org/t/p/w500/jRXYjXNq0Cs2TcJjLkki24MLp7u.jpg', 'https://image.tmdb.org/t/p/w1280/o0s4XsEDfDlvit5pDRtjn9soJkc.jpg', 'https://www.youtube.com/watch?v=5PSNL1qE6VY', '', 'James Cameron', 'Sam Worthington, Zoe Saldana, Sigourney Weaver, Michelle Rodriguez', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(26, 545611, 'Everything Everywhere All at Once', 'everything-everywhere-all-at-once', 'An aging Chinese immigrant is swept up in an insane adventure, where she alone can save the world by exploring other universes connecting with the lives she could have led.', 'Sci-Fi', 2022, 139, 8.1, 'https://image.tmdb.org/t/p/w500/w3LxiVYdWWRvEVdn5RYq6jIqkb1.jpg', 'https://image.tmdb.org/t/p/w1280/yHCfW4Tr34QqgEHJVBBBFxQSz6m.jpg', 'https://www.youtube.com/watch?v=wxN1T1uxQ2g', '', 'Daniel Kwan, Daniel Scheinert', 'Michelle Yeoh, Ke Huy Quan, Jamie Lee Curtis, Stephanie Hsu', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(27, 284054, 'Black Panther', 'black-panther', 'T\'Challa, heir to the hidden but advanced kingdom of Wakanda, must step forward to lead his people into a new future and must confront a challenger from his country\'s past.', 'Action', 2018, 134, 7.3, 'https://image.tmdb.org/t/p/w500/uxzzxijgPIY7slzFvMotPv8wjKA.jpg', 'https://image.tmdb.org/t/p/w1280/b6ZJZHUdMEFECvGiDpJjlfUWela.jpg', 'https://www.youtube.com/watch?v=xjDjIWPwcPU', '', 'Ryan Coogler', 'Chadwick Boseman, Michael B. Jordan, Lupita Nyong\'o, Danai Gurira', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(28, 419430, 'Get Out', 'get-out', 'A young African-American visits his white girlfriend\'s parents for the weekend, where his stay reveals an unimaginable secret. Jordan Peele\'s brilliant social horror debut.', 'Horror', 2017, 104, 7.7, 'https://image.tmdb.org/t/p/w500/tFXcEccSQMf3lfhfXKSU9iRBpa3.jpg', 'https://image.tmdb.org/t/p/w1280/l3QI2yPgWV92cP3q94nD2VpSfIF.jpg', 'https://www.youtube.com/watch?v=DzfpyUB60YY', '', 'Jordan Peele', 'Daniel Kaluuya, Allison Williams, Bradley Whitford, Catherine Keener', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(29, 76341, 'Mad Max: Fury Road', 'mad-max-fury-road', 'In a post-apocalyptic wasteland, Max teams up with a mysterious woman, Furiosa, to help a group of female prisoners escape a tyrannical warlord. A breathtaking action spectacle.', 'Action', 2015, 120, 8.1, 'https://image.tmdb.org/t/p/w500/8tZYtuWezp8JbcsvHYO0O46tFbo.jpg', 'https://image.tmdb.org/t/p/w1280/phszHPFnhmSgRDbRBkhJSlFEKdB.jpg', 'https://www.youtube.com/watch?v=hEJnMQG9ev8', '', 'George Miller', 'Tom Hardy, Charlize Theron, Nicholas Hoult, Hugh Keays-Byrne', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(30, 313369, 'La La Land', 'la-la-land', 'While navigating their careers in Los Angeles, a pianist and an actress share an inspiring romance while attempting to reconcile their art and ambitions as success threatens their relationship.', 'Romance', 2016, 128, 8.0, 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg', 'https://image.tmdb.org/t/p/w1280/nadTlnTE6dDyMzHKSFPBDEdxjcI.jpg', 'https://www.youtube.com/watch?v=0pdqf4P9MB8', '', 'Damien Chazelle', 'Ryan Gosling, Emma Stone, John Legend, Rosemarie DeWitt', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(31, 335984, 'Blade Runner 2049', 'blade-runner-2049', 'A young blade runner\'s discovery of a long-buried secret leads him to track down former blade runner Rick Deckard, who\'s been missing for thirty years. A stunning sci-fi noir.', 'Sci-Fi', 2017, 164, 8.0, 'https://image.tmdb.org/t/p/w500/gajva2L0rPYkEWjzgFlBXCAVBE5.jpg', 'https://image.tmdb.org/t/p/w1280/sAtoMqDVhNDQBc3QJL3RF6hlhGq.jpg', 'https://www.youtube.com/watch?v=gCcx85zbxz4', '', 'Denis Villeneuve', 'Ryan Gosling, Harrison Ford, Ana de Armas, Sylvia Hoeks', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(32, 329865, 'Arrival', 'arrival', 'A linguist works with the military to communicate with alien lifeforms after twelve mysterious spacecraft appear around the world. A profound meditation on language and time.', 'Sci-Fi', 2016, 116, 7.9, 'https://image.tmdb.org/t/p/w500/x2FJsf1ElAgr63Y3PNPtJrcmpoe.jpg', 'https://image.tmdb.org/t/p/w1280/yIZ1xMEgEHGvTCfNyT5Lhbq8jgM.jpg', 'https://www.youtube.com/watch?v=tFMo3UJ4B4g', '', 'Denis Villeneuve', 'Amy Adams, Jeremy Renner, Forest Whitaker, Michael Stuhlbarg', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(33, 661539, 'A Complete Unknown', 'a-complete-unknown', 'New York, 1961. Against the backdrop of a vibrant music scene and tumultuous cultural upheaval, 19-year-old Bob Dylan arrives in the city with his guitar and revolutionary talent.', 'Drama', 2024, 140, 7.5, 'https://image.tmdb.org/t/p/w500/llWl3GtNoXosbvYboelmoT459NM.jpg', 'https://image.tmdb.org/t/p/w1280/kcCy5tKTe6WepVQ6SQaSewpmoCj.jpg', 'https://www.youtube.com/watch?v=D9bcO2H6JLs', '', 'James Mangold', 'Timothée Chalamet, Elle Fanning, Monica Barbaro, Edward Norton', 1, 1, '2026-02-19 15:53:14', '2026-02-27 09:16:54'),
(34, 945961, 'Alien: Romulus', 'alien-romulus', 'A group of young people on a distant world find themselves in a confrontation with the most terrifying life form in the universe while scavenging the depths of an abandoned space station.', 'Sci-Fi', 2024, 119, 7.3, 'https://image.tmdb.org/t/p/w500/b33nnKl1GSFbao4l3fZDDqsMx0F.jpg', 'https://image.tmdb.org/t/p/w1280/9SSEUrSqhljBMzRe4aBTh17LAGS.jpg', 'https://www.youtube.com/watch?v=sNATNgHRm-o', '', 'Fede Álvarez', 'Cailee Spaeny, David Jonsson, Archie Renaux, Isabela Merced', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(35, 1226578, 'Longlegs', 'longlegs', 'FBI Agent Lee Harker is assigned to an unsolved serial killer case and discovers evidence of the occult, forcing her to unravel a terrifying mystery that connects back to her own life.', 'Thriller', 2024, 101, 6.3, 'https://image.tmdb.org/t/p/w500/1EwNyiiNFd863H4e8nWEzutnZD7.jpg', 'https://image.tmdb.org/t/p/w1280/6ToGkmqn0KG0UGGGUAC1Ww0e5CM.jpg', 'https://www.youtube.com/watch?v=vJ-R7s9vChc', '', 'Osgood Perkins', 'Maika Monroe, Nicolas Cage, Alicia Witt, Michelle Choi-Lee', 0, 1, '2026-02-19 15:53:14', '2026-02-27 09:16:54'),
(36, 786892, 'Furiosa: A Mad Max Saga', 'furiosa-a-mad-max-saga', 'The origin story of renegade warrior Furiosa before she teamed up with Mad Max in the post-apocalyptic world of the Wasteland.', 'Action', 2024, 148, 7.8, 'https://image.tmdb.org/t/p/w500/iADOJ8Zymht2JPMoy3R7xceZprc.jpg', 'https://image.tmdb.org/t/p/w1280/fypydE9MsLQmy8UYbfh5bCPBgXV.jpg', 'https://www.youtube.com/watch?v=XJMuhwVlca4', '', 'George Miller', 'Anya Taylor-Joy, Chris Hemsworth, Tom Burke, Alyla Browne', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(37, 493922, 'Hereditary', 'hereditary', 'A grieving family is haunted by tragic and disturbing occurrences after the death of their secretive grandmother. Ari Aster\'s terrifying portrait of familial grief and horror.', 'Horror', 2018, 127, 7.3, 'https://image.tmdb.org/t/p/w500/hjlZSXM86wJrfCv5VKfR5DI2VeU.jpg', 'https://image.tmdb.org/t/p/w1280/gJbTXKNTL6O7r7PzF6ZRkJGBlPp.jpg', 'https://www.youtube.com/watch?v=V6wWKNij_1M', '', 'Ari Aster', 'Toni Collette, Alex Wolff, Milly Shapiro, Gabriel Byrne', 0, 0, '2026-02-19 15:53:14', '2026-02-27 09:16:54'),
(38, 1184918, 'The Wild Robot', 'the-wild-robot', 'A shipwrecked robot discovers the wild and begins a new life there. While learning the ways of nature, she forms a bond with an orphaned gosling.', 'Animation', 2024, 102, 8.3, 'https://image.tmdb.org/t/p/w500/wTnV3PCVW5O92JMrFvvrRcV39RU.jpg', 'https://image.tmdb.org/t/p/w1280/9oYdz5gDoIl8h67e3ccq2zmHGJk.jpg', 'https://www.youtube.com/watch?v=_UR-l3QI2nE', '', 'Chris Sanders', 'Lupita Nyong\'o, Pedro Pascal, Kit Connor, Bill Nighy', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(39, 245891, 'John Wick', 'john-wick', 'An ex-hit-man comes out of retirement to track down the gangsters that killed his dog and took everything from him. A sleek, stylish action thriller that redefined the genre.', 'Action', 2014, 101, 7.4, 'https://image.tmdb.org/t/p/w500/fZPSd91yGE9fCcCe6OoQr6E3Bev.jpg', 'https://image.tmdb.org/t/p/w1280/umC04Cozevu8nn3ZTIU9rp7qIed.jpg', 'https://www.youtube.com/watch?v=2AUmvWm5ZDQ', '', 'Chad Stahelski', 'Keanu Reeves, Michael Nyqvist, Alfie Allen, Adrianne Palicki', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(40, 120467, 'The Grand Budapest Hotel', 'the-grand-budapest-hotel', 'A writer encounters the owner of an aging European hotel between the wars and learns of his friendship with a young employee who becomes involved in the theft of a priceless painting and a family battle over a great fortune.', 'Comedy', 2014, 100, 8.1, 'https://image.tmdb.org/t/p/w500/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg', 'https://image.tmdb.org/t/p/w1280/nX5XotM9yprCKarRH4fzOq1VM1J.jpg', 'https://www.youtube.com/watch?v=1Fg5iWmQjwk', '', 'Wes Anderson', 'Ralph Fiennes, Tony Revolori, Saoirse Ronan, F. Murray Abraham', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(41, 508442, 'Soul', 'soul-pixar', 'After landing the gig of his life, a New York jazz pianist suddenly finds himself trapped in a strange land between Earth and the afterlife. A Pixar masterpiece about finding your spark.', 'Animation', 2020, 100, 8.1, 'https://image.tmdb.org/t/p/w500/hm58Jw4Lw8OIeECIq5qyPYhAeRJ.jpg', 'https://image.tmdb.org/t/p/w1280/kf456ZqeC45XTvo6W9pW5clYKfQ.jpg', 'https://www.youtube.com/watch?v=xOsLIiBStEs', '', 'Pete Docter', 'Jamie Foxx, Tina Fey, Graham Norton, Rachel House', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(42, 354912, 'Coco', 'coco-pixar', 'Aspiring musician Miguel, confronted with his family\'s ancestral ban on music, enters the Land of the Dead to find his great-great-grandfather, a legendary singer.', 'Animation', 2017, 105, 8.4, 'https://image.tmdb.org/t/p/w500/gGEsBPAijhVUFoiNpgZXqRVWJt2.jpg', 'https://image.tmdb.org/t/p/w1280/askg3SMvhqEl4OL52YuvdtY40Yb.jpg', 'https://www.youtube.com/watch?v=Ga6RYejo6Hk', '', 'Lee Unkrich', 'Anthony Gonzalez, Gael García Bernal, Benjamin Bratt, Alanna Ubach', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(43, 447332, 'A Quiet Place', 'a-quiet-place', 'In a post-apocalyptic world, a family is forced to live in near silence while hiding from creatures that hunt by sound. John Krasinski\'s masterfully tense horror film.', 'Horror', 2018, 90, 7.5, 'https://image.tmdb.org/t/p/w500/nAU74GmpUk7t5iklEp3bufwDq4n.jpg', 'https://image.tmdb.org/t/p/w1280/roYyPiQKEzLxY5UKvbfIuvhGpeO.jpg', 'https://www.youtube.com/watch?v=WR7cc5t7tv8', '', 'John Krasinski', 'Emily Blunt, John Krasinski, Millicent Simmonds, Noah Jupe', 0, 0, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(44, 974576, 'Conclave', 'conclave', 'One of the world\'s most secretive and ancient events unfolds as a group of cardinals gather to elect a new Pope, only to uncover a trail of secrets left behind by the recently deceased pontiff.', 'Drama', 2024, 120, 7.6, 'https://image.tmdb.org/t/p/w500/vYEyxF1UT779RiEalpMjUT6kfdf.jpg', 'https://image.tmdb.org/t/p/w1280/eZzNdjNDvaSoyywy9ICg2UmFwul.jpg', 'https://www.youtube.com/watch?v=ey0hxdLHVz0', '', 'Edward Berger', 'Ralph Fiennes, Stanley Tucci, John Lithgow, Isabella Rossellini', 0, 1, '2026-02-19 15:53:14', '2026-02-27 09:16:54'),
(45, 792307, 'Poor Things', 'poor-things', 'The incredible tale about the fantastical evolution of Bella Baxter, a young woman brought back to life by the brilliant and unorthodox scientist Dr. Godwin Baxter.', 'Drama', 2023, 141, 8.0, 'https://image.tmdb.org/t/p/w500/kCGlIMHnOm8JPXq3rXM6c5wMxcT.jpg', 'https://image.tmdb.org/t/p/w1280/bQS43HSLZzMjZkcHJz4fGc9fSTi.jpg', 'https://www.youtube.com/watch?v=RlbR5N6veqw', '', 'Yorgos Lanthimos', 'Emma Stone, Mark Ruffalo, Willem Dafoe, Ramy Youssef', 0, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13'),
(46, 558449, 'Gladiator II', 'gladiator-ii', 'After his home is conquered by the tyrannical emperors who now lead Rome, Lucius is forced to enter the Colosseum and must look to his past to find strength to return the glory of Rome to its people.', 'Action', 2024, 148, 7.1, 'https://image.tmdb.org/t/p/w500/2cxhvwyEwRlysAmRH4iodkvo0z5.jpg', 'https://image.tmdb.org/t/p/w1280/euYIwmwkmz95mnXvufEqHG9BOIA.jpg', 'https://www.youtube.com/watch?v=bKQAMBhMFW0', '', 'Ridley Scott', 'Paul Mescal, Pedro Pascal, Denzel Washington, Connie Nielsen', 1, 1, '2026-02-19 15:53:14', '2026-02-25 12:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `movie_categories`
--

CREATE TABLE `movie_categories` (
  `movie_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_categories`
--

INSERT INTO `movie_categories` (`movie_id`, `category_id`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 7),
(2, 2),
(2, 3),
(2, 4),
(2, 7),
(3, 2),
(3, 3),
(3, 4),
(4, 2),
(4, 3),
(4, 7),
(5, 2),
(5, 3),
(5, 4),
(5, 7),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(7, 2),
(7, 3),
(7, 4),
(8, 2),
(8, 3),
(8, 4),
(8, 7),
(9, 2),
(9, 3),
(9, 4),
(9, 7),
(10, 2),
(10, 3),
(10, 7),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 7),
(12, 2),
(12, 3),
(12, 4),
(12, 5),
(13, 2),
(13, 3),
(13, 4),
(13, 6),
(14, 3),
(14, 4),
(15, 3),
(15, 4),
(16, 3),
(16, 4),
(16, 5),
(17, 1),
(17, 3),
(17, 4),
(18, 1),
(18, 3),
(18, 4),
(19, 1),
(19, 4),
(20, 3),
(20, 4),
(21, 2),
(21, 3),
(22, 7),
(23, 2),
(23, 3),
(24, 2),
(24, 3),
(24, 6),
(24, 7),
(25, 5),
(26, 1),
(26, 3),
(26, 4),
(26, 5),
(28, 6),
(29, 3),
(29, 4),
(30, 3),
(31, 3),
(31, 5),
(32, 5),
(33, 3),
(34, 3),
(34, 6),
(35, 2),
(35, 3),
(36, 3),
(36, 4),
(37, 6),
(38, 6),
(39, 4),
(40, 3),
(41, 3),
(41, 8),
(42, 3),
(42, 8),
(43, 6),
(44, 1),
(44, 2),
(44, 3),
(44, 4),
(44, 5),
(45, 1),
(45, 3),
(45, 4),
(46, 1),
(46, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@cineverse.com', '$2y$10$.fs3Jkl664WUDn4IIJC5VeAGM135DJcjOEYgUx0kNmxe6dKjRTPba', 'admin', NULL, '2026-02-19 15:53:25', '2026-02-19 15:53:25'),
(2, 'John Doe', 'john@example.com', '$2y$10$5A072nn9cUJN98HEVODiceyUurf2n9DtIH11UybQMP3wTYcrVVt7e', 'client', NULL, '2026-02-19 15:53:25', '2026-02-19 15:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `watch_history`
--

CREATE TABLE `watch_history` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `movie_id` int(11) UNSIGNED NOT NULL,
  `progress_seconds` int(11) NOT NULL DEFAULT 0,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `watched_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_movie_id` (`user_id`,`movie_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_tmdb_id` (`tmdb_id`);

--
-- Indexes for table `movie_categories`
--
ALTER TABLE `movie_categories`
  ADD PRIMARY KEY (`movie_id`,`category_id`),
  ADD KEY `fk_movie_categories_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watch_history`
--
ALTER TABLE `watch_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_movie_id` (`user_id`,`movie_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `watch_history`
--
ALTER TABLE `watch_history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movie_categories`
--
ALTER TABLE `movie_categories`
  ADD CONSTRAINT `fk_movie_categories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movie_categories_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `watch_history`
--
ALTER TABLE `watch_history`
  ADD CONSTRAINT `fk_watch_history_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_watch_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
