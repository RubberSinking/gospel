document.querySelectorAll('.insight').forEach(button => {
  const verse = button.closest('.verse');
  const toggle = () => {
    const open = verse.classList.toggle('open');
    button.setAttribute('aria-expanded', open ? 'true' : 'false');
  };
  button.addEventListener('click', toggle);
});
const observer = new IntersectionObserver(entries => entries.forEach(e => {
  if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
}), {threshold:.12});
document.querySelectorAll('.verse').forEach(v => observer.observe(v));
const glow = document.querySelector('.cursor-glow');
document.addEventListener('pointermove', e => { glow.style.left=e.clientX+'px'; glow.style.top=e.clientY+'px'; });
