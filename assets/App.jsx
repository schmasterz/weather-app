import React from 'react';
import Weather from "./components/Weather";
import { BrowserRouter, Routes, Route, Link, NavLink } from "react-router-dom";
import 'bootstrap/dist/css/bootstrap.min.css';

import ApiKeys from "./components/ApiKeys";

const App = () => {
    return (
        <>
            <div>
                <BrowserRouter>
                    <nav className="navbar navbar-expand-lg bg-body-tertiary">
                        <div className="container-fluid">
                            <Link to="/" className="navbar-brand"> Weather App</Link>
                            <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                <span className="navbar-toggler-icon"></span>
                            </button>
                            <div className="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li className="nav-item">
                                        <NavLink className="nav-link" to="/">Weather</NavLink>
                                    </li>
                                    <li className="nav-item">
                                        <NavLink className="nav-link" to="/api-keys">Api Keys</NavLink>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <Routes>
                        <Route exact path="/" element={<Weather/>}></Route>
                        <Route exact path="/api-keys" element={<ApiKeys/>}></Route>
                        <Route path="*" element={<h1>404 Not found</h1>}></Route>
                    </Routes>
                </BrowserRouter>
            </div>
        </>
    );
};

export default App;