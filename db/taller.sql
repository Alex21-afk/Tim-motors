-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-08-2024 a las 21:17:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `taller`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

CREATE TABLE `gasto` (
  `id` int(11) NOT NULL,
  `material` varchar(200) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_gasto` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gasto`
--

INSERT INTO `gasto` (`id`, `material`, `precio`, `cantidad`, `fecha_gasto`) VALUES
(1, 'Caja de presintos', 10.50, 3, '2024-08-14'),
(2, 'Lijas de 400', 4.00, 30, '2024-08-14'),
(3, 'Prueba', 4.00, 3, '2024-08-14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo`
--

CREATE TABLE `insumo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumo`
--

INSERT INTO `insumo` (`id`, `nombre`, `precio`) VALUES
(36, 'Moto', 120.00),
(37, 'Aceite viejo', 23.00),
(38, 'Frenos ', 12.00),
(43, 'Prueba', 12.00),
(45, 'Luces traceras', 130.00),
(46, 'Motor Nuevo', 700.00),
(47, 'Cascada P', 14.00),
(48, 'Prueba vieja', 12.00),
(49, 'Aceite Motul 20w50', 40.00),
(50, 'Aceite Motul 20w60', 35.00),
(51, 'Kit de arrastre', 150.00),
(52, 'Luces delanteras', 10.00),
(53, 'Luces Led', 19.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo_transacciones`
--

CREATE TABLE `insumo_transacciones` (
  `insumo_id` int(11) NOT NULL,
  `transacciones_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumo_transacciones`
--

INSERT INTO `insumo_transacciones` (`insumo_id`, `transacciones_id`, `cantidad`) VALUES
(36, 4, 2),
(36, 7, 1),
(36, 8, 1),
(37, 3, 2),
(37, 4, 4),
(43, 5, 1),
(49, 7, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`) VALUES
(1, 'Efectivo'),
(2, 'Cartera Movil'),
(3, 'Tarjeta ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `id` int(11) NOT NULL,
  `nombres` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajador`
--

INSERT INTO `trabajador` (`id`, `nombres`) VALUES
(5, 'Alexander Fabian'),
(24, 'Jhon Inche Mego'),
(34, 'Ricardo Mendoza');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago_id` int(11) DEFAULT NULL,
  `trabajador_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`id`, `fecha`, `descripcion`, `monto`, `metodo_pago_id`, `trabajador_id`) VALUES
(1, '2024-08-13', 'Mantenimiento general', 40.00, 1, 5),
(3, '2024-08-13', 'Bajada de motor', 200.00, 2, 24),
(4, '2024-08-13', 'Cambio de Ejez de eva', 120.00, 3, 34),
(5, '2024-08-14', 'Cambio de focos', 40.00, 1, 24),
(6, '2024-08-14', 'Cambio de aceite', 200.00, 1, 5),
(7, '2024-08-14', 'Cambio de focos', 40.00, 1, 5),
(8, '2024-08-14', 'Cambio de todo', 60.00, 1, 34);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `nombre`, `email`) VALUES
(1, 'jhoninche', '1234', 'Victor Jhon Inche Astuhuaman', 'jhon@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `insumo`
--
ALTER TABLE `insumo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `insumo_transacciones`
--
ALTER TABLE `insumo_transacciones`
  ADD PRIMARY KEY (`insumo_id`,`transacciones_id`),
  ADD KEY `transacciones_id` (`transacciones_id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombres` (`nombres`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metodo_pago_id` (`metodo_pago_id`),
  ADD KEY `fk_trabajador_id` (`trabajador_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `gasto`
--
ALTER TABLE `gasto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `insumo`
--
ALTER TABLE `insumo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `insumo_transacciones`
--
ALTER TABLE `insumo_transacciones`
  ADD CONSTRAINT `insumo_transacciones_ibfk_1` FOREIGN KEY (`insumo_id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `insumo_transacciones_ibfk_2` FOREIGN KEY (`transacciones_id`) REFERENCES `transacciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `fk_trabajador_id` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`),
  ADD CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
