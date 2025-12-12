-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2025 a las 07:33:52
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
-- Base de datos: `facturacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `nombres` varchar(100) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `cedula`, `nombres`, `email`, `telefono`, `direccion`, `creado_en`) VALUES
(1, '1723456789', 'Juan Pérez', 'juan.perez@mail.com', '0991234567', 'Santo Domingo', '2025-12-12 01:12:42'),
(2, '1712345678', 'María González', 'maria.gonzalez@mail.com', '0987654321', 'Quito', '2025-12-12 01:12:42'),
(3, '1809876543', 'Carlos Andrade', 'carlos.andrade@mail.com', '0974567890', 'Ambato', '2025-12-12 01:12:42'),
(5, '1734954030', 'Alex Lora', 'alex@gmail.com', '0978756231', 'Santo Domingo', '2025-12-12 05:27:39'),
(6, '1724955040', 'Angel Sebastian Sosa Suarez', 'ssosa9801@gmail.com', '0990100950', 'Santo Domingo', '2025-12-12 06:24:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_factura`
--

CREATE TABLE `detalles_factura` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total_linea` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_factura`
--

INSERT INTO `detalles_factura` (`id`, `factura_id`, `producto_id`, `cantidad`, `precio_unitario`, `total_linea`) VALUES
(1, 2, 2, 2, 120.50, 241.00),
(4, 4, 7, 1, 850.00, 850.00),
(5, 4, 2, 2, 120.50, 241.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_factura`
--

INSERT INTO `detalle_factura` (`id`, `factura_id`, `producto_id`, `cantidad`, `precio_unitario`, `total`, `creado_en`) VALUES
(1, 27, 9, 1, 2200.00, 2200.00, '2025-12-12 05:35:12'),
(2, 27, 10, 1, 56.89, 56.89, '2025-12-12 05:35:12'),
(3, 28, 10, 1, 56.89, 56.89, '2025-12-12 05:35:29'),
(4, 28, 7, 1, 850.00, 850.00, '2025-12-12 05:35:29'),
(5, 28, 11, 1, 100.00, 100.00, '2025-12-12 05:35:29'),
(6, 28, 9, 1, 2200.00, 2200.00, '2025-12-12 05:35:29'),
(7, 29, 12, 1, 49.99, 49.99, '2025-12-12 06:27:09'),
(8, 29, 13, 1, 99.99, 99.99, '2025-12-12 06:27:09'),
(9, 29, 11, 1, 100.00, 100.00, '2025-12-12 06:27:09'),
(10, 29, 10, 1, 56.89, 56.89, '2025-12-12 06:27:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `numero` varchar(30) DEFAULT NULL,
  `fecha` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `iva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(20) NOT NULL DEFAULT 'EMITIDA',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `cliente_id`, `numero`, `fecha`, `subtotal`, `iva`, `total`, `estado`, `creado_en`) VALUES
(2, 1, 'FAC-1765502012', '2025-12-11', 241.00, 36.15, 277.15, 'EMITIDA', '2025-12-12 01:13:32'),
(4, 2, 'FAC-1765503430', '2025-12-11', 1091.00, 163.65, 1254.65, 'EMITIDA', '2025-12-12 01:37:10'),
(27, 5, 'FAC-1765517712', '2025-12-12', 2256.89, 338.53, 2595.42, 'EMITIDA', '2025-12-12 05:35:12'),
(28, 3, 'FAC-1765517729', '2025-12-12', 3206.89, 481.03, 3687.92, 'EMITIDA', '2025-12-12 05:35:29'),
(29, 6, 'FAC-1765520829', '2025-12-12', 306.87, 46.03, 352.90, 'EMITIDA', '2025-12-12 06:27:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(30) DEFAULT NULL,
  `nombre` varchar(120) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `precio`, `stock`, `activo`, `creado_en`) VALUES
(2, 'P-001', 'Panel solar 450W', 120.50, 10, 1, '2025-12-12 01:07:10'),
(7, 'P-002', 'Inversor Solar 5kW', 850.00, 9, 1, '2025-12-12 01:12:58'),
(8, 'P-065', 'PowerBank', 34.53, 30, 1, '2025-12-12 01:12:58'),
(9, 'P-004', 'Batería de Litio 10kWh', 2200.00, 3, 1, '2025-12-12 01:12:58'),
(10, 'P-003', 'Procesador', 56.89, 7, 1, '2025-12-12 01:44:07'),
(11, 'P-239', 'Pantalla', 100.00, 12, 1, '2025-12-12 05:27:55'),
(12, 'P-223', 'Mouse Logitech', 49.99, 24, 1, '2025-12-12 06:24:57'),
(13, 'P-054', 'Teclado Corsair', 99.99, 6, 1, '2025-12-12 06:25:26'),
(14, 'P-999', 'RTX 4060 AERO', 499.99, 0, 0, '2025-12-12 06:25:51'),
(15, 'P-123', 'Samsung S24 Ultra', 899.99, 0, 0, '2025-12-12 06:26:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_factura`
--
ALTER TABLE `detalles_factura`
  ADD CONSTRAINT `detalles_factura_ibfk_1` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalles_factura_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `fk_det_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_det_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
