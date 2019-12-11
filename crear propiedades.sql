INSERT INTO `nomenclaturasavance` (`id`, `porcentaje`, `glosa`, `created_at`, `updated_at`, `tipo_tarea`) VALUES
	(1, 0, 'Cotización aceptada por el cliente.', now(), now(), 1),
	(2, 5, 'Tareas giradas a talleres.', now(), now(), 1),
	(3, 10, 'Trabajo a Flote: Inspección abordo efectuada.', now(), now(), 1),
	(4, 15, 'Trabajo en taller: Equipo desmontado - todo o partes en Taller.', now(), now(), 1),
	(5, 25, 'Inspección terminada. Informes Técnicos, Recomendación Reparación y Listado Repuestos en poder de Cliente.', now(), now(), 1),
	(6, 30, 'Recomendación de reparación aprobada. Cotización entregada al cliente.', now(), now(), 1),
	(7, 35, 'Cotización aprobada por el Cliente.', now(), now(), 1),
	(8, 40, 'Tareas emitidas a Talleres.', now(), now(), 1),
	(9, 50, 'Repuestos recibidos por el Taller.', now(), now(), 1),
	(10, 60, 'Proceso de Arme y Ajuste en taller.', now(), now(), 1),
	(11, 70, 'Proceso de Arme y Ajuste en taller terminado.', now(), now(), 1),
	(12, 75, 'Pruebas de Taller terminadas. Trabajo recibido por el Cliente e Inspectoría en Taller.', now(), now(), 1),
	(13, 80, 'Equipo instalado abordo y no probado o trabajo a flote terminado abordo y no probado.', now(), now(), 1),
	(14, 85, 'Trabajo recibido abordo por la Inspectoría y el Cliente. Inspección de Instalación aprobada.', now(), now(), 1),
	(15, 95, 'Trabajo recibido por la Inspectoría y el cliente. Pruebas de Puerto aprobadas.', now(), now(), 1),
	(16, 100, 'Prueba de Mar aprobada. Trabajo terminado.', now(), now(), 1);

INSERT INTO `propiedades_grafico` (`id`, `nombre`, `avance`, `color`) VALUES
	(1, 'A tiempo', 0, '#28a745'),
	(2, 'Advertencia', 60, '#ffff00'),
	(3, 'Peligro', 90, '#f48024'),
	(4, 'Atrasado', 100, '#dc3545'),
	(5, 'Avance', -1, '#074590'),
	(6, 'Porcentaje para verde', 80, '#28a745');

INSERT INTO `tipo_tareas` (`id`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'Trabajo normal', now(), now()),
	(2, 'Trabajo diagnostico', now(), now()),
	(4, 'Obra en mas', now(), now());
