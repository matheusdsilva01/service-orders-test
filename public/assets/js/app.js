document.querySelectorAll('[data-toast]').forEach((toast) => {
    setTimeout(() => toast.remove(), 3000);
});
