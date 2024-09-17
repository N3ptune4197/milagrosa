// resources/js/main.jsx
import { AutoComplete } from 'primereact/autocomplete';
import React from 'react';
import ReactDOM from 'react-dom/client';
import AutoCompletePersonal from './AutocompletePersonal';
        



const root = ReactDOM.createRoot(document.getElementById('root'));

root.render(
    <React.StrictMode>

            <AutoCompletePersonal />

    </React.StrictMode>
);
