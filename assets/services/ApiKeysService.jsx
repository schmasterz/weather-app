const API_BASE_URL = '/api/keys';
export const fetchApiKeys = async () => {
    const response = await fetch(API_BASE_URL, {
        method: 'GET',
        headers: {
            'apiKey': process.env.REACT_APP_API_KEY,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Failed to fetch API keys');
    }

    return await response.json();
};

export const deleteApiKey = async (id) => {
    const response = await fetch(`${API_BASE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'apiKey': process.env.REACT_APP_API_KEY,
            'Content-Type': 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Failed to delete API key');
    }
};
export const createApiKey = async (name) => {
    const formData = new FormData();
    formData.append('name', name);

    const response = await fetch('/api/keys', {
        method: 'POST',
        headers: {
            'apiKey': process.env.REACT_APP_API_KEY,
        },
        body: formData,
    });

    if (!response.ok) {
        throw new Error('Failed to create API key');
    }

    return await response.json();  // Return the newly created API key object
};
