-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 11:22 AM
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
-- Database: `swiss_collection`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `Address_ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Address_Tag` varchar(50) NOT NULL,
  `Address_Text` text NOT NULL,
  `State_ID` int(50) NOT NULL,
  `City_ID` int(50) NOT NULL,
  `IsActive` tinyint(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`Address_ID`, `user_id`, `Address_Tag`, `Address_Text`, `State_ID`, `City_ID`, `IsActive`) VALUES
(5, 77, '', 'Gayatri Society,Katargam', 0, 0, 1),
(6, 78, 'Home', 'Ram Nagar Socity', 60, 169, 1),
(7, 79, 'Home', 'Ram Nagar society,Gajera ', 49, 136, 1),
(9, 81, 'Office', 'Ram Nagar society,Gajera ', 5, 8, 1),
(13, 85, 'Home', 'kailesh society,Gajera ', 3, 13, 1),
(14, 86, 'Home', 'Manish Nagar society,Gajera ', 3, 13, 1),
(15, 87, 'Home', 'Manish Nagar society,Gajera ', 52, 146, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(50) NOT NULL,
  `Admin_Name` text NOT NULL,
  `Email_ID` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `CreatedOn` datetime NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `Admin_Name`, `Email_ID`, `Password`, `CreatedOn`, `phone`) VALUES
(7, 'Vikas Luhar', 'vikas@gmail.com', '$2y$10$yWBVwNOTEmZqHdsZSYdla.avcc032d.5.x.DIHqvNby7Ohm67G3..', '2025-03-26 10:48:44', '9925141355'),
(9, 'admin', 'admin@gmail.com', '$2y$10$1qW3LKhlUGurnCe5unTHNe/sflJZA/DlHsvhlupb5DGjsNr9c7c5a', '2025-05-01 15:23:52', '8547452145');

-- --------------------------------------------------------

--
-- Table structure for table `admin_commission`
--

CREATE TABLE `admin_commission` (
  `ID` int(11) NOT NULL,
  `Order_ID` int(11) DEFAULT NULL,
  `Seller_ID` int(11) DEFAULT NULL,
  `Commission_Percentage` decimal(5,2) DEFAULT NULL,
  `Commission_Amount` decimal(10,2) DEFAULT NULL,
  `CreatedON` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_commission`
--

INSERT INTO `admin_commission` (`ID`, `Order_ID`, `Seller_ID`, `Commission_Percentage`, `Commission_Amount`, `CreatedON`) VALUES
(15, 376, 15, 10.00, 1000.00, '2025-04-08 09:53:14'),
(16, 377, 17, 10.00, 400.00, '2025-04-08 10:17:54'),
(17, 378, 15, 10.00, 500.00, '2025-04-08 10:27:52'),
(18, 379, 15, 10.00, 500.00, '2025-04-08 10:32:08'),
(19, 380, 15, 10.00, 1000.00, '2025-04-08 12:00:40'),
(20, 381, 17, 10.00, 400.00, '2025-04-09 04:29:29'),
(21, 382, 15, 10.00, 120.00, '2025-04-09 04:31:05'),
(23, 384, 15, 10.00, 450.00, '2025-04-09 17:23:20'),
(24, 385, 17, 10.00, 600.00, '2025-04-09 17:31:02'),
(25, 386, 17, 10.00, 200.00, '2025-04-10 06:58:55'),
(26, 387, 15, 10.00, 500.00, '2025-04-10 08:55:00'),
(27, 387, 17, 10.00, 1500.00, '2025-04-10 08:55:00'),
(28, 388, 17, 10.00, 300.00, '2025-04-11 07:06:14'),
(29, 389, 17, 10.00, 400.00, '2025-04-11 07:07:01'),
(30, 390, 17, 10.00, 1000.00, '2025-04-11 08:26:56');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `status`) VALUES
(1, 'Marble', 0),
(80, 'Tile', 0),
(81, 'Sofa', 1),
(91, 'Furniture', 0),
(92, 'Lighting', 1),
(97, 'Wall Clocks', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `Feedback_ID` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `rating` int(5) NOT NULL,
  `review` varchar(255) NOT NULL,
  `feedback_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`Feedback_ID`, `customer_name`, `email_id`, `order_id`, `product_name`, `rating`, `review`, `feedback_date`) VALUES
(1, 'Vishal', 'User@gmail.com', '2', 'Sofa', 4, 'Nice Sofa', '2025-03-13'),
(3, 'fenil', 'fenil@gmail.com', '362', 'Nordic Elegance Pendant Lamp', 3, 'Highly recommended\"', '2025-04-07'),
(4, 'fenil', 'fenil@gmail.com', '354', 'Bed SOfa', 5, '\"Excellent quality\"', '2025-04-07'),
(5, 'Sanjay', 'sanjay@gmail.com', '358', 'Lamp', 5, 'Nice Lamp', '2025-04-07'),
(6, 'Sanjay', 'sanjay@gmail.com', '360', 'Nordic Elegance Pendant Lamp', 4, 'Nice Lamp', '2025-04-07'),
(7, 'fenil', 'fenil@gmail.com', '385', 'Nordic Elegance Pendant Lamp', 5, 'Nice', '2025-04-09'),
(8, 'fenil', 'fenil@gmail.com', '389', 'Nordic Elegance Pendant Lamp', 5, 'nice', '2025-04-11'),
(9, 'fenil', 'fenil@gmail.com', '388', 'AuraBeam', 1, 'not good', '2025-04-11');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `Order_ID` int(50) NOT NULL,
  `Product_ID` int(50) NOT NULL,
  `Quantity` int(50) NOT NULL,
  `Amount` int(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`Order_ID`, `Product_ID`, `Quantity`, `Amount`, `Status`, `id`) VALUES
