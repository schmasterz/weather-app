php bin/console doctrine:migrations:migrate
# Weather Service
This application provides weather information for specified cities, including temperature data and trend analysis. It is powered by the [OpenWeatherMap API](https://openweathermap.org/api), ensuring accurate and up-to-date weather information.


## Installation

### Step 1: Clone the repository
```bash
git clone https://github.com/schmasterz/weather-app
```
### Step 2: Run docker
```bash
cd weather-app
docker-compose -f docker-compose.yml up
```

### Step 3: Running application for first time
If you’re running the application for the first time, execute the following command
```bash
docker exec -it weather-app-web-1 /bin/bash -c "./setup.sh"
```
Open your .env file and edit OPENWEATHER_API_KEY

You can run tests by following command
```bash
docker exec -it php bin/phpunit
```

### Step 4: Accessing App
Point Your browser to http://localhost:8080

---
## Project Structure
+ ### **PHP Backend:** Located in the src/ folder
+ ### **JavaScript Frontend:** Located in the assets/ folder, which contains the React application using Bootstrap for styling





