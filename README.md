# Plataforma de Reportes - FES Aragón

Proyecto sencillo para generar reportes de incidencias en la FES Aragón. Incluye autenticación básica, roles (admin/alumno), CRUD de reportes y datos de ejemplo.

Cómo usar (resumen):
- Importar `database/schema.sql` y `database/seed.sql` en MySQL (o usar docker-compose que ya monta los seeds).
- Abrir `public/login.php` y usar credenciales de la seed: admin@aragon.unam.mx / Admin2026 o alumno1@comunidad.fes.aragon / Alumno123

Estructura principal:
- public/: páginas públicas (login, register, reportes)
- src/: helpers (auth)
- templates/: header/footer reuse
- database/: esquema y seed
