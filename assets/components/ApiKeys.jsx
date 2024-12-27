import React, { useEffect, useState } from 'react';
import { fetchApiKeys, deleteApiKey, createApiKey } from '../services/ApiKeysService';

const ApiKeys = () => {
    const [name, setName] = useState('');
    const [error, setError] = useState(null);
    const [apiKeys, setApiKeys] = useState([]);

    const handleInputChange = (e) => {
        setName(e.target.value);
    };

    const handleDelete = async (id) => {
        try {
            await deleteApiKey(id);
            setApiKeys(apiKeys.filter(key => key.id !== id));
        } catch (err) {
            setError(err.message || 'Failed to delete API key');
        }
    };

    useEffect(() => {
        const fetchKeys = async () => {
            try {
                const keys = await fetchApiKeys();
                setApiKeys(keys);

            } catch (err) {
                setError(err.message || 'Failed to fetch API keys');
            }
        };

        fetchKeys();
    }, []);

    const handleCreateKey = async () => {

        try {
            const newApiKey = await createApiKey(name);
            setApiKeys([...apiKeys, newApiKey]);
            setName('');
        } catch (err) {
            setError(err.message || 'Failed to create API key');
        }
    };

    return (
        <>
            <h3 className="m-3">Manage API Keys</h3>
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <form>
                            <div className="input-group">
                                <input
                                    type="text"
                                    className="form-control form-control-lg"
                                    placeholder="Enter Name"
                                    value={name}
                                    onChange={handleInputChange}
                                    onKeyDown={(e) => {
                                        if (e.key === 'Enter') handleInputChange(e);
                                    }}
                                />
                                <button
                                    className="btn btn-primary"
                                    type="button"
                                    onClick={handleCreateKey}
                                >
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                {error && <div className="alert alert-danger mt-3">{error}</div>}
                <div className="mt-3">
                    <h4>Existing API Keys</h4>
                    {apiKeys.length > 0 ? (
                        <table className="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>API Key</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            {apiKeys.map((key) => (
                                <tr key={key.id}>
                                    <td>{key.name}</td>
                                    <td>{key.apiKeyValue}</td>
                                    <td>
                                        <button
                                            className="btn btn-danger btn-sm"
                                            onClick={() => handleDelete(key.id)}
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                    ) : (
                        <p>No API keys available.</p>
                    )}
                </div>
            </div>
        </>
    );
};

export default ApiKeys;
