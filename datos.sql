
USE sitio_personal;
SET NAMES utf8mb4;

--  Inicio y Acerca de mí 
UPDATE perfil SET
  nombre_completo   = 'Fiorelha Barrantes Ramírez',
  titular           = 'Estudiante de Ingeniería en Sistemas',
  descripcion_corta = 'Me apasiona crear soluciones de software',
  presentacion      = 'Hola, soy Fiorelha, estudiante de Ingeniería en Sistemas. Disfruto aprender cómo funcionan las aplicaciones por dentro: cómo se organizan los datos, cómo se conectan las partes de un sistema y cómo hacer que todo sea fácil de usar.\n\nEste sitio es mi espacio para compartir mi formación, mis proyectos y lo que voy aprendiendo en el camino. Estoy en etapa de crecimiento y con muchas ganas de ganar experiencia en desarrollo de software.',
  acerca_descripcion = 'Soy estudiante de Ingeniería en Sistemas y me interesa el desarrollo de software, el diseño de bases de datos y la creación de sitios web. Me gusta entender un problema antes de programarlo y dividirlo en pasos pequeños hasta que funciona.\n\nTrabajo bien en equipo, hago preguntas cuando algo no me queda claro y disfruto documentar lo que aprendo. Mi meta es graduarme y empezar mi carrera como desarrolladora, creciendo junto a un equipo del que pueda aprender.',
  intereses         = 'Tecnología, Desarrollo web, Bases de datos, Programación, Música',
  habilidades       = 'HTML, CSS, PHP, MySQL, Python, Java, C#, Lógica de programación, Modelado de bases de datos, Trabajo en equipo, Resolución de problemas, Aprendizaje autónomo',
  experiencia       = 'Actualmente cuento con conocimientos en desarrollo web y en bases de datos relacionales, aplicados en proyectos académicos.\n\nEn mi formación he desarrollado trabajos prácticos como este sitio web administrable, donde implementé un sistema de login, la gestión de contenido y la subida de imágenes. También he practicado el análisis de requerimientos y el diseño de bases de datos, y estoy fortaleciendo mis conocimientos en programación y desarrollo de aplicaciones.'
WHERE id = 1;

--  Contacto 
UPDATE contacto SET
  correo   = 'fiorelhabarrantes@gmail.com',
  telefono = '85951408'
WHERE id = 1;

--  Atestados académicos 
-- (Se borran los existentes y se cargan los nuevos)
DELETE FROM atestados;

INSERT INTO atestados (titulo, institucion, anio, descripcion) VALUES
('Ingeniería en Sistemas (en curso)',
 'Universidad - UISIL',
 2024,
 'Formación universitaria en desarrollo de software, bases de datos, redes y análisis de sistemas. Actualmente en proceso de estudio.'),
('Desarrollo web con HTML, CSS, PHP y MySQL',
 'Curso Programación 4 - UISIL',
 2026,
 'Desarrollo de un sitio web personal administrable con base de datos, panel de administración, inicio de sesión y gestión de imágenes.'),
('Educación diversificada',
 'Curso Programación 4 - UISIL',
 2025,
 'Conclusión de la educación secundaria, base de mi formación universitaria.');
