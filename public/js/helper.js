window.esc = function (s) {
    return String(s ?? '').replace(/[&<>"']/g, function (c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
};
window.fmt = function (n) {
    return n >= 1000 ? (n / 1000).toFixed(2) + 'k' : n;
};