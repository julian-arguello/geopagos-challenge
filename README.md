
# Challenge GeoPagos

Este repositorio contiene mi solución al desafío técnico de GeoPagos.

---

## Objetivo del Proyecto

El objetivo es modelar el comportamiento de un torneo de tenis con las siguientes características:

- La modalidad del torneo es por eliminación directa.
- Puede asumirse que la cantidad de jugadores es potencia de 2.
- El torneo puede ser Femenino o Masculino.
- Cada jugador tiene un nombre y un nivel de habilidad (entre 0 y 100).
- En un enfrentamiento influyen el nivel de habilidad y la suerte (diseño a determinar).
- En el torneo masculino, se consideran la fuerza y la velocidad de desplazamiento como parámetros adicionales para determinar al ganador.
- En el torneo femenino, se considera el tiempo de reacción como un parámetro adicional para determinar al ganador.
- No existen los empates.
- Se debe simular el torneo a partir de una lista de jugadores y obtener como resultado al ganador.

---

## **Aclaraciones personales del torneo**

- Si bien los jugadores deben ser una potencia de 2, se requiere como mínimo que haya **2 participantes** para que el torneo sea válido.
- La lista de jugadores está simulada con información cargada desde los **seeders** y generada utilizando **Faker**.
- Automáticamente se generan **2 torneos de 8 participantes**:
  - Uno **femenino**.
  - Otro **masculino**.
- La **suerte** otorga un **15% adicional al score** del jugador afortunado en cada enfrentamiento.

---

## Tecnologías utilizadas

- Backend: Laravel 10, PHP 8.1.
- Frontend: Blade, Bootstrap.
- Base de datos: MySQL 8.
- Contenedores: Laravel Sail (Docker).

---

## Cómo ejecutar el proyecto


#### 1. Clona el repositorio
```bash
git clone https://github.com/julian-arguello/geopagos-challenge.git
cd geopagos-challenge
```

#### 2. Configura el archivo de entorno
(Ajusta las variables necesarias en el archivo ".env").
```bash
cp .env.example .env
```

#### 3. Instala las dependencias de Composer
```bash
composer install
```

#### 4. Levanta los contenedores con Laravel Sail
```bash
./vendor/bin/sail up -d
```

#### 5. Ejecuta las migraciones y seeders para inicializar la base de datos
```bash
./vendor/bin/sail artisan migrate:refresh --seed
```
#### 6. Accede al proyecto en tu navegador
```bash
# El proyecto estará disponible en http://localhost donde se puede ver el listado de torneos.

```
---

## Proceso por el cual se calcula al ganador (teniendo en cuenta la suerte)

Cuando el torneo se procesa sucede lo siguiente:

- Se llama al método `run()` de `TournamentService`.
  - Este método es el encargado de manejar la lógica para calcular al ganador de la siguiente manera:

Al método `run()` se le pasa el torneo como propiedad para poder acceder a la lista de inscriptos. Con esta lista recuperada, se la pasamos al método `round`, el cual se encargará de calcular la mitad de participantes y generar dos equipos.  
Estos equipos generados se enfrentan entre sí uno a uno, y los ganadores se van guardando en un nuevo listado de jugadores para poder llamar de manera recursiva a `round` hasta que nos quede un solo jugador, el cual será el ganador.

¿Cómo calculamos esto?  
Bueno, en cada ronda, cuando se enfrentan los jugadores, se llama al método `match`.

### match
Este método se encarga de enfrentar a los jugadores teniendo en cuenta:
- Su **score**, el cual se calcula con el método `scoreCalculator`.
- La **suerte**, que puede ser asignada a cualquiera de los dos jugadores (aplicando un 15% más de score al afortunado).

Luego de esto, se realiza el cálculo y se regresa el ganador, que `round` tomará para generar ese nuevo listado de jugadores, permitiendo llamarlo de manera recursiva como comentamos anteriormente, hasta que quede un solo jugador.

### scoreCalculator
Este método se encarga de sumar las habilidades para generar un score.  
Pero, para esto, se necesitó normalizar los valores para poder sumarlos. Para ello, se generó la función `normalize`, que es llamada por `scoreCalculator`.

### assignLuck
Este método recibe un listado con jugadores y regresa el jugador que tendrá suerte.

### Otros métodos
- `setWinner`: Encargado de establecer el ganador.
- `setLastOpponentAndLastRound`: Establece el último oponente y la última ronda jugada.

De esta manera, se estableció el core para calcular el ganador.

---

## ¿Qué veremos en la aplicación luego de levantar el proyecto?

La aplicación ofrece las siguientes funcionalidades principales:

### Sección de Torneos
Al ingresar a la aplicación, se mostrará un listado de torneos con la siguiente información:
- ID del torneo.
- Cantidad de participantes en el torneo.
- Género del torneo (Femenino o Masculino).
- Estado del torneo: puede ser uno de los siguientes:
  - Jugable: el torneo está listo para jugarse.
  - En progreso: el torneo ya comenzó pero no ha finalizado.
  - Finalizado: el torneo ya ha concluido.
- Posibilidad de acceder al detalle de cada torneo.

#### Detalle de Torneos
Dentro del detalle de un torneo, se mostrará:
- Información general del torneo, incluyendo:
  - Estado actual del torneo.
  - Género.
  - Cantidad de participantes.
- Listado de jugadores inscritos, con sus habilidades específicas.
- Si el torneo está en estado "Jugable", se podrá simular el torneo para obtener los resultados.

##### Resultado del Torneo
Una vez que el torneo se ha jugado, se mostrará:
- El ganador del torneo.
- La última ronda jugada por cada jugador.
- El último oponente que enfrentaron.

---

### Sección de Jugadores
La aplicación también incluye una sección específica para jugadores, donde se puede:
- Visualizar el listado completo de jugadores.
- Los jugadores están organizados y separados por género:
  - Femeninos.
  - Masculinos.

---

## Vista previa de la aplicación

### Listado de Torneos
![Listado de Torneos](https://github.com/user-attachments/assets/f3f58adb-c27d-42bf-a629-3afabf03cabc)

### Detalle de un Torneo
![Detalle de Torneo](https://github.com/user-attachments/assets/6345dac6-10a5-4a69-bb0e-9ab85b67fb77)

### Torneo ya ejecutado
![Torneo Ejecutado](https://github.com/user-attachments/assets/2df5e81f-7228-49d1-80b3-f69fae8367b3)

### Listado de Jugadores
![Listado de Jugadores](https://github.com/user-attachments/assets/f909bac7-a9c4-48ca-8d12-c05b242e31e4)

---

## **API**

La documentación de la API se encuentra disponible en:  
[http://localhost/api/documentation](http://localhost/api/documentation)

### Rutas disponibles

#### **Tournaments**
- **GET** `/tournaments`:  
  Obtiene el listado de torneos.

- **POST** `/tournaments`:  
  Permite enviar un listado de jugadores para simular un torneo y obtener un resultado.

#### **Tournaments/{id}**
- **GET** `/tournaments/{id}`:  
  Obtiene los detalles de un torneo específico, donde `{id}` es el identificador del torneo.

#### **Players**
- **GET** `/players`:  
  Obtiene el listado de todos los jugadores.

- **GET** `/players/{id}`:  
  Obtiene el detalle de un jugador específico, donde `{id}` es el identificador del jugador.

---
