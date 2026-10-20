(() => {
  const list = document.querySelector('#admin-variant-list');
  const template = document.querySelector('#admin-variant-template');
  const addButton = document.querySelector('#add-product-variant');
  if (!list || !template || !addButton) return;

  let nextIndex = list.querySelectorAll('.admin-variant-row').length;
  addButton.addEventListener('click', () => {
    const row = template.content.firstElementChild.cloneNode(true);
    row.querySelectorAll('[data-field]').forEach((field) => {
      field.name = `variants[${nextIndex}][${field.dataset.field}]`;
      field.removeAttribute('data-field');
    });
    list.append(row);
    nextIndex += 1;
    row.querySelector('input[name$="[value]"]')?.focus();
  });

  list.addEventListener('click', (event) => {
    const remove = event.target.closest('.remove-variant-row');
    if (remove) remove.closest('.admin-variant-row')?.remove();
  });

  document.querySelectorAll('input[type="file"][accept^="image/"]').forEach((input) => {
    input.addEventListener('change', () => {
      const limit = input.multiple ? 10 : 1;
      if (input.files.length > limit) {
        input.setCustomValidity(`Choose no more than ${limit} image${limit === 1 ? '' : 's'}.`);
        input.reportValidity();
      } else {
        input.setCustomValidity('');
      }
    });
  });
})();
