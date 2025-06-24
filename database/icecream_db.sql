-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 05:35 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `icecream_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '1',
  `added_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `price`, `qty`, `added_on`) VALUES
(2, 'gP3HfqRS2N2JQKZVB5MR', 'P002', '0.00', 1, '2025-03-15 17:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'Thick Shake'),
(2, 'Shakes'),
(3, 'Juices'),
(4, 'Ice Cream'),
(6, 'Cake');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `subject`, `message`) VALUES
('Li1jK9f2Y6yRekSRtkan', '9QBOLgPAvXFWicJsBmMY', 'ANUSHKA', 'randomperson@gmail.com', 'for upload picture', 'how to uploaded it');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(20) NOT NULL,
  `address_type` varchar(10) NOT NULL,
  `method` varchar(50) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `qty` varchar(2) NOT NULL,
  `dates` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL DEFAULT 'in progress',
  `payment_status` varchar(100) NOT NULL DEFAULT 'pending'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `seller_id`, `name`, `email`, `address`, `address_type`, `method`, `product_id`, `price`, `qty`, `dates`, `status`, `payment_status`) VALUES
('O001', 'U001', 101, 'Rahul Sharma', 'rahul@example.com', 'Delhi', 'Home', 'COD', 'P001', '120', '50', '2024-02-09 18:30:00', 'delivered', 'paid'),
('O002', 'U002', 101, 'Anjali Verma', 'anjali@example.com', 'Mumbai', 'Office', 'UPI', 'P001', '120', '30', '2024-02-11 18:30:00', 'delivered', 'paid'),
('O003', 'U003', 101, 'Rohit Kumar', 'rohit@example.com', 'Bangalore', 'Home', 'COD', 'P005', '199', '1', '2024-02-14 18:30:00', 'Shipped', 'Paid'),
('O004', 'U004', 102, 'Sana Sheikh', 'sana@example.com', 'Kolkata', 'Home', 'Card', 'P002', '90', '15', '2024-01-09 18:30:00', 'Shipped', 'Paid'),
('O005', 'U005', 101, 'Deepak Mehta', 'deepak@example.com', 'Hyderabad', 'Office', 'COD', 'P003', '150', '25', '2024-03-19 18:30:00', 'Shipped', 'Paid'),
('O006', 'U006', 101, 'Pooja Yadav', 'pooja@example.com', 'Pune', 'Home', 'Card', 'P001', '120', '10', '2024-02-04 18:30:00', 'delivered', 'paid'),
('O007', 'U007', 102, 'Amit Kumar', 'amit@example.com', 'Chennai', 'Office', 'UPI', 'P002', '90', '8', '2024-02-07 18:30:00', 'Shipped', 'Paid'),
('O008', 'U008', 103, 'Neha Sharma', 'neha@example.com', 'Delhi', 'Home', 'COD', 'P004', '130', '6', '2024-02-19 18:30:00', 'Shipped', 'Paid'),
('O009', 'U009', 101, 'Karan Patel', 'karan@example.com', 'Surat', 'Home', 'UPI', 'P001', '120', '50', '2024-02-09 18:30:00', 'delivered', 'paid'),
('O010', 'U010', 103, 'Simran Kaur', 'simran@example.com', 'Amritsar', 'Office', 'Card', 'P004', '130', '18', '2024-04-01 18:30:00', 'Shipped', 'Paid'),
('2f8c1e74-feb0-11ef-9', 'gP3HfqRS2N2JQKZVB5MR', 1, 'Mkics', 'voiceofpenpub@gmail.com', 'aaa', 'Home', 'COD', 'P003', '159.00', '1', '2025-03-11 19:36:56', 'Shipped', 'Paid'),
('46b1ab76-feb0-11ef-9', 'gP3HfqRS2N2JQKZVB5MR', 1, 'Mkics', 'voiceofpenpub@gmail.com', 'aaa', 'Home', 'COD', 'P003', '159.00', '1', '2025-03-11 19:37:35', 'In Progress', 'Pending'),
('0050253e-00ed-11f0-8', 'gP3HfqRS2N2JQKZVB5MR', 1, 'Mkics', 'voiceofpenpub@gmail.com', 'qwert', 'Office', 'Online Payment', 'P008', '299.00', '1', '2025-03-14 15:57:16', 'In Progress', 'Paid'),
('6bca771e-00ef-11f0-8', 'gP3HfqRS2N2JQKZVB5MR', 1, 'Mkics', 'voiceofpenpub@gmail.com', 'qwert', 'Office', 'Online Payment', 'P008', '299.00', '1', '2025-03-14 16:14:35', 'Shipped', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` varchar(20) NOT NULL,
  `seller_id` int(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(225) NOT NULL,
  `product_id` varchar(20) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `price`, `image`, `stock`, `category`, `product_id`, `status`, `description`) VALUES
('P002', 104, 'Pineapple Coconut Shake ', 299, 'pineapple_coconut_shake.jpg', 0, 'Shakes', 'P002', 'active', 'Fresh Pineapple pulp with Coconut Cream'),
('P003', 104, 'Blueberry Shake', 159, 'blueberry_shake.jpg', 25, 'Thick Shakes', 'P003', 'active', 'Blueberry Shake From Fresh Blueberries with Cream'),
('P004', 104, 'Strawberry Shake', 110, 'strawberry_cheesecake_shake.jpg', 12, 'Shakes', 'P004', 'active', 'Strawberry blended with milk & cream.'),
('P005', 104, 'Mango Juice', 99, 'mango_juice.jpg', 15, 'Juice', '', 'active', 'Fresh Squeezed Alphanzo Mango Juice'),
('P006', 104, 'Mocha Frappe', 199, 'mocha.jpg', 10, 'Thick Shake', 'P006', 'active', 'Rich and creamy mocha frappe topped with whipped cream'),
('P007', 104, 'Chocolate Ice Cream', 129, 'chocolate_icecream.jpg', 30, 'Ice Cream', 'P007', 'active', 'Smooth and creamy chocolate ice cream'),
('P008', 104, 'Vanilla Ice Cream', 299, 'vanilla_icecream.jpg', 22, 'Ice Cream', 'P008', 'active', 'Classic vanilla ice cream with real vanilla beans'),
('P012', 104, 'Carrot Beetroot Juice', 99, 'carrot_beetroot_juice.jpg', 18, 'Juice', 'P012', 'active', 'Fresh Carrot and Beetroot Juice Juice'),
('P013', 104, 'Watermelon Juice', 99, 'watermelon_juice.jpg', 10, 'Juice', 'P013', 'active', 'Fresh pulp juice of watermelon with no chemical'),
('P014', 104, 'Chocolate Scope', 199, 'sub-banner-img.png', 2, 'Ice Cream', 'P014', 'active', 'Chocolate Scope with choco rolls');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE IF NOT EXISTS `sellers` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `name`, `email`, `password`, `image`) VALUES
(101, 'Anushka khichar', 'anushkakhichar999@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'p6dOXhj0qZw8gN1uCxxS.jpg'),
(102, 'Mkics', 'm@mail.com', '@Mkics123**', 'abc.jpg'),
(103, 'Anonymous', 'abc@mail.com', 'b0d44150ea3dfd77505ed2840e7076b71367b766', 'Zsx7xqw9bUeW8OrXDBGr.jpg'),
(104, 'Anonymous', 'aab@mail.com', 'b0d44150ea3dfd77505ed2840e7076b71367b766', 'cOoNkcUgAM90r3omrIkG.jpg'),
(105, 'Mkics', 'mkics@mail.com', '70fc56c6e5d0d398eb35e76a6804927529360adf', 'MARfpoAMKP93OFWtRd9z.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`) VALUES
('iquGia8wr7KtVCPY4i4y', 'abc', 'm@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'pYfwpsUxjgYbZWvGTZu3.jpg'),
('MidsxM0Ua5RgUxFfz5hB', 'Anushka khichar', 'anushkakhichar99@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'xNYdebroAZrChp6PUqY0.jpg'),
('9QBOLgPAvXFWicJsBmMY', 'abc', 'abc@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'FpR9ITd95AZzNd9UpoAI.avif'),
('UZO4wfxut6togd7hcPXp', 'abcc', 'abcc@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '8c6TyzWl6720fV50e0z0.jpg'),
('', 'selena ansari', 'selenaansari1@gmail.com', '$2y$10$T6lQ85ZkztjlmbfqfCB05.wI7Rv0RYGldU1tAxm2V8q', ''),
('K117ngKgo8h277y127Yb', 'random person', 'randomperson@gmail.com', '4eedab0f586d2aa202ae1be9a01a4b612dfa919d', 'FrXCBGwxTqgoTp59lvoU.jpg'),
('7WCZ51KptR7vRFrdJZWi', 'selena ansari', 'anushkakhichar9@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'j96ZxOUDPUNOqtDUEJ4C.jpg'),
('myHCGR9MUxhd6uQqJPKm', 'Anushka khichar', 'anushkakhichar999@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'XAa8WxKUkiQeDK7UPlPz.jpg'),
('8cjWtBquuKvHKthEPdr7', '', '', 'da39a3ee5e6b4b0d3255bfef95601890afd80709', 'FH3jDDwPklgvFV1aun6f.'),
('VvUhQgsfPgOrayr8gtH4', 'eee', '8dbb@gmail.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'BqaeiuJAjrHsRsG8L4OM.png'),
('2lnmku7ZPX40F9texoqV', 'eee', '8dbb@gmail.com', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'wU2nri9csiybJX4sQVKI.png'),
('gjXTmltWveovwk81W9KE', 'eee', 'anushkakhichar@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 'qaMzDTp0wMUi3P9fPVwW.png'),
('nTUQwWllWRWa5hQpRqvH', 'abc', 'abc@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'wWBBwWkyc88lgyfLdYxc.png'),
('pN1epqMyA9tT3M4BP2jn', 'anuuu', 'anushkakhichar9999@gmail.com', 'db37045211370c214813c0f854f7890e58f74c40', 'XRfTTJRYpFH2gKqYoBOt.png'),
('97BUaHXdsvwqJZpKIoJ7', 'Mkics', 'm@mail.com', '2b0ccc7643a2562ad76f7f0f2de4ac360d738d71', ''),
('gP3HfqRS2N2JQKZVB5MR', 'Mkics', 'voiceofpenpub@gmail.com', 'cdcbac9dcf48c352a6147aa4471db44d4e2b16e9', '67c886625a639.jpg'),
('9TSaK4IyE2pbwx2gOJqQ', 'M K Institute', 'mk@mail.com', 'b0d44150ea3dfd77505ed2840e7076b71367b766', '9kcZzAhxEqnDOgsmgSxE.jpg'),
('DlMVywkv39FQtfCvsSUa', 'M K Institute', 'salenaansari12@gmail.com', 'b0d44150ea3dfd77505ed2840e7076b71367b766', 'cq1K9itBJuF79m1eSuJs.jpg'),
('U118', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`product_id`),
  KEY `idx_product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`) VALUES
(23, 'gP3HfqRS2N2JQKZVB5MR', 'P003'),
(19, 'gP3HfqRS2N2JQKZVB5MR', 'P006'),
(21, 'gP3HfqRS2N2JQKZVB5MR', 'P008');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
