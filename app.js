(() => {
  const products = window.storeProducts || [];
  const byId = Object.fromEntries(products.map((product) => [product.id, product]));
  const cart = new Map();
  let toastTimer;
  let activeProduct = null;
  const productDialog = document.querySelector('#product-dialog');
  const bagDialog = document.querySelector('#bag-dialog');
  const checkoutDialog = document.querySelector('#checkout-dialog');
  const money = (amount) => `৳${Number(amount).toLocaleString('en-BD')}`;
  const discount = (product) => Math.round((1 - product.price / product.was) * 100);

  function toast(message) {
    const node = document.querySelector('.toast');
    node.textContent = message;
    node.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => node.classList.remove('show'), 2400);
  }

  function updateCart() {
    const count = [...cart.values()].reduce((sum, item) => sum + item.quantity, 0);
    const total = [...cart.values()].reduce((sum, item) => sum + item.product.price * item.quantity, 0);
    document.querySelector('.bag-count').textContent = count;
    document.querySelector('#bag-total-count').textContent = count;
    document.querySelector('#bag-subtotal').textContent = money(total);
    document.querySelector('#checkout-total').textContent = money(total);
    const list = document.querySelector('.bag-items');
    if (cart.size === 0) {
      list.innerHTML = '<p class="empty-bag">Your bag is ready for something lovely.</p>';
      return;
    }
    list.innerHTML = [...cart.values()].map((item) => `
      <div class="bag-item">
        <img src="https://images.unsplash.com/${item.product.image}?auto=format&fit=crop&w=180&q=70" alt="">
        <div><div class="bag-item-name">${item.product.name}</div><div class="bag-item-detail">${item.variant} · Qty ${item.quantity}</div></div>
        <div><div class="bag-item-price">${money(item.product.price * item.quantity)}</div><button class="bag-item-remove" type="button" data-remove="${item.key}" aria-label="Remove ${item.product.name}">×</button></div>
      </div>`).join('');
  }

  function addToBag(product, variant = product.sizes[0], quantity = 1) {
    const key = `${product.id}:${variant}`;
    const current = cart.get(key);
    cart.set(key, { key, product, variant, quantity: (current?.quantity || 0) + quantity });
    updateCart();
    toast(`${product.name} added to your bag`);
  }

  function openProduct(product) {
    activeProduct = product;
    const image = `https://images.unsplash.com/${product.image}?auto=format&fit=crop&w=900&q=80`;
    document.querySelector('.dialog-content').innerHTML = `
      <img class="dialog-image" src="${image}" alt="${product.name}">
      <div class="dialog-info">
        <span class="eyebrow">${product.category.toUpperCase()} · ${product.tag.toUpperCase()}</span>
        <h2>${product.name}</h2>
        <div class="dialog-price"><strong>${money(product.price)}</strong><del>${money(product.was)}</del><span class="discount-tag">−${discount(product)}%</span></div>
        <p class="dialog-description">${product.description}</p>
        <span class="option-title">Color · ${product.color}</span>
        <span class="option-title">Choose your size</span>
        <div class="size-options">${product.sizes.map((size, index) => `<button class="size-option${index === 0 ? ' selected' : ''}" type="button" data-size="${size}">${size}</button>`).join('')}</div>
        <span class="option-title">Quantity</span>
        <div class="quantity-control"><button type="button" data-quantity="minus" aria-label="Decrease quantity">−</button><span>1</span><button type="button" data-quantity="plus" aria-label="Increase quantity">+</button></div>
        <button class="button button-dark dialog-add" type="button">Add to bag <span>↗</span></button>
        <p class="delivery-note">Easy 7-day exchange · Cash on delivery · Delivery across Bangladesh</p>
      </div>`;
    productDialog.showModal();
  }

  document.querySelectorAll('[data-product]').forEach((button) => button.addEventListener('click', () => {
    const product = byId[button.dataset.product];
    if (product) openProduct(product);
  }));

  document.querySelectorAll('[data-add]').forEach((button) => button.addEventListener('click', () => {
    const product = byId[button.dataset.add];
    if (product) addToBag(product);
  }));

  document.querySelector('.dialog-content').addEventListener('click', (event) => {
    const size = event.target.closest('[data-size]');
    if (size) {
      document.querySelectorAll('.size-option').forEach((option) => option.classList.toggle('selected', option === size));
    }
    const quantityButton = event.target.closest('[data-quantity]');
    if (quantityButton) {
      const count = document.querySelector('.quantity-control span');
      count.textContent = Math.max(1, Number(count.textContent) + (quantityButton.dataset.quantity === 'plus' ? 1 : -1));
    }
    if (event.target.closest('.dialog-add') && activeProduct) {
      const variant = document.querySelector('.size-option.selected')?.dataset.size || activeProduct.sizes[0];
      const quantity = Number(document.querySelector('.quantity-control span').textContent);
      addToBag(activeProduct, variant, quantity);
      productDialog.close();
    }
  });

  document.querySelector('.bag-items').addEventListener('click', (event) => {
    const remove = event.target.closest('[data-remove]');
    if (remove) {
      cart.delete(remove.dataset.remove);
      updateCart();
    }
  });

  document.querySelector('.bag-button').addEventListener('click', () => {
    updateCart();
    bagDialog.showModal();
  });

  document.querySelector('.checkout-button').addEventListener('click', () => {
    if (cart.size === 0) {
      toast('Your bag is empty — find a favorite first');
      return;
    }
    bagDialog.close();
    checkoutDialog.showModal();
  });

  document.querySelector('#checkout-form').addEventListener('submit', (event) => {
    event.preventDefault();
    if (!event.currentTarget.reportValidity()) return;
    checkoutDialog.close();
    cart.clear();
    updateCart();
    event.currentTarget.reset();
    toast('Thanks! Your demo order is ready for the backend milestone.');
  });

  document.querySelectorAll('.dialog-close').forEach((button) => button.addEventListener('click', () => button.closest('dialog').close()));
  document.querySelectorAll('dialog').forEach((dialog) => dialog.addEventListener('click', (event) => {
    if (event.target === dialog) dialog.close();
  }));

  const search = document.querySelector('#product-search');
  let selectedCategory = 'All';
  function filterProducts() {
    const term = search.value.trim().toLowerCase();
    let visible = 0;
    document.querySelectorAll('.product-card').forEach((card) => {
      const show = (selectedCategory === 'All' || card.dataset.category === selectedCategory) && card.dataset.name.includes(term);
      card.hidden = !show;
      if (show) visible++;
    });
    document.querySelector('.no-results').hidden = visible !== 0;
  }
  document.querySelectorAll('.filter-chip').forEach((button) => button.addEventListener('click', () => {
    selectedCategory = button.dataset.category;
    document.querySelectorAll('.filter-chip').forEach((chip) => chip.classList.toggle('active', chip === button));
    filterProducts();
  }));
  search.addEventListener('input', filterProducts);
  document.querySelectorAll('[data-filter-link]').forEach((link) => link.addEventListener('click', () => {
    const chip = [...document.querySelectorAll('.filter-chip')].find((item) => item.dataset.category === link.dataset.filterLink);
    if (chip) chip.click();
  }));

  document.querySelectorAll('.save-button').forEach((button) => button.addEventListener('click', () => {
    const saved = button.classList.toggle('saved');
    button.textContent = saved ? '♥' : '♡';
    toast(saved ? 'Saved to your favorites' : 'Removed from your favorites');
  }));

  const menuButton = document.querySelector('.menu-toggle');
  menuButton.addEventListener('click', () => {
    const open = document.querySelector('.main-nav').classList.toggle('open');
    menuButton.setAttribute('aria-expanded', String(open));
  });
  document.querySelectorAll('.main-nav a').forEach((link) => link.addEventListener('click', () => {
    document.querySelector('.main-nav').classList.remove('open');
    menuButton.setAttribute('aria-expanded', 'false');
  }));

  document.querySelector('.newsletter-form').addEventListener('submit', (event) => {
    event.preventDefault();
    event.currentTarget.reset();
    toast('Thanks for joining us — subscription is demo only.');
  });
  updateCart();
})();
