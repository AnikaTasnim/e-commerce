(() => {
  const money = (amount) => `৳${Math.round(Number(amount)).toLocaleString('en-BD')}`;

  const menuButton = document.querySelector('.menu-toggle');
  menuButton?.addEventListener('click', () => {
    const open = document.querySelector('.main-nav').classList.toggle('open');
    menuButton.setAttribute('aria-expanded', String(open));
  });
  document.querySelectorAll('.main-nav a').forEach((link) => link.addEventListener('click', () => {
    document.querySelector('.main-nav')?.classList.remove('open');
    menuButton?.setAttribute('aria-expanded', 'false');
  }));

  const search = document.querySelector('#product-search');
  if (search) {
    let category = 'ALL';
    const applyFilters = () => {
      const term = search.value.trim().toLowerCase();
      let visible = 0;
      document.querySelectorAll('.product-card').forEach((card) => {
        const matches = (category === 'ALL' || card.dataset.category === category) && card.dataset.name.includes(term);
        card.hidden = !matches;
        if (matches) visible++;
      });
      const empty = document.querySelector('.no-results');
      if (empty) empty.hidden = visible > 0;
    };
    document.querySelectorAll('.filter-chip').forEach((chip) => chip.addEventListener('click', () => {
      category = chip.dataset.category;
      document.querySelectorAll('.filter-chip').forEach((item) => item.classList.toggle('active', item === chip));
      applyFilters();
    }));
    search.addEventListener('input', applyFilters);
    document.querySelectorAll('[data-filter-link]').forEach((link) => link.addEventListener('click', () => {
      document.querySelector(`.filter-chip[data-category="${link.dataset.filterLink}"]`)?.click();
    }));
  }

  const mainImage = document.querySelector('#main-product-image');
  document.querySelectorAll('[data-gallery-image]').forEach((button) => button.addEventListener('click', () => {
    if (mainImage) {
      mainImage.src = button.dataset.galleryImage;
      mainImage.alt = button.dataset.galleryAlt || '';
    }
    document.querySelectorAll('.product-thumbnail').forEach((thumbnail) => thumbnail.classList.toggle('selected', thumbnail === button));
  }));

  const districtSelect = document.querySelector('#district');
  const areaSelect = document.querySelector('#area');
  if (districtSelect && areaSelect) {
    const areaOptions = [...areaSelect.querySelectorAll('option[data-district]')];
    const updateAreas = () => {
      const districtId = districtSelect.value;
      const previouslySelected = areaSelect.value;
      areaSelect.replaceChildren(new Option(districtId ? 'Choose an area' : 'Choose a district first', ''));
      areaOptions.filter((option) => option.dataset.district === districtId).forEach((option) => {
        areaSelect.append(option.cloneNode(true));
      });
      areaSelect.disabled = !districtId;
      if ([...areaSelect.options].some((option) => option.value === previouslySelected)) areaSelect.value = previouslySelected;
    };
    districtSelect.addEventListener('change', updateAreas);
    updateAreas();
  }

  const orderSummary = document.querySelector('.order-summary');
  if (!orderSummary) return;

  const variant = document.querySelector('#product-variant');
  const quantity = document.querySelector('#quantity');
  const basePrice = Number(orderSummary.dataset.basePrice || 0);
  const regularPrice = Number(orderSummary.dataset.regularPrice || 0);

  function updateOrderSummary() {
    const selected = variant?.selectedOptions[0];
    const adjustment = Number(selected?.dataset.adjustment || 0);
    const available = Number(selected?.dataset.stock || 0);
    const count = Math.max(1, Math.min(20, Number(quantity?.value || 1), available || 20));
    if (quantity) {
      quantity.max = String(Math.max(1, Math.min(20, available || 20)));
      quantity.value = String(count);
    }

    const unit = Math.max(0, basePrice + adjustment);
    const regular = Math.max(unit, regularPrice + adjustment);
    const subtotal = unit * count;
    const savings = Math.max(0, (regular - unit) * count);
    const shippingInput = document.querySelector('input[name="shipping_option_id"]:checked');
    const shipping = Number(shippingInput?.dataset.charge || 0);
    const total = subtotal + shipping;

    const setText = (selector, value) => {
      const element = document.querySelector(selector);
      if (element) element.textContent = value;
    };
    setText('#product-current-price', money(unit));
    setText('#summary-variant', selected?.value ? selected.textContent.trim().split(' — ')[0] : 'Choose a size or option');
    setText('#summary-quantity', `Quantity: ${count}`);
    setText('#summary-subtotal', money(subtotal));
    setText('#summary-subtotal-line', money(subtotal));
    setText('#summary-shipping', money(shipping));
    setText('#summary-total', money(total));
    setText('#place-order-total', money(total));

    const shippingName = shippingInput?.closest('.shipping-option')?.querySelector('.shipping-choice strong')?.textContent;
    setText('#summary-shipping-name', shippingName || 'Delivery');
    const discountLine = document.querySelector('#summary-discount')?.closest('.summary-row');
    if (discountLine) {
      discountLine.hidden = savings <= 0;
      setText('#summary-discount', `−${money(savings)}`);
    }
    const badge = document.querySelector('#detail-discount');
    if (badge) {
      const percent = regular > 0 ? Math.round(((regular - unit) / regular) * 100) : 0;
      badge.textContent = `−${percent}%`;
      badge.hidden = percent <= 0;
    }

    const stockHint = document.querySelector('#stock-hint');
    if (stockHint) stockHint.textContent = selected?.value ? `${available} available` : 'Select an available option to see stock.';
    const button = document.querySelector('.place-order-button');
    if (button) button.disabled = !selected?.value || available < 1;
  }

  variant?.addEventListener('change', updateOrderSummary);
  quantity?.addEventListener('input', updateOrderSummary);
  document.querySelectorAll('[data-quantity-step]').forEach((button) => button.addEventListener('click', () => {
    if (!quantity) return;
    quantity.value = String(Math.max(1, Number(quantity.value || 1) + Number(button.dataset.quantityStep)));
    updateOrderSummary();
  }));
  document.querySelectorAll('input[name="shipping_option_id"]').forEach((input) => input.addEventListener('change', updateOrderSummary));
  updateOrderSummary();
})();
