Para ejecutar el proyecto (despliegure automatico) en la carpeta raiz ejecutar los comandos:

docker build -t proyectodatos2-web .
docker run -d -p 8080:80 proyectodatos2-web
