## Purpose
This file tells AI coding agents how to be immediately productive in this repo: a small procedural PHP CRUD app with image uploads and a MySQL schema.

## Big picture
- Stack: PHP (procedural), MySQL, Bootstrap (static HTML/CSS in `PHP2025/css`), uploads stored in `PHP2025/fotos/`.
- App root: `PHP2025/` contains the web app. Database DDL: `curso_php2025.sql` at the repository root.
- Main flow: UI pages (e.g. `formulario.php`) POST to handlers (e.g. `respuesta.php`) which use `conexion.php` (mysqli procedural) to run SQL and then redirect to `index.php`.

## Entry points & responsibilities
- `PHP2025/index.php` — main panel/dashboard.
- `PHP2025/formulario.php` — create form (fields: `nombre`, `apellido`, file input `foto`).
- `PHP2025/respuesta.php` — handles `Alta` POST: validates image, moves file into `fotos/`, inserts into `personas` table.
- `PHP2025/modificar.php`, `PHP2025/actualizar.php`, `PHP2025/eliminar.php` — update/delete handlers (follow the same mysqli procedural pattern).
- `PHP2025/conexion.php` — single DB connection file; variables: `$servidor`, `$usuario`, `$contrasena`, `$basededatos`, and the connection handle `$conexion`.
- `PHP2025/loginseguro.php` — presents login UI and creates session using fixed credentials (session name: `back`, session variables: `is_logged`, `Usuario`, `IDUsuario`, `Nombre`). Do not change session var names unless you update all checks.

## Data & conventions to preserve
- Database table: `personas` with columns at least `nombre`, `apellido`, `foto` (used throughout). Use the same column names when editing queries.
- Form field names are important: `nombre`, `apellido`, `foto`, and submit buttons like `Alta` for create. Handlers check `isset($_POST['Alta'])` etc.
- File uploads: use `enctype="multipart/form-data"`; handlers expect `$_FILES['foto']`; files are moved with `move_uploaded_file()` to `fotos/` and the DB stores the relative path (e.g., `fotos/12345.jpg`). Keep `fotos/` writable.
- DB access pattern: mysqli procedural, sometimes with `mysqli_real_escape_string()` (no prepared statements). If you change to prepared statements, update all handlers consistently.

## Developer workflows & commands
- Import DB locally: `mysql -u <user> -p < curso_php2025.sql` (or use phpMyAdmin) — database name expected: `curso_php2025`.
- Quick dev server (from repo root):
```
php -S localhost:8000 -t PHP2025
# then open http://localhost:8000/index.php
```
- Alternative: drop the `PHP2025/` folder into XAMPP/htdocs and use the local Apache+PHP setup.

## Security & testing notes (observed patterns)
- `conexion.php` currently contains plaintext credentials (`german`/`german1234`) — treat as secrets if you change remote configs.
- Authentication: `loginseguro.php` uses hard-coded admin credentials. Many pages rely on session variables named above. Preserve session variable names or update checks across files.
- Input handling: handlers use `mysqli_real_escape_string()` plus basic image checks (via `getimagesize`). There is not a consistent prepared-statement pattern — if you introduce prepared statements, do so project-wide.

## When you edit code
- Reuse `include 'conexion.php';` at the top of handlers to stay consistent.
- Keep procedural structure (functions or minimal classes are okay) — the rest of the code expects file-level procedural files.
- Mirror existing redirect/response style: after DB ops the code generally does `header('location: index.php?mensaje=...')` and `exit();`.

## Useful examples (copy/paste safe)
- Include DB: `include 'conexion.php';` (file path relative to PHP pages).
- Insert example (follow style in `respuesta.php`):
```
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$sql = "INSERT INTO personas (nombre, apellido, foto) VALUES ('$nombre', '$apellido', '$ruta')";
mysqli_query($conexion, $sql);
```

## Files to inspect when making changes
- [PHP2025/conexion.php](PHP2025/conexion.php)
- [PHP2025/respuesta.php](PHP2025/respuesta.php)
- [PHP2025/formulario.php](PHP2025/formulario.php)
- [PHP2025/loginseguro.php](PHP2025/loginseguro.php)
- [curso_php2025.sql](curso_php2025.sql)

---
If any section is unclear or you want the agent to apply a change (e.g., migrate to prepared statements, centralize config, or add unit tests), tell me which scope to modify and I will update the guidance and implement it.
