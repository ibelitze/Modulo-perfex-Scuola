# Módulo Perfex para Scuola Italiana Vittorio Montiglio

## Introducción

Éste módulo funciona solamente cuando se instala en el CRM Perfex. Fue hecho a medida para la escuela Chilena, dado que necesitaban un sistema para administrar los retiros que se generaban en su página web:

https://www.scuola.cl

específicamente para generar retiros (un módulo personalizado para wordpress, también hecho por mí) se puede ver su funcionamiento en su sección:

https://www.scuola.cl/sivm/retiro-de-alumnos/

el código de muestra está en mi repositorio.

## Descripción

El módulo consta de una pantalla sencilla donde se muestra la lista de los retiros generados en la web, y donde cada personal administrativo (con sus funciones específicas) pueden anular, aceptar, modificar, observar, entre otras funciones, cada retiro de la lista según su status.

Los retiros tienen diferentes estatus:

- Abierto
- Pendiente de ratificación
- Ratificado
- En proceso
- Anulado
- Formalizado
- Cerrado

Cada uno de éstos procesos los lleva un personal específico de la Escuela. 

## Entre otras funcionalidades: 

- Envío de mensajería interna para comunicar a otras áreas del proceso 
- Uso de bucket de AWS para guardado de evidencias y archivos relacionados al retiro.
- Envíos de correo electrónicos personalizados, dependiendo del status del retiro.
- Manejo de firmas digitales
- Generación de PDFS para los documentos de cierre de retiro.
- Uso de tareas Cron (cronjobs), para evaluar retiros y dependiendo de su caducidad, se anulan internamente.


Pero la mayor parte, por decir: el trabajo más fuerte es el fluyo que debe llevar cada retiro para poder ser procesado. El cual requiere un paso bastante exhaustivo por el director de área, secretaria scolastica, administración y rectoría, antes de poder ser formalizado y dado por cerrado el proceso. Una vez cerrado se le envía Cada uno de estos cargos tienen sus funciones defininas dentro del código (Controllers y Models), así como en los Helpers/ se pueden conseguir los modelos de los Emails que constantemente están siendo usados para la comunicación entre la Scuola y el apoderado.

Es un proyecto muy lindo! ojalá puedas echarle un vistazo (aunque el código no sea perfecto).