(377, 124, 2, 4000, 'Shipped', 46),
(381, 124, 2, 4000, 'Shipped', 50),
(385, 124, 3, 6000, 'Pending', 54),
(386, 124, 1, 2000, 'Pending', 55),
(388, 126, 1, 3000, 'Cancelled', 58),
(389, 124, 2, 4000, 'Pending', 59),
(390, 129, 1, 10000, 'Pending', 60);

-- --------------------------------------------------------

--
-- Table structure for table `payment_transaction`
--

CREATE TABLE `payment_transaction` (
  `PaymentTransaction_ID` int(255) NOT NULL,
  `Order_ID` int(50) NOT NULL,
  `Transaction_ID` int(255) NOT NULL,
  `Payment_Status` tinyint(50) NOT NULL,
  `CreatedOn` date NOT NULL,
  `User_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_desc` text NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `uploaded_date` date NOT NULL DEFAULT current_timestamp(),
  `Seller_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_desc`, `product_image`, `price`, `category_id`, `subcategory_id`, `uploaded_date`, `Seller_ID`) VALUES
(121, 'Tuxedo Sofa', 'A Tuxedo Sofa is a sophisticated and sleek seating option, perfect for modern and contemporary interiors. With its clean lines, boxy silhouette, and straight arms aligned with the backrest, it exudes a sense of structure and refinement.\r\n\r\n✨ Key Features\r\n✔ Straight, High Arms & Back – Creates a balanced, geometric look.\r\n✔ Upholstered in Luxury Materials – Often found in velvet, leather, or high-end fabrics for a premium feel.\r\n✔ Deep, Firm Cushions – Provides a comfortable yet tailored seating experience.\r\n✔ Exposed Legs or Minimal Base – Usually with metal or wooden legs, adding a modern touch.\r\n✔ Minimalist & Chic Design – Perfect for contemporary, mid-century, and luxury interiors.\r\n\r\n🏡 Ideal For\r\n✅ Stylish living rooms\r\n✅ Office lounges & waiting areas\r\n✅ Luxury hotel lobbies\r\n✅ Modern apartments', 'uploads/1743367731_sven-brandsma-GZ5cKOgeIB0-unsplash.jpg', 1500, 81, 38, '2025-03-31', 17),
(124, 'Nordic Elegance Pendant Lamp', '✔ Minimalist Nordic Design – Sleek and elegant, perfect for contemporary spaces\r\n✔ Soft Ambient Lighting – Creates a warm and inviting atmosphere\r\n✔ Premium Build – High-quality metal & wood accents for durability\r\n✔ Versatile Placement – Ideal for dining areas, living rooms, and cafes\r\n✔ Easy Installation – Adjustable height for a customized look', './uploads/jean-philippe-delberghe-Ry9WBo3qmoc-unsplash.jpg', 2000, 92, 46, '2025-04-01', 17),
(125, 'LumaGlow', 'LumaGlow – Designed to Dazzle, Built to Shine\r\nElevate your interior with the stunning allure of LumaGlow, a perfect fusion of sleek design and luxurious lighting. Crafted with clean lines, a minimalist silhouette, and premium finishes, LumaGlow transforms any room into a modern sanctuary. Whether it’s mounted on a wall, suspended above a dining table, or styled on a side table—this piece adds a touch of sophistication without trying too hard.\r\n\r\nThe soft, ambient glow creates a cozy yet contemporary vibe, making it ideal for modern homes, creative studios, and elegant lounges. LumaGlow isn’t just lighting—it’s an experience.', 'uploads/1744353514_cheryl-winn-boujnida-jhDof9B6vPY-unsplash.jpg', 2500, 92, 46, '2025-04-11', 17),
(126, 'AuraBeam', 'AuraBeam – Sculpted Light. Pure Atmosphere.\r\nAuraBeam is where contemporary design meets atmospheric elegance. With its sleek curves, matte-metal finish, and artful silhouette, this light fixture feels more like a piece of modern sculpture than just illumination.\r\n\r\nEngineered to cast a gentle, radiant glow, AuraBeam creates the perfect ambient mood — effortlessly elevating living rooms, lounges, or luxe workspaces. Its soft halo effect wraps your space in warmth, making every corner feel curated and calm.', 'uploads/1744353650_WhatsApp Image 2025-04-11 at 12.10.29_9f53892b.jpg', 3000, 92, 46, '2025-04-11', 17),
(127, 'Velura Luxe', 'Velura Luxe – Where Elegance Meets Indulgence\r\nElevate your living space with the timeless sophistication of Velura Luxe. Wrapped in sumptuously soft velvet and finished with sleek, tailored lines, this sofa is a statement of refined taste. The deep, cushioned seats invite you to sink in, while the gold-accented legs and sculpted silhouette bring a touch of glam to any room.\r\n\r\nDesigned for those who appreciate the finer things, Velura Luxe blends comfort, class, and contemporary charm in perfect harmony. Ideal for modern interiors craving a hint of luxury, it turns everyday lounging into a lavish experience.', 'uploads/1744353808_WhatsApp Image 2025-04-11 at 12.12.21_8cc5843e.jpg', 10000, 81, 39, '2025-04-11', 17),
(128, 'ModNest', 'ModNest – Redefining Minimal Living\r\nSleek, smart, and effortlessly cool — ModNest is the ultimate blend of form and function. With its clean lines, low-profile design, and modular versatility, this sofa adapts to your lifestyle while elevating your space.\r\n\r\nCrafted with structured cushions, a sturdy frame, and premium textured fabric, ModNest delivers both comfort and style without compromise. Rearrange, expand, or reshape — it\'s a modern nest built for dynamic living.\r\nPerfect for urban homes, studios, or anyone who loves a clutter-free aesthetic with maximum impact.', 'uploads/1744353859_WhatsApp Image 2025-04-11 at 12.12.22_77892d4f.jpg', 8000, 81, 38, '2025-04-11', 17),
(129, 'Arco Haven', 'Arco Haven – The Art of Curved Comfort\r\nGracefully sculpted with arched arms and soft, rounded edges, Arco Haven brings a sense of calm elegance to any room. Upholstered in rich, tactile fabric and grounded by a sleek silhouette, it’s designed to be both a statement piece and a sanctuary.\r\n\r\nThe plush seat cushions and supportive backrest offer cloud-like comfort, while its modern curves create a warm, inviting aesthetic that’s effortlessly sophisticated. Whether you\'re curling up with a book or hosting in style, Arco Haven makes every moment feel elevated.\r\n\r\nArco Haven – where modern design meets cozy luxury.', 'uploads/1744353909_WhatsApp Image 2025-04-11 at 12.12.22_edfc9595.jpg', 10000, 81, 40, '2025-04-11', 17),
(130, 'Howard Miller Pendulum Wall Clock', 'Design: A traditional-style wall clock with a beautifully crafted wooden frame, often in finishes like cherry, oak, or mahogany. The clock\'s design is sleek yet ornate, typically with intricate carvings or decorative accents that highlight its vintage appeal.\r\n\r\nPendulum: The swinging pendulum beneath the clock face adds a dynamic element, often in a polished brass or gold finish, which creates an elegant and calming presence in any space.\r\n\r\nClock Face: A classic white or cream-colored dial with Arabic or Roman numerals and gold-tone hour and minute hands. Some models may feature additional features like a moon phase dial or a decorative bezel.\r\n\r\nChimes: Many Howard Miller pendulum clocks come with built-in chimes that strike every hour, adding a melodic sound to your space. You can typically choose from Westminster chimes or other traditional chime options.\r\n\r\nMaterials: Crafted from high-quality wood, with a smooth finish and subtle sheen, ensuring durability and an upscale look.', 'uploads/1744356206_IMG-20250411-WA0026.jpg', 2500, 97, 47, '2025-04-11', 17),
(131, 'Seiko Classic Wall Clock', 'Design: The Seiko Classic Wall Clock features a sleek, minimalist design with a traditional round or square shape, perfect for adding a sophisticated touch to your space without overwhelming it. The clock’s clean lines and simple features make it versatile for both contemporary and classic interiors.\r\n\r\nClock Face: A white or ivory-colored dial with black or gold-tone Arabic numerals, providing clear visibility and a clean, easy-to-read face. The hands are usually finished in gold, black, or silver, giving the clock an upscale look.\r\n\r\nMaterial: Typically crafted with a wooden or metal frame. Wooden options may come in rich finishes like walnut, oak, or cherry, while metal versions can feature brushed chrome or matte black, giving it a more modern and sleek appearance.\r\n\r\nMovement: The clock uses a high-quality quartz movement for accuracy, ensuring reliable timekeeping. Some models may feature a silent sweep movement, eliminating the ticking sound for a quieter environment.', 'uploads/1744356294_IMG-20250411-WA0028.jpg', 2200, 97, 47, '2025-04-11', 17);

