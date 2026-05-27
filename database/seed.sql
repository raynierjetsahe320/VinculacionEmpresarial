
-- Usuarios: admin y algunos alumnos de ejemplo
INSERT INTO usuarios(nombre, correo, cuenta, password, rol)
VALUES
('Administrador','admin@aragon.unam.mx','000000001','Admin2026','admin'),
('Alumno Uno','alumno1@comunidad.fes.aragon','202012345','Alumno123','alumno'),
('Alumno Dos','alumno2@comunidad.fes.aragon','202098765','Alumno123','alumno');


-- Edificios reales de ejemplo (A1..A12 + Anexo Derecho + DUACyD)
INSERT INTO edificios(nombre, pisos)
VALUES
('A1 - Ingeniería Civil',3),
('A2 - Mecánica',4),
('A3 - Eléctrica',5),
('A4 - Industrial',4),
('A5 - Computación',5),
('A6 - Laboratorios',3),
('A7 - Arquitectura',2),
('A8 - Biblioteca',4),
('A9 - Administración',3),
('A10 - Coordinaciones',2),
('A11 - Servicios Escolares',2),
('A12 - Posgrado',2),
('Anexo Derecho',2),
('DUACyD',4);

-- Salones (generados sencillos: Sala 101..105 por edificio)
INSERT INTO salones(nombre, edificio_id)
SELECT CONCAT('Sala ', FLOOR(100 + n)) , e.id
FROM (
	SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
) nums
CROSS JOIN edificios e
WHERE n <= 5;

-- Incidencias de ejemplo (algunas creadas por alumnos)
-- Zonas principales donde pueden generarse reportes
INSERT INTO zonas(nombre) VALUES
('Salón Usos Múltiples'),
('Gimnasio'),
('Pesas y Regaderas'),
('Adquisiciones'),
('Instalaciones Académicas y Equipo Audiovisual'),
('Centro de Cómputo'),
('Centro de Lenguas Extranjeras (CELE)'),
('Centro Tecnológico'),
('Biblioteca'),
('Servicio Médico y Comedor'),
('Módulo de Extensión Universitaria'),
('Estacionamiento Techado'),
('Clínica Odontológica Izcalla'),
('Esculturas'),
('Edificio de Gobierno'),
('Torres de la Facultad'),
('Plaza del Estudiante'),
('A-1'),('A-2'),('A-3'),('A-4'),('A-5'),('A-6'),('A-7'),('A-8'),('A-9'),('A-10'),('A-11'),('A-12'),
('Laboratorio L-1'),('Laboratorio L-2'),('Laboratorio L-3'),('Laboratorio L-4'),
('Cancha Básquetbol'),('Cancha Fútbol'),('Cancha Béisbol'),('Cancha Fútbol rápido'),('Cancha Voleibol playero');

-- Incidencias de ejemplo (algunas creadas por alumnos), ahora con zona_id
INSERT INTO incidencias(titulo, descripcion, tipo, prioridad, estado, edificio_id, salon_id, zona_id, user_id)
VALUES
('Baño sin agua','El lavabo no tiene suministro de agua en el segundo piso','Servicios','Alta','Abierta',1,1,1,2),
('Luz dañada','Falta iluminación en el pasillo principal','Iluminación','Media','En proceso',2,2,2,3),
('Aire acondicionado','El equipo no enfría en el aula 203','Climatización','Alta','Abierta',5,3,3,2),
('Puerta dañada','La cerradura del aula 104 no funciona','Seguridad','Media','Abierta',3,4,4,3),
('Equipo de cómputo','PC sin arranque en laboratorio 2','Equipamiento','Alta','Pendiente',5,7,29,2);
