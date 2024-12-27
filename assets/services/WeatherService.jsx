const WeatherService = async (city) => {
    try {
        const response = await fetch(`/api/weather/${city}`, {
            method: 'GET',
            headers: {
                'apiKey': process.env.REACT_APP_API_KEY,
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Failed to fetch weather data');
        }
        return await response.json();
    } catch (error) {
        throw new Error(error.message || 'Failed to fetch weather data');
    }
};

export default WeatherService;