-- --------------------------------------------------------

--
-- Table structure for table `seller`
--

CREATE TABLE `seller` (
  `Seller_ID` int(50) NOT NULL,
  `Seller_Name` text NOT NULL,
  `Seller_Shop_Name` text NOT NULL,
  `Seller_Shop_Address` text NOT NULL,
  `Email_Id` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Seller_Mobile_No` bigint(20) DEFAULT NULL,
  `Seller_Shop_Logo` varchar(255) NOT NULL,
  `IsActive` tinyint(50) NOT NULL,
  `IsApproved` tinyint(50) NOT NULL,
  `ApprovedBy` varchar(255) DEFAULT NULL,
  `ApprovedOn` datetime DEFAULT NULL,
  `CreatedOn` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller`
--

INSERT INTO `seller` (`Seller_ID`, `Seller_Name`, `Seller_Shop_Name`, `Seller_Shop_Address`, `Email_Id`, `Password`, `Seller_Mobile_No`, `Seller_Shop_Logo`, `IsActive`, `IsApproved`, `ApprovedBy`, `ApprovedOn`, `CreatedOn`) VALUES
(17, 'vikas', 'Shree Hari Furniture ', 'Ambika Niketan, Athwalines, Surat, Gujarat 395007, India', 'vikas@gmail.com', '$2y$10$5vk8bn4/xK2Y7mIgTirevO.GqRLhnDwjb0k/Jn5NVnoVigj0ebEi6', 8545685245, './uploads/Human Being.png', 1, 1, 'Vikas', '2025-03-30 17:18:48', '2025-03-29'),
(18, 'Jay Mehta', 'Mehta Shop', 'EARTHSPACE, Hazira Rd, Opp ONGC, Surat, Gujarat 394510, India', 'JayM@gmail.com', '$2y$10$s/A3ANjc.Puq4k7h75BU7eHE1YWrS8H5Byv0c9j4KwJ.7Cf6Ydhhu', 2147483647, './uploads/userdefault.png', 1, 1, 'Vikas Luhar', '2025-04-11 08:30:43', '2025-03-30'),
(19, 'Hans', 'Remo Shop', 'Vesu SUrat', 'hans@gmail.com', '$2y$10$kgtgXXSwZEpObQRA0WBBVeu7zd73Qo1f4ncuXoyidbQcDHXvB5uNC', 9874563210, '../uploads/1743326055_Human Being.png', 0, 1, 'Vikas', '2025-03-30 16:14:10', '2025-03-30'),
(20, 'Uday', 'Uday Mera', 'surar', 'uday@gmail.com', '$2y$10$LX5daC37TTMrZ7KjP3967uDG9Gww4Pe27ImnPPgC3PSON.SLozbAC', 8745214578, '../uploads/1743346521_logo.jpeg', 1, 1, 'Vikas Luhar', '2025-04-10 09:49:58', '2025-03-30'),
(21, 'Kushil', 'kushil shop', 'Vesu,Surat', 'kushil@gmail.com', '$2y$10$ap9MtkNyxNs8KffWvHsVm.e..73TDjMPTvSlfT2O6gvYFiVCXxEze', 8545862547, '../uploads/1743349710_Human Being.png', 1, 1, NULL, '2025-04-03 14:52:15', '2025-03-30');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `Sub_category_ID` int(11) NOT NULL,
  `Sub_category_name` text NOT NULL,
  `Category_ID` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`Sub_category_ID`, `Sub_category_name`, `Category_ID`, `status`) VALUES
