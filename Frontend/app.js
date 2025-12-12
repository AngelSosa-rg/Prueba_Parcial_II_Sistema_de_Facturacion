const API = "/Sistema_de_factura_electronica/Backend/public/index.php?";

async function requestJSON(path, options = {}) {
  const res = await fetch(API + path, options);
  const text = await res.text();
  let data = null;
  try { data = text ? JSON.parse(text) : null; } catch { data = text; }

  if (!res.ok) {
    const msg = (data && data.error) ? data.error : `HTTP ${res.status}`;
    throw new Error(msg);
  }
  return data;
}

function getJSON(path) {
  return requestJSON(path);
}

function postJSON(path, payload) {
  return requestJSON(path, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });
}

function putJSON(path, payload) {
  return requestJSON(path, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  });
}

function deleteReq(pathWithQuery) {
  return requestJSON(pathWithQuery, { method: "DELETE" });
}

function money(v) {
  const n = Number(v ?? 0);
  return n.toLocaleString("es-EC", { style: "currency", currency: "USD" });
}
