En la carpeta raiz del proyecto, ejecutar los comandos:
### 🚀 Primera vez (construir la imagen y ejecutar)

docker build -t proyectodatos2-web .

docker run -d -p 8080:80 --name proyectodatos2 proyectodatos2-web

### 🔁 Ejecuciones posteriores (ya la imagen está creada)
docker start proyectodatos2
