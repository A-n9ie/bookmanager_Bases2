Para ejecutar el proyecto (despliegue automatico) 
en la carpeta raiz del proyecto ejecutar los comandos:

docker build -t proyectodatos2-web .

docker run -d -p 8080:80 proyectodatos2-web
