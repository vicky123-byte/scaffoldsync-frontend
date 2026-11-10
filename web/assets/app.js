'use strict';

function openModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.add('show');
  modal.setAttribute('aria-hidden', 'false');
  document.body.classList.add('modal-open');
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.remove('show');
  modal.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('modal-open');
}

function toggleSidebar() {
  document.getElementById('sidebar')?.classList.toggle('open');
}

function validatePaymentFile(input) {
  const hint = document.getElementById('fileHint');
  const file = input.files && input.files[0];
  if (!hint || !file) return;
  const allowed = ['image/jpeg', 'image/png', 'application/pdf'];
  if (!allowed.includes(file.type)) {
    input.value = '';
    hint.textContent = 'Format tidak didukung. Gunakan JPG, PNG, atau PDF.';
    hint.classList.add('error-text');
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    input.value = '';
    hint.textContent = 'Ukuran file melebihi 5 MB.';
    hint.classList.add('error-text');
    return;
  }
  hint.textContent = `${file.name} · ${(file.size / 1024 / 1024).toFixed(2)} MB`;
  hint.classList.remove('error-text');
}

document.addEventListener('click', (event) => {
  const target = event.target;
  if (target instanceof HTMLElement && target.classList.contains('modal')) {
    target.classList.remove('show');
    target.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
  }
});

document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    document.querySelectorAll('.modal.show').forEach((modal) => {
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    });
    document.body.classList.remove('modal-open');
  }
});
