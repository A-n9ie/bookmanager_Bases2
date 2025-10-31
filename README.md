## 🐳 Ejecución con Docker Compose
En la carpeta raíz del proyecto, ejecutar los comandos, según el caso:

### 🚀 Primera vez (construir la imagen y ejecutar)
docker compose up --build -d

# 🔁 Ejecuciones posteriores -> volver a iniciar o apagar
## 💤 Sin eliminar el contenedor
### ✅ Iniciar el contenedor (si existe)
docker compose start

### 🛑 Detener contenedor
docker compose stop

## 🧹 Eliminando el contenedor
### ▶️ Crear e iniciar contenedor (ya la imagen está creada)
docker compose up -d

### ⚠️ Eliminar contenedor (la imagen y los volumenes quedan creados)
docker compose down

# 🧩 Borrar más

### 🗑️ Eliminar volúmenes
docker compose down -v

### 🏗️ Eliminar imágenes creadas
docker compose down --rmi local

### 💣 Eliminar todo (contenedores, volúmenes e imágenes)
docker compose down --rmi all -v

# Resumen comandos
| Acción                                             | Comando                            | Contenedores           | Imágenes       | Volúmenes     | Emoji |
| -------------------------------------------------- | ---------------------------------- | ---------------------- | -------------- | ------------- | ----- |
| Primera vez: construir y levantar                  | `docker compose up --build -d`     | 🟢 Creados y corriendo | ✅ Creada/Usada | ✅ Conservados | 🚀    |
| Levantar contenedor existente                      | `docker compose up -d`             | 🟢 Corriendo           | ✅ Conservadas  | ✅ Conservados | ▶️    |
| Detener contenedor (sin eliminar)                  | `docker compose stop`              | 🔴 Detenidos           | ✅ Conservadas  | ✅ Conservados | ⏸️    |
| Iniciar contenedor detenido                        | `docker compose start`             | 🟢 Corriendo           | ✅ Conservadas  | ✅ Conservados | ✅     |
| Eliminar contenedores y red                        | `docker compose down`              | ❌ Eliminados           | ✅ Conservadas  | ✅ Conservados | ⚠️    |
| Eliminar contenedores y volúmenes                  | `docker compose down -v`           | ❌ Eliminados           | ✅ Conservadas  | ❌ Eliminados  | 🗑️   |
| Eliminar contenedores e imágenes locales           | `docker compose down --rmi local`  | ❌ Eliminados           | ❌ Eliminadas   | ✅ Conservados | 🏗️   |
| Eliminar todo (contenedores, volúmenes e imágenes) | `docker compose down --rmi all -v` | ❌ Eliminados           | ❌ Eliminadas   | ❌ Eliminados  | 💣    |
