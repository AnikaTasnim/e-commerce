(() => {
  const number = (amount) => Math.round(Number(amount)).toLocaleString('en-BD');
  const money = (amount) => `৳${number(amount)}`;
  const isBanglaPage = document.documentElement.lang === 'bn';

  const search = document.querySelector('#product-search');
  const filterChips = [...document.querySelectorAll('.filter-chip')];
  const productCards = [...document.querySelectorAll('.product-card')];
  if (filterChips.length && productCards.length) {
    let category = document.querySelector('.filter-chip.active')?.dataset.category || 'ALL';
    const applyFilters = (animate = false) => {
      const term = (search?.value || '').trim().toLocaleLowerCase();
      let visible = 0;
      productCards.forEach((card) => {
        const matchesCategory = category === 'ALL'
          || (card.dataset.category || '').toUpperCase() === category;
        const matchesSearch = (card.dataset.name || card.textContent).toLocaleLowerCase().includes(term);
        const matches = matchesCategory && matchesSearch;
        const wasHidden = card.hidden;
        card.hidden = !matches;
        if (matches) {
          visible++;
          if (animate && wasHidden) {
            card.classList.remove('filter-enter');
            void card.offsetWidth;
            card.classList.add('filter-enter');
          }
        } else {
          card.classList.remove('filter-enter');
        }
      });

      const empty = document.querySelector('.no-results');
      if (empty) empty.hidden = visible > 0;
    };

    filterChips.forEach((chip) => {
      chip.setAttribute('aria-pressed', String(chip.dataset.category === category));
      chip.addEventListener('click', () => {
        category = chip.dataset.category || 'ALL';
        filterChips.forEach((item) => {
          const selected = item === chip;
          item.classList.toggle('active', selected);
          item.setAttribute('aria-pressed', String(selected));
        });
        applyFilters(true);
      });
    });

    search?.addEventListener('input', () => applyFilters(true));
    applyFilters();
  }

  const heroSlider = document.querySelector('#hero-slider');
  if (heroSlider) {
    const slides = [...heroSlider.querySelectorAll('.hero-slide')];
    const dots = [...heroSlider.querySelectorAll('[data-slider-to]')];
    const status = heroSlider.querySelector('#hero-slide-status');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    let activeSlide = 0;
    let timer;
    let paused = false;

    const showSlide = (index) => {
      activeSlide = (index + slides.length) % slides.length;
      slides.forEach((slide, slideIndex) => {
        const isActive = slideIndex === activeSlide;
        slide.classList.toggle('is-active', isActive);
        slide.setAttribute('aria-hidden', String(!isActive));
        slide.inert = !isActive;
        const video = slide.querySelector('video');
        if (video) {
          if (isActive && !reducedMotion.matches) video.play().catch(() => {});
          else video.pause();
        }
      });
      dots.forEach((dot, dotIndex) => dot.setAttribute('aria-pressed', String(dotIndex === activeSlide)));
      if (status) status.textContent = isBanglaPage
        ? `স্লাইড ${activeSlide + 1}: ${slides[activeSlide].dataset.slideLabel}`
        : `Slide ${activeSlide + 1} of ${slides.length}: ${slides[activeSlide].dataset.slideLabel}`;
    };

    const stopTimer = () => window.clearInterval(timer);
    const startTimer = () => {
      stopTimer();
      if (!paused && !reducedMotion.matches && slides.length > 1 && !slides[activeSlide]?.querySelector('video')) {
        timer = window.setInterval(() => showSlide(activeSlide + 1), 10000);
      }
    };

    heroSlider.querySelector('[data-slider-prev]')?.addEventListener('click', () => { showSlide(activeSlide - 1); startTimer(); });
    heroSlider.querySelector('[data-slider-next]')?.addEventListener('click', () => { showSlide(activeSlide + 1); startTimer(); });
    dots.forEach((dot) => dot.addEventListener('click', () => { showSlide(Number(dot.dataset.sliderTo)); startTimer(); }));
    slides.forEach((slide, index) => slide.querySelector('video')?.addEventListener('ended', () => {
      showSlide(index + 1);
      startTimer();
    }));
    heroSlider.addEventListener('mouseenter', () => { paused = true; stopTimer(); });
    heroSlider.addEventListener('mouseleave', () => { paused = false; startTimer(); });
    heroSlider.addEventListener('focusin', () => { paused = true; stopTimer(); });
    heroSlider.addEventListener('focusout', (event) => {
      if (!heroSlider.contains(event.relatedTarget)) { paused = false; startTimer(); }
    });
    reducedMotion.addEventListener?.('change', () => {
      if (reducedMotion.matches) heroSlider.querySelector('.hero-slide.is-active video')?.pause();
      else heroSlider.querySelector('.hero-slide.is-active video')?.play().catch(() => {});
      startTimer();
    });
    if (reducedMotion.matches) heroSlider.querySelector('video')?.pause();
    document.addEventListener('visibilitychange', () => document.hidden ? stopTimer() : startTimer());
    startTimer();
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
      const previousArea = areaSelect.value;
      areaSelect.replaceChildren(new Option(districtId ? 'Choose an area' : 'Choose a district first', ''));
      areaOptions.filter((option) => option.dataset.district === districtId).forEach((option) => {
        areaSelect.append(option.cloneNode(true));
      });
      areaSelect.disabled = !districtId;
      if ([...areaSelect.options].some((option) => option.value === previousArea)) areaSelect.value = previousArea;
    };
    districtSelect.addEventListener('change', updateAreas);
    updateAreas();
  }

  const successDialog = document.querySelector('#order-success-dialog');
  if (successDialog) {
    successDialog.showModal();
    document.querySelector('#close-order-success')?.addEventListener('click', () => successDialog.close());
    successDialog.addEventListener('click', (event) => {
      if (event.target === successDialog) successDialog.close();
    });
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
    setText('#summary-variant', selected?.value ? selected.textContent.trim() : (isBanglaPage ? 'প্যাকেটের ওজন বেছে নিন' : 'Choose a size or option'));
    setText('#summary-quantity', isBanglaPage ? `পরিমাণ: ${number(count)}` : `Quantity: ${number(count)}`);
    setText('#summary-subtotal', money(subtotal));
    setText('#summary-subtotal-line', money(subtotal));
    setText('#summary-shipping', money(shipping));
    setText('#summary-total', money(total));
    setText('#place-order-total', money(total));

    const shippingName = shippingInput?.closest('.shipping-option')?.querySelector('.shipping-choice strong')?.textContent;
    setText('#summary-shipping-name', shippingName || (isBanglaPage ? 'ডেলিভারি চার্জ' : 'Delivery'));
    const discountLine = document.querySelector('#summary-discount')?.closest('.summary-row');
    if (discountLine) {
      discountLine.hidden = savings <= 0;
      setText('#summary-discount', `- ${money(savings)}`);
    }
    const badge = document.querySelector('#detail-discount');
    if (badge) {
      const percent = regular > 0 ? Math.round(((regular - unit) / regular) * 100) : 0;
      badge.textContent = `${number(percent)}% off`;
      badge.hidden = percent <= 0;
    }

    const stockHint = document.querySelector('#stock-hint');
    if (stockHint) stockHint.textContent = selected?.value
      ? (isBanglaPage ? `${number(available)} প্যাকেট মজুত` : `${number(available)} in stock`)
      : (isBanglaPage ? 'প্যাকেটের ওজন বেছে নিন।' : 'Select a size to check availability.');
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
