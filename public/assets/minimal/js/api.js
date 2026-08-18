const Api = (function () {
  const BASE_URL = '/api/v1';

  function getToken() {
    return localStorage.getItem('staff_token');
  }

  function setToken(token) {
    if (token) {
      localStorage.setItem('staff_token', token);
    } else {
      localStorage.removeItem('staff_token');
    }
  }

  function setUser(user) {
    if (user) {
      localStorage.setItem('staff_user', JSON.stringify(user));
    } else {
      localStorage.removeItem('staff_user');
    }
  }

  function getUser() {
    const raw = localStorage.getItem('staff_user');
    return raw ? JSON.parse(raw) : null;
  }

  function clearSession() {
    setToken(null);
    setUser(null);
  }

  async function request(path, options = {}) {
    const headers = Object.assign(
      {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
      options.headers || {}
    );

    const token = getToken();
    if (token) {
      headers.Authorization = `Bearer ${token}`;
    }

    const response = await fetch(BASE_URL + path, {
      method: options.method || 'GET',
      headers,
      body: options.body ? JSON.stringify(options.body) : undefined,
    });

    if (response.status === 401) {
      clearSession();
      if (!window.location.pathname.includes('/staff/login')) {
        window.location.href = '/staff/login';
      }
      throw new ApiError('Session expired', 401, null);
    }

    let data = null;
    const text = await response.text();
    if (text) {
      try {
        data = JSON.parse(text);
      } catch (e) {
        data = null;
      }
    }

    if (!response.ok) {
      const message = (data && (data.message || data.error)) || `Request failed (${response.status})`;
      throw new ApiError(message, response.status, data);
    }

    return data;
  }

  function ApiError(message, status, data) {
    this.message = message;
    this.status = status;
    this.data = data;
  }
  ApiError.prototype = Object.create(Error.prototype);

  return {
    get: (path) => request(path, { method: 'GET' }),
    post: (path, body) => request(path, { method: 'POST', body }),
    patch: (path, body) => request(path, { method: 'PATCH', body }),
    delete: (path) => request(path, { method: 'DELETE' }),
    getToken,
    setToken,
    getUser,
    setUser,
    clearSession,
  };
})();