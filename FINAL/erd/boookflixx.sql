-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 11:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boookflixx`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `category_id`, `title`, `author`, `price`, `stock`, `description`, `image`) VALUES
(4, 3, 'Pet cemetary', 'Stephen king', 3000.00, 7, 'fter tragedy strikes, a grieving father discovers an ancient burial ground behind his home with the power to raise the dead. Dr. Louis Creed\'s (Midkiff) family moves into the country house of their dreams and discover a pet cemetery at the back of their property.', 'uploads/1734489943_images.jpg'),
(5, 6, 'DUNE', 'FRANK HERBERT', 2500.00, 3, 'Set on the desert planet Arrakis, Dune is the story of the boy Paul Atreides, heir to a noble family tasked with ruling an inhospitable world where the only thing of value is the “spice” melange, a drug capable of extending life and enhancing consciousness. Coveted across the known universe, melange is a prize worth killing for...', 'uploads/1734520853_Dune.png'),
(6, 10, 'DETECTIVE FICTION', 'WILLIAM WELLS', 2500.00, 3, 'A serial killer is on the loose in Naples, Florida, an enclave of wealth and privilege on the Southwest Gulf Coast. At first, the murders have been disguised as accidents, but when Police Chief Wade Hansen becomes suspicious, Mayor Charles Beaumont orders him to apprehend the killer before the truth becomes public knowledge.', 'uploads/1734521065_Detective Fiction.png'),
(7, 3, 'The Amityville Horror', 'Jay Anson', 1700.00, 1, 'In December 1975, the Lutz family moved into their dream home, the same home where Ronald DeFeo had murdered his parents, brothers and sisters just one year earlier.\r\n\r\nThe psychic phenomena that followed created the most terrifying experience the Lutz family had ever encountered, forcing them to flee the house in 28 days, convinced that it was possessed by evil spirits.', 'uploads/1734521179_Horror.png'),
(8, 7, 'Golden Curse', 'M. Lynn', 2200.00, 5, 'A curse. A hidden identity. A dangerous love.\r\n\r\nTen year old Persinette Basile was forced to flee the palace of Gaule for her life.\r\n\r\nNow at eighteen, she must find a way to return in order to obey a curse on her family line.\r\n\r\nThe prince won’t know who she is. Not anymore. But she knows him and what he will do if he discovers her true name.\r\n\r\nMade to fight for her life to earn her place, she vows to find a way to break the curse no matter the cost.', 'uploads/1734521583_Golden.png'),
(9, 5, 'Frida: A Biography of Frida Kahlo', 'Hayden Herrera', 2350.00, 9, 'Hailed by readers and critics across the country, this engrossing biography of Mexican painter Frida Kahlo reveals a woman of extreme magnetism and originality, an artist whose sensual vibrancy came straight from her own experiences: her childhood near Mexico City during the Mexican Revolution; a devastating accident at age eighteen that left her crippled and unable to bear children.', 'uploads/1734521693_Frida.png'),
(10, 7, 'The Chronicles of Narnia (Publication Order) #1 The Lion, the Witch and the Wardrobe', 'C.S. Lewis', 2600.00, 0, 'They open a door and enter a world NARNIA...the land beyond the wardrobe, the secret country known only to Peter, Susan, Edmund, and Lucy...the place where the adventure begins. Lucy is the first to find the secret of the wardrobe in the professor\'s mysterious old house. At first, no one believes her when she tells of her adventures in the land of Narnia. But soon Edmund and then Peter and Susan discover the Magic and meet Aslan, the Great Lion, for themselves. In the blink of an eye, their lives are changed forever.', 'uploads/1734522441_narnia.jpg'),
(11, 1, 'The Twilight Saga - New Moon: Music from the Motion Picture Soundtrack', 'Hal Leonard Corporation', 2200.00, 4, 'This is not the work if fiction. This is a book of musical scores.\r\nEasy Piano Licensed Art & SdtkOur songbook matching the soundtrack to the hit Twilight sequel features indie/alt-rock originals written exclusively for the film. Includes Death Cab for Cutie\'s lead single \"Meet Me on the Equinox\" plus songs by Thom Yorke, Muse, Bon Iver, Band of Skulls, Sea Wolf, Lykke Li and others. 15 tunes in all: Done All Wrong * Friends * Monsters * New Moon (The Meadow) * No Sound but the Wind * The Violet Hour * A White Demon Love Song * and more.', 'uploads/1734522582_twilight.jpg'),
(12, 6, '(The Hunger Games Companions) The Hunger Games: Official Illustrated Movie Companion', 'Kate Egan', 2360.00, 2, 'Go behind the scenes of the making of The Hunger Games with exclusive images and interviews. From the screenwriting process to the casting decisions to the elaborate sets and costumes to the actors\' performances and directors\' vision, this is the definitive companion to the breathtaking film.', 'uploads/1734522660_hunger.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Romancia', 'none'),
(2, 'Action', NULL),
(3, 'Horror', NULL),
(4, 'Fiction', NULL),
(5, 'Biography', NULL),
(6, 'Science Fiction', NULL),
(7, 'Fantasy', NULL),
(8, 'Historical', NULL),
(9, 'Adventure', NULL),
(10, 'Msytery', NULL),
(11, 'gore', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_date`) VALUES
(1, 2, 2500.00, '2025-01-13 14:30:03'),
(2, 2, 0.00, '2025-01-13 14:34:56'),
(3, 2, 0.00, '2025-01-13 14:35:32'),
(4, 2, 5000.00, '2025-01-13 15:04:33'),
(5, 2, 1700.00, '2025-01-14 02:59:52'),
(6, 2, 6000.00, '2025-01-16 21:50:07'),
(7, 2, 3000.00, '2025-01-16 21:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'addshit', 'shittyadmin123', '', 'admin', '2025-01-13 12:40:45'),
(2, 'user', 'user', '', 'user', '2025-01-13 12:40:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
