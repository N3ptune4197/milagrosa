// resources/js/components/PersonalSelectAutoComplete.js
import React, { useState, useEffect } from 'react';
import { AutoComplete } from 'primereact/autocomplete';
import axios from 'axios';

const PersonalSelectAutoComplete = ({ selectedId, onSelect }) => {
    const [value, setValue] = useState(null);
    const [items, setItems] = useState([]);
    const [filteredItems, setFilteredItems] = useState([]);

    useEffect(() => {
        // Fetch items when the component mounts
        const fetchItems = async () => {
            try {
                const response = await axios.get('/api/personals');
                setItems(response.data);
                setFilteredItems(response.data);
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        fetchItems();
    }, []);

    const search = (event) => {
        const query = event.query.toLowerCase();
        setFilteredItems(items.filter(personal => 
            personal.nombres.toUpperCase().includes(query) ||
            personal.a_paterno.toUpperCase().includes(query)
        ));
    };

    const handleSelect = (e) => {
        setValue(e.value);
        onSelect(e.value.id); // Pass the selected ID to parent
    };

    const itemTemplate = (item) => (
        <div className="p-d-flex p-ai-center">
            <div className="p-mr-2">{item.nombres} {item.a_paterno}</div>
        </div>
    );

    return (
        <div className="p-field">
            <label htmlFor="personal-select">Personal</label>
            <AutoComplete
                id="personal-select"
                value={value}
                suggestions={filteredItems}
                completeMethod={search}
                onChange={(e) => setValue(e.value)}
                onSelect={handleSelect}
                itemTemplate={itemTemplate}
                field="nombres"
                placeholder="Buscar personal..."
            />
            <select 
                name="idPersonal" 
                id="idPersonal" 
                className="form-control d-none"
                defaultValue={selectedId}
            >
                {items.map(personal => (
                    <option key={personal.id} value={personal.id}>
                        {personal.nombres} {personal.a_paterno}
                    </option>
                ))}
            </select>
        </div>
    );
};

export default PersonalSelectAutoComplete;
