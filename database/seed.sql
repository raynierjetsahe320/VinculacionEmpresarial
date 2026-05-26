
INSERT INTO usuarios(nombre, correo, password, rol)
VALUES
('Administrador','admin@aragon.unam.mx','123456','admin');

INSERT INTO edificios(nombre, pisos)
VALUES
('A1 Ingeniería Civil',3),
('A2 Mecánica',4),
('A3 Eléctrica',5),
('A4 Industrial',4),
('A5 Computación',5),
('A6 Laboratorios',3),
('A7 Arquitectura',2),
('A8 Biblioteca',4),
('A9 Administración',3),
('A10 Coordinaciones',2),
('A11 Servicios Escolares',2),
('A12 Posgrado',2),
('DUACyD',4);

INSERT INTO incidencias(titulo, descripcion, prioridad, estado, edificio_id)
VALUES
('Baño sin agua','No funciona el lavabo','Alta','Abierta',1),
('Luz dañada','Sin iluminación','Media','Proceso',2),
('Aire acondicionado','No enfría','Baja','Pendiente',5);
