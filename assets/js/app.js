document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
      toggle.textContent = open ? '×' : '☰';
    });
  }

  document.querySelectorAll('input[type="datetime-local"]').forEach((input) => {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    input.min = now.toISOString().slice(0, 16);
  });

  document.querySelectorAll('.alert').forEach((alert) => {
    window.setTimeout(() => alert.classList.add('alert-visible'), 30);
  });
});
