// State-City dropdown functionality
function initializeStateCity() {
    const stateSelectors = {
        company: {
            state: document.getElementById('company_state'),
            city: document.getElementById('company_city'),
        },
        warehouse: {
            state: document.getElementById('warehouse_state'),
            city: document.getElementById('warehouse_city'),
        }
    };

    // Indian states list
    const states = [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
        'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Delhi'
    ];
    
    // Populate state dropdowns
    Object.values(stateSelectors).forEach(section => {
        if (section.state) {
            states.forEach(stateName => {
                const option = new Option(stateName, stateName);
                section.state.appendChild(option);
            });
        }
    });

    // Function to handle city fetching
    function handleCityPopulation(sectionKey) {
        const stateSelect = stateSelectors[sectionKey].state;
        const citySelect = stateSelectors[sectionKey].city;

        if (!stateSelect || !citySelect) return;

        stateSelect.addEventListener('change', function () {
            const stateName = this.value;
            citySelect.innerHTML = '<option value="">Select City/District</option>';
            citySelect.disabled = true;

            if (stateName) {
                fetch(`/api/cities/${encodeURIComponent(stateName)}`)
                    .then(res => res.json())
                    .then(cities => {
                        cities.forEach(cityName => {
                            const option = new Option(cityName, cityName);
                            citySelect.appendChild(option);
                        });
                        citySelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                        citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    });
            }
        });
    }

    handleCityPopulation('company');
    handleCityPopulation('warehouse');
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeStateCity);
} else {
    initializeStateCity();
}