(15, 'Wall Marble', 80, 0),
(32, 'Granite Marble', 1, 1),
(33, 'Spain Marble', 1, 1),
(38, 'Living Room Sofas', 81, 1),
(39, 'Office & Lounge Sofas', 81, 1),
(40, 'Outdoor & Specialty Sofas', 81, 1),
(44, 'Sofas & lounge chairs', 91, 1),
(45, 'Coffee tables & side tables', 91, 1),
(46, 'Pendant lights & chandeliers', 92, 1),
(47, 'Analog Wall Clocks', 97, 1),
(48, 'Vintage Wall Clocks', 97, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `Cart_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Product_ID` int(50) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(50) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `CreatedOn` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcart`
--

INSERT INTO `tblcart` (`Cart_ID`, `User_ID`, `Product_ID`, `Price`, `Quantity`, `Amount`, `CreatedOn`) VALUES
(47, 31, 86, 410.00, 1, 410.00, '2025-03-25 10:16:36'),
(48, 31, 90, 40.00, 1, 40.00, '2025-03-25 10:26:17'),
(75, 30, 120, 140.00, 2, 280.00, '2025-04-01 10:53:24');

-- --------------------------------------------------------

--
-- Table structure for table `tblcity`
--

CREATE TABLE `tblcity` (
  `City_ID` int(50) NOT NULL,
  `City_Name` text NOT NULL,
  `State_ID` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcity`
--

INSERT INTO `tblcity` (`City_ID`, `City_Name`, `State_ID`) VALUES
(114, 'Visakhapatnam', 42),
(115, 'Vijayawada', 42),
(116, 'Guntur', 42),
(117, 'Itanagar', 43),
(118, 'Naharlagun', 43),
(119, 'Pasighat', 43),
(120, 'Guwahati', 44),
(121, 'Dibrugarh', 44),
(122, 'Silchar', 44),
(123, 'Patna', 45),
(124, 'Gaya', 45),
(125, 'Bhagalpur', 45),
(126, 'Raipur', 46),
(127, 'Bhilai', 46),
(128, 'Bilaspur', 46),
(129, 'Panaji', 47),
(130, 'Margao', 47),
(131, 'Vasco da Gama', 47),
(132, 'Ahmedabad', 48),
(133, 'Surat', 48),
(134, 'Vadodara', 48),
(135, 'Chandigarh', 49),
(136, 'Faridabad', 49),
(137, 'Gurgaon', 49),
(138, 'Shimla', 50),
(139, 'Manali', 50),
(140, 'Dharamshala', 50),
(141, 'Ranchi', 51),
(142, 'Jamshedpur', 51),
(143, 'Dhanbad', 51),
(144, 'Bengaluru', 52),
(145, 'Mysuru', 52),
(146, 'Mangalore', 52),
(147, 'Thiruvananthapuram', 53),
(148, 'Kochi', 53),
(149, 'Kozhikode', 53),
(150, 'Bhopal', 54),
(151, 'Indore', 54),
(152, 'Gwalior', 54),
(153, 'Mumbai', 55),
(154, 'Pune', 55),
(155, 'Nagpur', 55),
(156, 'Imphal', 56),
(157, 'Thoubal', 56),
(158, 'Bishnupur', 56),
(159, 'Shillong', 57),
(160, 'Tura', 57),
(161, 'Nongstoin', 57),
(162, 'Aizawl', 58),
(163, 'Lunglei', 58),
(164, 'Champhai', 58),
(165, 'Kohima', 59),
(166, 'Dimapur', 59),
(167, 'Mokokchung', 59),
(168, 'Bhubaneswar', 60),
(169, 'Cuttack', 60),
(170, 'Rourkela', 60),
(171, 'Chandigarh', 61),
(172, 'Ludhiana', 61),
(173, 'Amritsar', 61),
(174, 'Jaipur', 62),
(175, 'Jodhpur', 62),
(176, 'Udaipur', 62),
(177, 'Gangtok', 63),
(178, 'Namchi', 63),
(179, 'Gyalshing', 63),
(180, 'Chennai', 64),
(181, 'Coimbatore', 64),
(182, 'Madurai', 64),
(183, 'Hyderabad', 65),
(184, 'Warangal', 65),
(185, 'Nizamabad', 65),
(186, 'Agartala', 66),
(187, 'Udaipur', 66),
(188, 'Dharmanagar', 66),
(189, 'Lucknow', 67),
(190, 'Kanpur', 67),
(191, 'Agra', 67),
(192, 'Dehradun', 68),
(193, 'Haridwar', 68),
(194, 'Roorkee', 68),
(195, 'Kolkata', 69),
(196, 'Howrah', 69),
(197, 'Durgapur', 69),
(198, 'Port Blair', 70),
(199, 'Chandigarh', 71),
(200, 'Daman', 72),
(201, 'Diu', 72),
(202, 'Silvassa', 72),
(203, 'Kavaratti', 73),
(204, 'New Delhi', 74),
(205, 'Old Delhi', 74),
(206, 'Puducherry', 75),
(207, 'Karaikal', 75),
(208, 'Mahe', 75),
(209, 'Yanam', 75),
(210, 'Leh', 76),
(211, 'Kargil', 76),
(212, 'Srinagar', 77),
(213, 'Jammu', 77);

-- --------------------------------------------------------

--
-- Table structure for table `tblimages`
--

CREATE TABLE `tblimages` (
  `Image_ID` int(11) NOT NULL,
  `Product_ID` int(50) NOT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `CreatedOn` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblimages`
--

INSERT INTO `tblimages` (`Image_ID`, `Product_ID`, `ImageURL`, `CreatedOn`) VALUES
(1, 78, 'uploads/1742646990_inside-weather-Uxqlfigh6oE-unsplash.jpg', '0000-00-00'),
(2, 78, 'uploads/1742646990_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '0000-00-00'),
(3, 78, 'uploads/1742646990_henry-co-ONnKNBzGWJw-unsplash.jpg', '0000-00-00'),
(4, 0, 'uploads/1742647484_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '2025-03-22'),
(5, 0, 'uploads/1742647484_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-22'),
(6, 81, 'uploads/1742647552_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '2025-03-22'),
(7, 81, 'uploads/1742647552_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-22'),
(8, 0, 'uploads/1742647699_inside-weather-Uxqlfigh6oE-unsplash.jpg', '2025-03-22'),
(9, 0, 'uploads/1742647699_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '2025-03-22'),
(10, 0, 'uploads/1742647699_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-22'),
(11, 82, 'uploads/1742647912_inside-weather-Uxqlfigh6oE-unsplash.jpg', '2025-03-22'),
(12, 82, 'uploads/1742647912_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '2025-03-22'),
(13, 82, 'uploads/1742647912_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-22'),
(14, 83, 'uploads/1742649647_spacejoy-IH7wPsjwomc-unsplash.jpg', '2025-03-22'),
(15, 83, 'uploads/1742649647_hutomo-abrianto-Q_fZcn8vlWY-unsplash.jpg', '2025-03-22'),
(16, 84, 'uploads/1742653168_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '2025-03-22'),
(17, 84, 'uploads/1742653168_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-22'),
(18, 85, 'uploads/1742653209_spacejoy-IH7wPsjwomc-unsplash.jpg', '2025-03-22'),
(19, 85, 'uploads/1742653209_inside-weather-Uxqlfigh6oE-unsplash.jpg', '2025-03-22'),
(20, 85, 'uploads/1742653209_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg', '2025-03-22'),
(21, 86, 'uploads/1742653428_pawel-czerwinski-mfIplTZLE6E-unsplash.jpg,uploads/1742653428_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-22'),
(22, 87, 'uploads/1742655451_premium_photo-1681245141314-fb0b76bf164e.avif,uploads/1742655451_premium_photo-1681414728700-bb88e2c08648.avif', '2025-03-22'),
(23, 88, 'uploads/1742655534_Marble 10 (3).avif,uploads/1742655534_Marble 8.jpg', '2025-03-22'),
(24, 89, 'uploads/1742655893_Marble 11 (2).avif,uploads/1742655893_Marble 10 (3).avif', '2025-03-22'),
(26, 90, 'uploads/1742656020_Marble 6.jpg', '2025-03-22'),
(27, 90, './uploads/ceb9362578e240cbf99c02390287eb5b.jpg', '2025-03-22'),
(28, 90, './uploads/premium_photo-1681412205273-06c73f1c550d.avif', '2025-03-22'),
(32, 91, 'uploads/1742673002_henry-co-ONnKNBzGWJw-unsplash.jpg', '2025-03-23'),
(34, 92, './uploads/inside-weather-dbH_vy7vICE-unsplash.jpg', '2025-03-23'),
(35, 92, './uploads/phillip-goldsberry-fZuleEfeA1Q-unsplash.jpg', '2025-03-23'),
(36, 92, './uploads/kari-shea-ItMggD0EguY-unsplash.jpg', '2025-03-23'),
(37, 93, 'uploads/1742731325_inside-weather-dbH_vy7vICE-unsplash.jpg', '2025-03-23'),
(38, 93, 'uploads/1742731325_phillip-goldsberry-fZuleEfeA1Q-unsplash.jpg', '2025-03-23'),
(40, 94, './uploads/inside-weather-Uxqlfigh6oE-unsplash.jpg', '2025-03-24'),
(41, 95, 'uploads/1742878276_spacejoy-IH7wPsjwomc-unsplash.jpg', '2025-03-25'),
(42, 95, 'uploads/1742878276_inside-weather-Uxqlfigh6oE-unsplash.jpg', '2025-03-25'),
(43, 1, './uploads/kari-shea-ItMggD0EguY-unsplash.jpg', '2025-03-25'),
(45, 96, 'uploads/1742914413_logo.jpeg', '2025-03-25'),
(46, 97, './uploads/Marble 11 (2).avif', '2025-03-25'),
(47, 97, './uploads/Marble 10 (3).avif', '2025-03-25'),
(48, 103, 'uploads/1742917458_photo-1667400104789-f50a4cb393cf.png', '2025-03-25'),
(49, 103, 'uploads/1742917458_Marble 6.png', '2025-03-25'),
(50, 104, 'uploads/1742966042_Marble 6.png', '2025-03-26'),
(51, 105, 'uploads/1743015695_inside-weather-dbH_vy7vICE-unsplash.jpg', '2025-03-27'),
(53, 106, 'uploads/1743048141_premium_photo-1674676471474-7476385674bf.jpeg', '2025-03-27'),
(54, 106, 'uploads/1743048141_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-27'),
(55, 107, 'uploads/1743175140_photo-1667400104789-f50a4cb393cf.png', '2025-03-28'),
(56, 108, 'uploads/1743175321_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(57, 109, 'uploads/1743175470_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(58, 110, 'uploads/1743175521_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(59, 111, 'uploads/1743176008_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(60, 113, 'uploads/1743176702_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(61, 115, 'uploads/1743176726_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(62, 116, 'uploads/1743176761_premium_photo-1674676471474-7476385674bf.jpeg', '2025-03-28'),
(63, 117, 'uploads/1743176852_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-28'),
(64, 118, './uploads/jean-philippe-delberghe-Ry9WBo3qmoc-unsplash.jpg', '2025-03-28'),
(65, 119, 'uploads/1743220831_premium_photo-1674676471474-7476385674bf.jpeg', '2025-03-29'),
(66, 120, 'uploads/1743258252_inside-weather-dbH_vy7vICE-unsplash.jpg', '2025-03-29'),
(67, 121, 'uploads/1743367731_naomi-hebert-2dcYhvbHV-M-unsplash.jpg', '2025-03-31'),
(68, 121, 'uploads/1743367731_premium_photo-1661765778256-169bf5e561a6.jpg', '2025-03-31'),
(69, 122, 'uploads/1743406504_jean-philippe-delberghe-Ry9WBo3qmoc-unsplash.jpg', '2025-03-31'),
(70, 122, 'uploads/1743406504_premium_photo-1674676471474-7476385674bf.jpeg', '2025-03-31'),
(71, 122, 'uploads/1743406504_premium_photo-1672166939372-5b16118eee45.jpeg', '2025-03-31'),
(72, 123, 'uploads/1743487922_premium_photo-1705262413381-5865413c4f48.avif', '2025-04-01'),
(73, 124, 'uploads/1743488524_premium_photo-1695039524489-e36a7fe9a467.avif', '2025-04-01'),
(75, 125, 'uploads/1744353514_premium_photo-1695039524489-e36a7fe9a467.avif', '2025-04-11'),
(76, 126, 'uploads/1744353650_WhatsApp Image 2025-04-11 at 12.10.28_af0ca672.jpg', '2025-04-11'),
(77, 127, 'uploads/1744353808_WhatsApp Image 2025-04-11 at 12.12.22_8df63792.jpg', '2025-04-11'),
(78, 128, 'uploads/1744353859_WhatsApp Image 2025-04-11 at 12.12.22_ba8672c6.jpg', '2025-04-11'),
(79, 129, 'uploads/1744353909_WhatsApp Image 2025-04-11 at 12.12.23_e49f2695.jpg', '2025-04-11'),
(80, 130, 'uploads/1744356206_IMG-20250411-WA0027.jpg', '2025-04-11'),
(81, 131, 'uploads/1744356294_IMG-20250411-WA0024.jpg', '2025-04-11'),
(82, 131, 'uploads/1744356294_IMG-20250411-WA0025.jpg', '2025-04-11');

-- --------------------------------------------------------

--
-- Table structure for table `tblorder`
--

CREATE TABLE `tblorder` (
  `Order_ID` int(50) NOT NULL,
  `User_ID` int(50) NOT NULL,
  `TotalAmount` int(50) NOT NULL,
  `FinalAmount` int(50) NOT NULL,
  `Payment_ID` varchar(100) NOT NULL,
  `Payment_Method` varchar(50) NOT NULL,
  `CreatedON` date NOT NULL,
  `Address_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorder`
--

INSERT INTO `tblorder` (`Order_ID`, `User_ID`, `TotalAmount`, `FinalAmount`, `Payment_ID`, `Payment_Method`, `CreatedON`, `Address_ID`) VALUES
(376, 79, 10000, 10000, 'pay_QGWL4Cb1yy4PTJ', 'Online', '2025-04-08', 7),
(377, 79, 4000, 4000, '', 'COD', '2025-04-08', 7),
(378, 79, 5000, 5000, '', 'COD', '2025-04-08', 7),
(379, 79, 5000, 5000, '', 'COD', '2025-04-08', 7),
(380, 78, 10000, 10000, 'pay_QGYVdTyekJZuDA', 'Online', '2025-04-08', 6),
(381, 78, 4000, 4000, '', 'COD', '2025-04-09', 6),
(382, 78, 1200, 1200, 'pay_QGpNtNvPJ0rmBs', 'Online', '2025-04-09', 6),
(384, 78, 4500, 4500, '', 'COD', '2025-04-09', 6),
(385, 78, 6000, 6000, 'pay_QH2flhyv4rOXU2', 'Online', '2025-04-09', 6),
(386, 79, 2000, 2000, 'pay_QHGRAAVJC8Z0r3', 'Online', '2025-04-10', 7),
(388, 78, 3000, 3000, 'pay_QHf5zd19QgkHzY', 'Online', '2025-04-11', 6),
(389, 78, 4000, 4000, 'pay_QHf6iJdQRyTPmU', 'Online', '2025-04-11', 6),
(390, 79, 10000, 10000, 'pay_QHgTE0DG0BZ4Sz', 'Online', '2025-04-11', 7);

-- --------------------------------------------------------

--
-- Table structure for table `tblstate`
--

CREATE TABLE `tblstate` (
  `State_ID` int(11) NOT NULL,
  `State_Name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstate`
--

INSERT INTO `tblstate` (`State_ID`, `State_Name`) VALUES
(42, 'Andhra Pradesh'),
(43, 'Arunachal Pradesh'),
(44, 'Assam'),
(45, 'Bihar'),
(46, 'Chhattisgarh'),
(47, 'Goa'),
(48, 'Gujarat'),
(49, 'Haryana'),
(50, 'Himachal Pradesh'),
(51, 'Jharkhand'),
(52, 'Karnataka'),
(53, 'Kerala'),
(54, 'Madhya Pradesh'),
(55, 'Maharashtra'),
(56, 'Manipur'),
(57, 'Meghalaya'),
(58, 'Mizoram'),
(59, 'Nagaland'),
(60, 'Odisha'),
(61, 'Punjab'),
(62, 'Rajasthan'),
(63, 'Sikkim'),
(64, 'Tamil Nadu'),
(65, 'Telangana'),
(66, 'Tripura'),
(67, 'Uttar Pradesh'),
(68, 'Uttarakhand'),
(69, 'West Bengal'),
(70, 'Andaman and Nicobar Islands'),
(71, 'Chandigarh'),
(72, 'Dadra and Nagar Haveli and Daman and Diu'),
(73, 'Lakshadweep'),
(74, 'Delhi'),
(75, 'Puducherry'),
(76, 'Ladakh'),
(77, 'Jammu and Kashmir');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user',
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `State_ID` int(11) NOT NULL,
  `City_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`user_id`, `name`, `email`, `phone`, `password`, `user_type`, `created_on`, `profile_image`, `is_active`, `State_ID`, `City_ID`) VALUES
(75, 'jayu', 'jayu@gmail.com', '8745585240', '4fc2c2bb39be2e07a3c18fd489ae2d1e', 'user', '2025-03-31 23:49:58', '', 1, 1, 3),
(76, 'Veerd', 'veerd@gmail.com', '7582541252', '72041462620baf088a93f395627f9938', 'user', '2025-03-31 23:54:17', '', 1, 2, 4),
(77, 'Udit', 'udit@gmail.com', '8565485421', '06fb19d5db6e3594649096d9a9e0b768', 'user', '2025-04-01 00:04:58', 'user_77_1744016907.png', 1, 3, 5),
(78, 'fenil', 'fenil@gmail.com', '7854521888', '560a01a83007e7dfd20d403173e5da3c', 'user', '2025-04-01 00:12:55', 'user_78_1744223418.avif', 1, 60, 169),
(79, 'Sanjay11', 'sanjay@gmail.com', '8541254695', 'd147098b6789c5bc75c13cafad8aea1c', 'user', '2025-04-01 11:19:53', 'user_79_1744035125.avif', 1, 49, 136),
(81, 'jackkk', 'Jack1@gmail.com', '8547474587', '93c8ab60498adb00cb5a01876d67fc91', 'user', '2025-04-07 15:11:16', 'user_81_1744018940.avif', 1, 5, 8),
(85, 'Chahal', 'chahal@gmail.com', '8551478520', '91e6b3fb3a4efef7566b11d7b7a7ed35', 'user', '2025-04-07 15:22:59', NULL, 1, 3, 13),
(86, 'Jayshah', 'jayshah@gmail.com', '8547854255', 'cb86f61581ee06538e3099a855fade54', 'user', '2025-04-08 23:46:08', NULL, 1, 3, 13),
(87, 'Jayshah', 'jay@gmail.com', '8547452445', 'f0e7d0d17cff891edbc9cdf92dcd9297', 'user', '2025-04-10 16:31:49', NULL, 1, 52, 146);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `Wishlist_ID` int(50) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `CreatedOn` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`Address_ID`),
  ADD KEY `State_ID_FK` (`State_ID`),
  ADD KEY `City_ID_FK` (`City_ID`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`);

--
-- Indexes for table `admin_commission`
--
ALTER TABLE `admin_commission`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Order_ID` (`Order_ID`),
  ADD KEY `Seller_ID` (`Seller_ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`Feedback_ID`),
  ADD KEY `order_id_FK` (`order_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Order_ID_FK` (`Order_ID`),
  ADD KEY `Product_ID_FK` (`Product_ID`);

--
-- Indexes for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD PRIMARY KEY (`PaymentTransaction_ID`,`Transaction_ID`),
  ADD KEY `Order_ID_FK` (`Order_ID`),
  ADD KEY `User_ID_FK` (`User_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `Seller_ID` (`Seller_ID`),
  ADD KEY `Sub_categary_Id` (`subcategory_id`);

--
-- Indexes for table `seller`
--
ALTER TABLE `seller`
  ADD PRIMARY KEY (`Seller_ID`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`Sub_category_ID`),
  ADD KEY `Category_ID` (`Category_ID`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`Cart_ID`),
  ADD KEY `User_ID_FK` (`User_ID`),
  ADD KEY `Product_ID_FK` (`Product_ID`);

--
-- Indexes for table `tblcity`
--
ALTER TABLE `tblcity`
  ADD PRIMARY KEY (`City_ID`),
  ADD KEY `State_ID_FK` (`State_ID`);

--
-- Indexes for table `tblimages`
--
ALTER TABLE `tblimages`
  ADD PRIMARY KEY (`Image_ID`),
  ADD KEY `Product_ID_FK` (`Product_ID`);

--
-- Indexes for table `tblorder`
--
ALTER TABLE `tblorder`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `User_ID_FK` (`User_ID`),
  ADD KEY `Address_ID_FK` (`Address_ID`);

--
-- Indexes for table `tblstate`
--
ALTER TABLE `tblstate`
  ADD PRIMARY KEY (`State_ID`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `State_ID` (`State_ID`,`City_ID`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`Wishlist_ID`),
  ADD KEY `Product_ID_FK` (`Product_ID`),
  ADD KEY `User_ID_FK` (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `Address_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin_commission`
--
ALTER TABLE `admin_commission`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `Feedback_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `seller`
--
ALTER TABLE `seller`
  MODIFY `Seller_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `Sub_category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `Cart_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `tblcity`
--
ALTER TABLE `tblcity`
  MODIFY `City_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `tblimages`
--
ALTER TABLE `tblimages`
  MODIFY `Image_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `tblorder`
--
ALTER TABLE `tblorder`
  MODIFY `Order_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391;

--
-- AUTO_INCREMENT for table `tblstate`
--
ALTER TABLE `tblstate`
  MODIFY `State_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`Order_ID`) REFERENCES `tblorder` (`Order_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_Id_FK` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD CONSTRAINT `Category_Id_FK` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_Category` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`category_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Sub_Category_Id_FK` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
