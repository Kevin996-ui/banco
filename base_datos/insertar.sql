INSERT INTO perfiles (nombre_perfil) VALUES ('Administrador'), ('Desarrollador'), ('Supervisor');

INSERT INTO usuarios (username, password, perfil_id) 
VALUES 
('admin', SHA2('admin123', 256), 1),
('dev', SHA2('dev123', 256), 2),
('super', SHA2('super123', 256), 3);
