import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './App';

// Attach React to the DOM
const rootElement = document.getElementById('root');
const root = createRoot(rootElement);

root.render(<App />);