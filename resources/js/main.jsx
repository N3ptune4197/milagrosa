// resources/js/main.jsx
import React from 'react';
import ReactDOM from 'react-dom/client';
import { ChakraProvider } from '@chakra-ui/react'

import App from './App';

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(
    <React.StrictMode>

        <ChakraProvider>
            
            <App />
        </ChakraProvider>
    </React.StrictMode>
);
