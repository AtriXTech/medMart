const CustomerAuth = (function () {
  function requireAuth() {
    if (!CustomerApi.getToken()) {
      window.location.href = '/customer/login';
      return false;
    }
    return true;
  }

  function requireGuest() {
    if (CustomerApi.getToken()) {
      window.location.href = '/customer/products';
      return false;
    }
    return true;
  }

  async function logout() {
    try {
      await CustomerApi.post('/customer/logout');
    } catch (e) {}
    CustomerApi.clearSession();
    window.location.href = '/customer/login';
  }

  return { requireAuth, requireGuest, logout };
})();