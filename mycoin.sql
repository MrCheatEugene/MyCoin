CREATE DATABASE `mycoin`;
use `mycoin`;

CREATE TABLE `transactions` (
  `uid` int(11) NOT NULL,
  `rx_addr` int(11) NOT NULL,
  `amount` float NOT NULL,
  `code` int(11) NOT NULL,
  `tid` text NOT NULL,
  `status` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `cards` text DEFAULT NULL,
  `balance` float NOT NULL DEFAULT 0,
  `registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;