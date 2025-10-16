export function getAuthOptions(method = 'GET') {
    const token = localStorage.getItem('authToken');
    
    if (!token) {
        // If no token exists, redirect to login or handle unauthenticated state
        console.warn("No authentication token found. Redirecting to login.");
        window.location.href = '/login.html';
        return null;
    }

    const options = {
        method: method,
        // 1. REMOVED: credentials: 'include' (no longer needed for session cookies)
        headers: {
            // 2. ADDED: Authorization header with the Bearer token
            'Authorization': `Bearer ${token}` 
        }
    };
    
    // Add Content-Type header for non-GET methods if a body is present
    if (method !== 'GET' && method !== 'HEAD') {
        options.headers['Content-Type'] = 'application/json';
    }
    
    return options;
}