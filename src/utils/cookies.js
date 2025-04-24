export function setCookie(name, value, days = 7) {
    const d = new Date();
    d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
    const expires = "expires=" + d.toUTCString();
    document.cookie = `${name}=${value};${expires};path=/`;
  }
  
  export function getCookie(name) {
    const cookies = document.cookie.split(";");
    for (let cookie of cookies) {
      const [key, val] = cookie.trim().split("=");
      if (key === name) return val;
    }
    return null;
  }
  
  export function deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
  }
  