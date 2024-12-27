import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import WeatherService from "../services/WeatherService";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSun, faSnowflake } from '@fortawesome/free-solid-svg-icons';

const Weather = () => {
    const [query, setQuery] = useState('');
    const [weatherData, setWeatherData] = useState(null);
    const [error, setError] = useState(null);

    const handleInputChange = (e) => {
        setQuery(e.target.value);
    };

    const handleSearchSubmit = async (e) => {
        if (e) e.preventDefault();

        try {
            const data = await WeatherService(query);
            setWeatherData(data);
            setError(null);
        } catch (error) {
            setError("Unable to fetch weather data. Please try again.");
        }
    };

    const getTrendIcon = (trend) => {
        if (trend > 0) {
            return <FontAwesomeIcon icon={faSun} style={{ fontSize: '20px', marginLeft: '10px', color: 'orange' }} />;
        } else if (trend < 0) {
            return <FontAwesomeIcon icon={faSnowflake} style={{ fontSize: '20px', marginLeft: '10px', color: 'blue' }} />;
        }
        return '';
    };

    return (
        <>
            <h3 className="m-3">Search By City</h3>
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <form onSubmit={handleSearchSubmit}>
                            <div className="input-group">
                                <input
                                    type="text"
                                    className="form-control form-control-lg"
                                    placeholder="Search City"
                                    value={query}
                                    onChange={handleInputChange}
                                    onKeyDown={(e) => {
                                        if (e.key === 'Enter') handleSearchSubmit(e);
                                    }}
                                />
                                <button className="btn btn-primary" type="submit">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {error && <div className="alert alert-danger mt-3">{error}</div>}
                {weatherData && (
                    <div className="row mt-4">
                        <div className="col-3">
                            <img
                                src={weatherData.icon || 'placeholder.png'} // Fallback image
                                className="img-fluid"
                                alt="Weather Icon"
                                style={{ height: '250px', width: 'auto' }}
                            />
                        </div>
                        <div className="col-9">
                            <div className="card">
                                <div className="card-body">
                                    <h2 className="card-title">Weather in {weatherData.city}</h2>
                                    <h4 className="card-text">Temperature: {weatherData.temperature} °C</h4>
                                    <h4 className="card-text">
                                        Trend: {weatherData.trend} °C {getTrendIcon(weatherData.trend)}
                                    </h4>
                                    <h4 className="card-text">Condition: {weatherData.main}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </>
    );
};

export default Weather;
