const Auth = (function () {
  function requireAuth() {
    if (!Api.getToken()) {
      window.location.href = '/staff/login';
      return false;
    }
    return true;
  }

  function requireGuest() {
    if (Api.getToken()) {
      window.location.href = '/staff/dashboard';
      return false;
    }
    return true;
  }

  function requireOnboarding() {
    if (!Api.getToken()) {
      window.location.href = '/staff/login';
      return false;
    }
    return true;
  }

  async function logout() {
    try {
      await Api.post('/staff/logout');
    } catch (e) {}
    Api.clearSession();
    window.location.href = '/staff/login';
  }

  return { requireAuth, requireGuest, requireOnboarding, logout };
})();