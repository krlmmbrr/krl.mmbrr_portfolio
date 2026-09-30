/* =====================================================
   PORTFOLIO SCRIPTS — COMPLETE FILE
   Load this exactly where your current portfolio script
   lives (end of <body>, or as a classic/deferred script —
   NOT type="module" and NOT async, because the inline
   onclick handlers call global functions like switchView).
   ===================================================== */

/* ---------- Asset base (used by projectData) ---------- */
/* If your Blade template already defines window.portfolioAssetBase
   before this script runs, that value is kept. Otherwise it is
   auto-detected from the NOVA AI banner path, with /assets/ as
   the final fallback. */
if (typeof window.portfolioAssetBase === 'undefined') {
  window.portfolioAssetBase = '/assets/';
  try {
    var portfolioAssetProbe = document.querySelector('img[src$="NOVAAI_Banner.png"]');
    if (portfolioAssetProbe) {
      window.portfolioAssetBase = portfolioAssetProbe.getAttribute('src').replace(/NOVAAI_Banner\.png.*$/, '');
    }
  } catch (portfolioAssetError) { /* keep the default */ }
}

/* =====================================================
   SCROLL LOCK — active while the skeleton is visible
   The page is completely frozen during the 3-second
   skeleton loading animation:
   - <html> + <body> receive the portfolio-loading class,
     which hides the scrollbar and blocks overflow.
   - Wheel, touch and keyboard scrolling are blocked.
   - Scroll restoration is disabled and the page is held
     at the top, so the scrollbar thumb never moves.
   The lock is released only after the skeleton animation
   AND its fade-out have fully completed.
   ===================================================== */
let scrollLockActive = false;

function blockWheelEvent(e) { e.preventDefault(); }
function blockTouchEvent(e) { e.preventDefault(); }
function blockScrollKeys(e) {
  const scrollKeys = [' ', 'ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End'];
  if (scrollKeys.indexOf(e.key) === -1) return;
  const target = e.target;
  if (target && target.closest && target.closest('input, textarea, select, [contenteditable]')) return;
  e.preventDefault();
}
function forceScrollTop() {
  if (window.scrollY !== 0 || window.pageXOffset !== 0) window.scrollTo(0, 0);
}

function activateScrollLock() {
  if (scrollLockActive) return;
  scrollLockActive = true;

  document.documentElement.classList.add('portfolio-loading');
  if (document.body) document.body.classList.add('portfolio-loading');

  /* Freeze at the top — no restored scroll positions, no scrollbar movement */
  if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
  window.scrollTo(0, 0);

  window.addEventListener('wheel', blockWheelEvent, { passive: false });
  window.addEventListener('scroll', forceScrollTop);
  document.addEventListener('touchmove', blockTouchEvent, { passive: false, capture: true });
  document.addEventListener('keydown', blockScrollKeys, { passive: false });
}

function deactivateScrollLock() {
  if (!scrollLockActive) return;
  scrollLockActive = false;

  window.removeEventListener('wheel', blockWheelEvent);
  window.removeEventListener('scroll', forceScrollTop);
  document.removeEventListener('touchmove', blockTouchEvent, { capture: true });
  document.removeEventListener('keydown', blockScrollKeys);

  document.body.classList.remove('portfolio-loading');
  document.documentElement.classList.remove('portfolio-loading');

  if ('scrollRestoration' in history) history.scrollRestoration = 'auto';
}

/* Engage the lock immediately, before anything else runs. */
activateScrollLock();

/* =====================================================
   MODAL LOGIC
   ===================================================== */
function openModal() {
  const modal = document.getElementById('callModal');
  const content = document.getElementById('callModalContent');
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  requestAnimationFrame(() => {
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
  });
}

function closeModal() {
  const modal = document.getElementById('callModal');
  const content = document.getElementById('callModalContent');
  content.classList.remove('scale-100', 'opacity-100');
  content.classList.add('scale-95', 'opacity-0');
  setTimeout(() => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }, 300);
}

const callModalElement = document.getElementById('callModal');
if (callModalElement) {
  callModalElement.addEventListener('click', (e) => {
    if (e.target.id === 'callModal') closeModal();
  });
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    const modal = document.getElementById('callModal');
    if (modal && !modal.classList.contains('hidden')) closeModal();
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox && lightbox.classList.contains('open')) closeImageLightbox();
  } else if (e.key === 'ArrowLeft') {
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox && lightbox.classList.contains('open')) showPreviousLightboxImage();
  } else if (e.key === 'ArrowRight') {
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox && lightbox.classList.contains('open')) showNextLightboxImage();
  }
});

/* =====================================================
   IMAGE LIGHTBOX VIEWER
   ===================================================== */
let lightboxItems = [];
let lightboxIndex = 0;

function updateLightboxImage() {
  const lightbox = document.getElementById('imageLightbox');
  const img = document.getElementById('lightboxImage');
  const caption = document.getElementById('lightboxCaption');
  const previous = document.getElementById('lightboxPrevious');
  const next = document.getElementById('lightboxNext');
  const counter = document.getElementById('lightboxCounter');
  const item = lightboxItems[lightboxIndex];
  if (!img || !item) return;
  img.src = item.src;
  img.alt = item.alt || '';
  img.style.width = '';
  img.style.maxWidth = '';
  img.style.maxHeight = '';
  if (caption) caption.textContent = item.alt || '';
  if (previous) previous.disabled = lightboxIndex === 0;
  if (next) next.disabled = lightboxIndex === lightboxItems.length - 1;
  if (counter) counter.textContent = lightboxItems.length > 1 ? `${lightboxIndex + 1} / ${lightboxItems.length}` : '';
}

function openImageLightbox(src, alt, items, index) {
  const lightbox = document.getElementById('imageLightbox');
  if (!lightbox) return;
  lightboxItems = items && items.length ? items : [{ src, alt }];
  const requestedIndex = Number(index);
  lightboxIndex = Number.isInteger(requestedIndex) && requestedIndex >= 0 && requestedIndex < lightboxItems.length ? requestedIndex : 0;
  lightbox.classList.add('open');
  updateLightboxImage();
  document.body.style.overflow = 'hidden';
}

function showPreviousLightboxImage() {
  if (lightboxIndex > 0) {
    lightboxIndex--;
    updateLightboxImage();
  }
}

function showNextLightboxImage() {
  if (lightboxIndex < lightboxItems.length - 1) {
    lightboxIndex++;
    updateLightboxImage();
  }
}

function closeImageLightbox() {
  const lightbox = document.getElementById('imageLightbox');
  if (!lightbox) return;
  lightbox.classList.remove('open');
  document.body.style.overflow = '';
  const img = document.getElementById('lightboxImage');
  if (img) {
    img.src = '';
    img.alt = '';
    img.style.width = '';
    img.style.maxWidth = '';
    img.style.maxHeight = '';
  }
  lightboxItems = [];
  lightboxIndex = 0;
}

/* =====================================================
   DOM READY — runs whether the script loads before or
   after DOMContentLoaded has already fired.
   ===================================================== */
function onDomReady() {
  const lightbox = document.getElementById('imageLightbox');
  if (lightbox) {
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox) closeImageLightbox();
    });
  }

  const galleryItems = Array.from(document.querySelectorAll('#view-gallery .gallery-item'));
  const galleryLightboxItems = galleryItems.map(item => {
    const image = item.querySelector('img');
    return image ? { src: image.src, alt: image.alt || '' } : null;
  }).filter(Boolean);

  galleryItems.forEach((item, index) => {
    const image = item.querySelector('img');
    if (!image) return;
    item.setAttribute('tabindex', '0');
    item.setAttribute('role', 'button');
    item.setAttribute('aria-label', 'Open ' + (image.alt || 'Gallery image'));
    const openGalleryImage = () => openImageLightbox(image.src, image.alt, galleryLightboxItems, index);
    item.addEventListener('click', openGalleryImage);
    item.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openGalleryImage();
      }
    });
  });

  initFaqAccordion();

  /* Selected Works — show only the first four projects + centered Explore pill */
  initFeaturedProjects();

  /* Message suggestions — manual toggle only, stays closed after submission */
  initMessageSuggestions();

  window.setTimeout(completeInitialSkeleton, 3000);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', onDomReady);
} else {
  onDomReady();
}

/* =====================================================
   MESSAGE SUGGESTIONS
   ===================================================== */
const MESSAGE_SUGGESTIONS = [
  'I want to redesign my project website.',
  'I need a mobile application design for my project.',
  'I need a web design for my project.',
  'I need a landing page design for my business.',
  'I need a SaaS design for my project.',
  'I need a design system for my project.',
  'I need a dashboard design for my system.',
  'I need a SaaS dashboard design for my project.',
  'I need a portfolio website design for my personal brand.'
];

let suggestionsOpenedManually = false;
let suggestionsDelegationBound = false;
let suggestionsObserver = null;

/* ---------- Element lookups (always fresh — survive re-renders) ---------- */
function getSuggestionsToggle() {
  let toggleBtn = document.getElementById('toggleSuggestions');
  if (!toggleBtn) {
    const candidates = Array.from(document.querySelectorAll('button, a'));
    toggleBtn = candidates.find(el =>
      (el.textContent || '').trim().toLowerCase().indexOf('suggestion') !== -1
    ) || null;
  }
  return toggleBtn;
}

function getSuggestionsPanel() {
  return document.getElementById('messageSuggestions');
}

function createSuggestionsPanel(toggleBtn) {
  const panel = document.createElement('div');
  panel.id = 'messageSuggestions';
  const messageAnchor = document.getElementById('message');
  if (messageAnchor && messageAnchor.parentNode) {
    messageAnchor.parentNode.insertBefore(panel, messageAnchor.nextSibling);
  } else if (toggleBtn && toggleBtn.parentNode) {
    toggleBtn.parentNode.insertBefore(panel, toggleBtn.nextSibling);
  } else {
    return null;
  }
  return panel;
}

function buildSuggestionsPanelContent(panel) {
  if (!panel) return;
  panel.innerHTML = '';
  MESSAGE_SUGGESTIONS.forEach(text => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'message-suggestion';
    btn.setAttribute('data-suggestion', text);
    btn.textContent = text;
    panel.appendChild(btn);
  });
}

function getMessageInput() {
  let messageInput = document.getElementById('message');
  if (!messageInput) {
    const panel = getSuggestionsPanel();
    const form = panel ? panel.closest('form') : null;
    if (form) messageInput = form.querySelector('textarea');
  }
  if (!messageInput) {
    const toggle = getSuggestionsToggle();
    const form = toggle ? toggle.closest('form') : null;
    if (form) messageInput = form.querySelector('textarea');
  }
  if (!messageInput) {
    messageInput = document.querySelector('textarea');
  }
  return messageInput;
}

/* ---------- Visibility ---------- */
function isSuggestionsPanelVisible() {
  const panel = getSuggestionsPanel();
  if (!panel) return false;
  return !panel.classList.contains('hidden') &&
         !panel.hasAttribute('hidden') &&
         panel.style.display !== 'none';
}

function syncSuggestionsToggleUI(open) {
  const toggleBtn = getSuggestionsToggle();
  let toggleText = document.getElementById('toggleSuggestionsText');
  if (!toggleText && toggleBtn) toggleText = toggleBtn.querySelector('span');
  let toggleIcon = document.getElementById('toggleSuggestionsIcon');
  if (!toggleIcon && toggleBtn) toggleIcon = toggleBtn.querySelector('svg');

  const desiredText = open ? 'Hide Suggestions' : 'Show Suggestions';
  if (toggleText && toggleText.textContent !== desiredText) {
    toggleText.textContent = desiredText;
  }
  if (toggleIcon) {
    toggleIcon.style.transform = open ? 'rotate(180deg)' : 'rotate(0deg)';
  }
  if (toggleBtn && toggleBtn.getAttribute('aria-expanded') !== (open ? 'true' : 'false')) {
    toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
}

function setSuggestionsPanel(open, isManual) {
  const panel = getSuggestionsPanel();
  if (panel) {
    if (open) {
      panel.classList.remove('hidden');
      panel.removeAttribute('hidden');
      panel.style.removeProperty('display');
    } else {
      panel.classList.add('hidden');
      panel.setAttribute('hidden', '');
      panel.style.display = 'none';
    }
  }
  syncSuggestionsToggleUI(open);
  if (typeof isManual === 'boolean') suggestionsOpenedManually = open;
}

/* ---------- Enforcement ---------- */
function enforceSuggestionsClosed() {
  if (!suggestionsOpenedManually && isSuggestionsPanelVisible()) {
    setSuggestionsPanel(false, false);
  }
}

function closeMessageSuggestions() {
  suggestionsOpenedManually = false;
  setSuggestionsPanel(false, false);
}

/* Exposed so any external success handler can also close the panel. */
window.closeMessageSuggestions = closeMessageSuggestions;

function startSuggestionsGuardObserver() {
  if (suggestionsObserver) return;
  if (typeof MutationObserver === 'undefined' || !document.body) return;
  suggestionsObserver = new MutationObserver(() => {
    enforceSuggestionsClosed();
    if (suggestionsOpenedManually) syncSuggestionsToggleUI(isSuggestionsPanelVisible());
  });
  suggestionsObserver.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: true,
    attributeFilter: ['class', 'hidden']
  });
}

/* ---------- Manual toggle (the ONLY way to open) ---------- */
function handleSuggestionsToggleClick() {
  let panel = getSuggestionsPanel();
  if (!panel) {
    panel = createSuggestionsPanel(getSuggestionsToggle());
    if (panel) {
      buildSuggestionsPanelContent(panel);
      setSuggestionsPanel(false, false);
      setSuggestionsPanel(true, true);
    }
    return;
  }
  if (!panel.querySelector('.message-suggestion')) {
    buildSuggestionsPanelContent(panel);
  }
  setSuggestionsPanel(!isSuggestionsPanelVisible(), true);
}

function appendSuggestionToMessage(input, suggestion) {
  const base = input.value.replace(/\s+$/, '');
  let combined;

  if (base === '') {
    combined = suggestion;
  } else if (/[.!?…]$/.test(base) || /[,;:]$/.test(base)) {
    combined = base + ' ' + suggestion;
  } else {
    combined = base + ', ' + suggestion;
  }

  input.value = combined;
  input.focus();

  const end = combined.length;
  try { input.setSelectionRange(end, end); } catch (err) {}

  try { input.dispatchEvent(new Event('input', { bubbles: true })); } catch (err) {}
  try { input.dispatchEvent(new Event('change', { bubbles: true })); } catch (err) {}
}

function initMessageSuggestions() {
  const toggleBtn = getSuggestionsToggle();
  if (!toggleBtn) return;
  toggleBtn.setAttribute('type', 'button');

  let suggestionsDiv = getSuggestionsPanel();
  if (!suggestionsDiv) {
    suggestionsDiv = createSuggestionsPanel(toggleBtn);
  }
  if (!suggestionsDiv) return;

  buildSuggestionsPanelContent(suggestionsDiv);

  setSuggestionsPanel(false, false);

  if (suggestionsDelegationBound) {
    startSuggestionsGuardObserver();
    return;
  }
  suggestionsDelegationBound = true;

  document.addEventListener('click', (e) => {
    const target = e.target;
    if (!target || !target.closest) return;

    const chip = target.closest('.message-suggestion');
    if (chip) {
      e.preventDefault();
      const suggestion = chip.getAttribute('data-suggestion') || (chip.textContent || '').trim();
      const messageInput = getMessageInput();
      if (messageInput && suggestion) appendSuggestionToMessage(messageInput, suggestion);
      return;
    }

    let toggle = target.closest('#toggleSuggestions');
    if (!toggle) {
      const maybeToggle = target.closest('button, a');
      if (maybeToggle && !document.getElementById('toggleSuggestions') &&
          (maybeToggle.textContent || '').trim().toLowerCase().indexOf('suggestion') !== -1) {
        toggle = maybeToggle;
      }
    }
    if (toggle) {
      e.preventDefault();
      toggle.setAttribute('type', 'button');
      if (e.isTrusted !== false) handleSuggestionsToggleClick();
      return;
    }

    const formButton = target.closest('button, input[type="submit"], input[type="button"]');
    if (formButton) {
      const messageField = document.getElementById('message');
      if (messageField) {
        const form = formButton.closest('form');
        const section = formButton.closest('section');
        if ((form && form.contains(messageField)) ||
            (section && section.contains(messageField))) {
          closeMessageSuggestions();
        }
      }
    }
  });

  document.addEventListener('submit', closeMessageSuggestions, true);
  document.addEventListener('reset', closeMessageSuggestions, true);

  const guardMessageFieldInteractions = (e) => {
    if (e.target && (e.target.id === 'message' || e.target.tagName === 'TEXTAREA')) {
      enforceSuggestionsClosed();
    }
  };
  document.addEventListener('input', guardMessageFieldInteractions);
  document.addEventListener('focusin', guardMessageFieldInteractions);

  startSuggestionsGuardObserver();
}

/* =====================================================
   THEME TOGGLE
   ===================================================== */
function applyTheme() {
  const isDark = document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');

  const sunIcon = document.getElementById('sun-icon');
  const moonIcon = document.getElementById('moon-icon');

  if (isDark) {
    sunIcon.style.opacity = '0';
    sunIcon.style.transform = 'rotate(90deg) scale(0.5)';
    moonIcon.style.opacity = '1';
    moonIcon.style.transform = 'rotate(0deg) scale(1)';
  } else {
    sunIcon.style.opacity = '1';
    sunIcon.style.transform = 'rotate(0deg) scale(1)';
    moonIcon.style.opacity = '0';
    moonIcon.style.transform = 'rotate(-90deg) scale(0.5)';
  }
}

function toggleTheme(event) {
  if (!document.startViewTransition) {
    applyTheme();
    return;
  }
  document.startViewTransition(() => { applyTheme(); });
}

(function initThemeIcons() {
  const isDark = document.documentElement.classList.contains('dark');
  const sunIcon = document.getElementById('sun-icon');
  const moonIcon = document.getElementById('moon-icon');
  if (isDark) {
    sunIcon.style.opacity = '0';
    sunIcon.style.transform = 'rotate(90deg) scale(0.5)';
    moonIcon.style.opacity = '1';
    moonIcon.style.transform = 'rotate(0deg) scale(1)';
  } else {
    sunIcon.style.opacity = '1';
    sunIcon.style.transform = 'rotate(0deg) scale(1)';
    moonIcon.style.opacity = '0';
    moonIcon.style.transform = 'rotate(-90deg) scale(0.5)';
  }
})();

/* =====================================================
   INITIAL SKELETON AND FAQ
   ===================================================== */
let initialSkeletonComplete = false;

function completeInitialSkeleton() {
  if (initialSkeletonComplete) return;
  initialSkeletonComplete = true;
  requestAnimationFrame(() => {
    const shell = document.querySelector('.portfolio-shell');
    if (shell) shell.classList.remove('is-initial-loading');
    window.setTimeout(deactivateScrollLock, 500);
  });
}

window.setTimeout(completeInitialSkeleton, 3000);

function initFaqAccordion() {
  const faqItems = Array.from(document.querySelectorAll('.faq-item'));
  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');
    if (!question || !answer) return;

    question.addEventListener('click', () => {
      const isOpen = question.getAttribute('aria-expanded') === 'true';
      faqItems.forEach(otherItem => {
        if (otherItem === item) return;
        const otherQuestion = otherItem.querySelector('.faq-question');
        const otherAnswer = otherItem.querySelector('.faq-answer');
        if (!otherQuestion || !otherAnswer) return;
        otherQuestion.setAttribute('aria-expanded', 'false');
        otherAnswer.setAttribute('aria-hidden', 'true');
        otherItem.classList.remove('is-open');
        otherAnswer.style.maxHeight = '0px';
      });
      question.setAttribute('aria-expanded', String(!isOpen));
      answer.setAttribute('aria-hidden', String(isOpen));
      item.classList.toggle('is-open', !isOpen);
      answer.style.maxHeight = isOpen ? '0px' : answer.scrollHeight + 'px';
    });
  });

  window.addEventListener('resize', () => {
    faqItems.forEach(item => {
      const question = item.querySelector('.faq-question');
      const answer = item.querySelector('.faq-answer');
      if (question && answer && question.getAttribute('aria-expanded') === 'true') {
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });
}

/* =====================================================
   VIEW SWITCHING — Re-triggers animation every click
   ===================================================== */

function switchView(viewName) {
  const target = document.getElementById('view-' + viewName);
  if (!target) return Promise.resolve();

  const wasActive = target.classList.contains('active');

  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));

  if (wasActive) {
    void target.offsetWidth;
  }

  target.classList.add('active');
  const shell = document.querySelector('.portfolio-shell');
  if (shell) shell.setAttribute('data-active-view', viewName);

  document.querySelectorAll('[data-nav]').forEach(btn => {
    btn.removeAttribute('data-active');
  });
  const activeNav = document.querySelector('[data-nav="' + viewName + '"]');
  if (activeNav) activeNav.setAttribute('data-active', 'true');

  window.scrollTo({ top: 0, behavior: 'smooth' });
  return Promise.resolve();
}

function navigateToContact() {
  switchView('home').then(() => {
    const contact = document.getElementById('section-contact');
    if (contact) contact.scrollIntoView({ behavior: 'smooth' });
  });
}

function navigateToSection(sectionId) {
  switchView('home').then(() => {
    const section = document.getElementById(sectionId);
    if (section) section.scrollIntoView({ behavior: 'smooth' });
  });
}

/* =====================================================
   FEATURED PROJECTS (Selected Works)
   ===================================================== */
function initFeaturedProjects() {
  const grid = document.getElementById('featuredGrid');
  if (!grid) return;

  const FEATURED_LIMIT = 4;
  const cards = Array.from(grid.children).filter(el => el.classList.contains('project-card'));

  cards.forEach((card, index) => {
    if (index < FEATURED_LIMIT) {
      card.classList.remove('hidden');
    } else {
      card.classList.add('hidden');
    }
  });

  const remaining = Math.max(0, cards.length - FEATURED_LIMIT);

  const existingWrap = document.getElementById('exploreExtraWrap');
  if (existingWrap) existingWrap.remove();
  const existingBtn = document.getElementById('exploreExtraProjectsBtn');
  if (existingBtn) existingBtn.remove();

  if (remaining <= 0) return;

  const label = remaining === 1
    ? 'Explore 1+ Project'
    : 'Explore ' + remaining + '+ Projects';

  const btn = document.createElement('button');
  btn.type = 'button';
  btn.id = 'exploreExtraProjectsBtn';
  btn.className = 'explore-extra-btn';
  btn.innerHTML =
    '<span>' + label + '</span>' +
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
    '<path d="M5 12h14"/><path d="M12 5l7 7-7 7"/>' +
    '</svg>';
  btn.setAttribute('aria-label', label);
  btn.addEventListener('click', () => switchView('projects'));

  const wrap = document.createElement('div');
  wrap.id = 'exploreExtraWrap';
  wrap.className = 'explore-extra-wrap';
  wrap.appendChild(btn);

  grid.insertAdjacentElement('afterend', wrap);
}

/* =====================================================
   PROJECT DATA — Splash Screen FIRST for mobile projects
   ===================================================== */
const projectData = {
  novaai: {
    title: 'NOVA AI',
    image: window.portfolioAssetBase + 'NOVAAI_Banner.png',
    description: 'An AI-powered mobile chat app where users can ask questions, get instant responses, solve problems, generate content, and explore ideas through intelligent AI conversations.',
    categories: ['Mobile Application Design', 'AI / Chat Assistant'],
    type: 'mobile',
    gallery: [
      { type: 'image', src: window.portfolioAssetBase + 'NOVAAI_SplashScreen.png', alt: 'NOVA AI Splash Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'NOVAAI_LoginScreen.png', alt: 'NOVA AI Login Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'NOVAAI_DashboardScreen.png', alt: 'NOVA AI Dashboard Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'NOVAAI_TalkScreen.png', alt: 'NOVA AI Talk Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'NOVAAI_ChatScreen.png', alt: 'NOVA AI Chat Screen' }
    ]
  },
  flowza: {
    title: 'FLOWZA',
    image: window.portfolioAssetBase + 'FLOWZA_Banner.png',
    description: 'A web-based dashboard platform that helps teams track active projects, monitor progress, manage tasks and deadlines, and stay updated on team activity.',
    categories: ['Web Design', 'SaaS Dashboard Design'],
    type: 'web',
    gallery: [
      { type: 'image', src: window.portfolioAssetBase + 'FLOWZA_DashboardScreen1.png', alt: 'FLOWZA Dashboard Screen 1' },
      { type: 'image', src: window.portfolioAssetBase + 'FLOWZA_DashboardScreen2.png', alt: 'FLOWZA Dashboard Screen 2' }
    ]
  },
  planty: {
    title: 'Planty',
    image: window.portfolioAssetBase + 'Planty_Banner.png',
    description: 'A mobile plant e-commerce app where users can browse and purchase plant, manage their cart, and check out using card payment or cash on delivery.',
    categories: ['Mobile Application Design', 'E-commerce'],
    type: 'mobile',
    gallery: [
      { type: 'image', src: window.portfolioAssetBase + 'Planty_SplashScreen.png', alt: 'Planty Splash Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'Planty_HomeScreen.png', alt: 'Planty Home Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'Planty_CartScreen.png', alt: 'Planty Cart Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'Planty_ItemDetailsScreen.png', alt: 'Planty Item Details Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'Planty_QuantityScreen.png', alt: 'Planty Quantity Screen' }
    ]
  },
  musicplayer: {
    title: 'Music Player',
    image: window.portfolioAssetBase + 'MusicPlayer_Banner.png',
    description: 'A music streaming web app where user can discover artists and trending songs, search genres, play and queue music, save favorite, and view friend listening activities.',
    categories: ['Web Design', 'Music Streaming'],
    type: 'web',
    gallery: [
      { type: 'image', src: window.portfolioAssetBase + 'MusicPlayer1.png', alt: 'Music Player Screen 1' },
      { type: 'image', src: window.portfolioAssetBase + 'MusicPlayer2.png', alt: 'Music Player Screen 2' },
      { type: 'image', src: window.portfolioAssetBase + 'MusicPlayer3.png', alt: 'Music Player Screen 3' }
    ]
  },
  carrental: {
    title: 'Car Rental',
    image: window.portfolioAssetBase + 'CarRental_Banner.png',
    description: 'A mobile car rental app where user can explore available cars, discover vehicle on the map, save favorite, and easily book or rent their preferred car.',
    categories: ['Mobile Application Design', 'Car Rental / Rental Service'],
    type: 'mobile',
    gallery: [
      { type: 'image', src: window.portfolioAssetBase + 'CarRental_SplashScreen.png', alt: 'Car Rental Splash Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'CarRental_HomeScreen.png', alt: 'Car Rental Home Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'CarRental_CarDetailsScreen.png', alt: 'Car Rental Car Details Screen' },
      { type: 'image', src: window.portfolioAssetBase + 'CarRental_CarMapScreen.png', alt: 'Car Rental Car Map Screen' }
    ]
  },
  norva: {
    title: 'NORVA',
    image: window.portfolioAssetBase + 'Norva_Banner.png',
    description: 'A modern web e-commerce website where users can browse and shop for jackets and pants, view product details, add items to their cart, and manage their selected products.',
    categories: ['Web Design', 'E-commerce'],
    type: 'web',
    gallery: [
      { type: 'image', src: window.portfolioAssetBase + 'NORVA_HomeWeb.png', alt: 'NORVA Home Web' },
      { type: 'image', src: window.portfolioAssetBase + 'NORVA_Jacket&PantsWeb.png', alt: 'NORVA Jackets & Pants Web' },
      { type: 'image', src: window.portfolioAssetBase + 'NORVA_NewArrivalsWeb.png', alt: 'NORVA New Arrivals Web' },
      { type: 'image', src: window.portfolioAssetBase + 'NORVA_About&FooterWeb.png', alt: 'NORVA About & Footer Web' }
    ]
  }
};

let lastViewBeforeProject = 'home';

/* ===== Helper to build clean interface shot (no device mockup) ===== */
function buildInterfaceShot(src, alt) {
  const wrap = document.createElement('div');
  wrap.className = 'interface-shot';
  wrap.innerHTML = `<img src="${src}" alt="${alt}" loading="lazy">`;
  wrap.addEventListener('click', () => openImageLightbox(src, alt, window.activeLightboxItems, wrap.dataset.lightboxIndex));
  return wrap;
}

/* ===== Helper to build clean web slide (no mockup) ===== */
function buildWebSlide(src, alt, items, index) {
  const slide = document.createElement('div');
  slide.className = 'web-slide';
  const imgEl = document.createElement('img');
  imgEl.src = src;
  imgEl.alt = alt;
  imgEl.loading = 'lazy';
  imgEl.addEventListener('click', () => openImageLightbox(src, alt, items, index));
  slide.appendChild(imgEl);
  return slide;
}

/* =====================================================
   OPEN PROJECT — builds mobile grid / web carousel
   ===================================================== */
function openProject(projectId) {
  const project = projectData[projectId];
  if (!project) return;

  const activeView = document.querySelector('.view.active');
  if (activeView) {
    lastViewBeforeProject = activeView.id.replace('view-', '');
  }

  document.getElementById('project-detail-title').textContent = project.title;
  const img = document.getElementById('project-detail-image');
  img.src = project.image;
  img.alt = project.title;

  const desc = document.getElementById('project-detail-description');
  desc.textContent = project.description;

  const catContainer = document.getElementById('project-detail-categories');
  catContainer.innerHTML = '';
  project.categories.forEach(cat => {
    const span = document.createElement('span');
    span.className = 'category-pill';
    span.textContent = cat;
    catContainer.appendChild(span);
  });

  /* ---------- Build gallery ---------- */
  const galleryContainer = document.getElementById('project-detail-gallery');
  galleryContainer.innerHTML = '';

  if (!project.gallery || project.gallery.length === 0) return;

  let images = project.gallery.filter(i => i.type === 'image');

  if (project.type === 'mobile') {
    const splashIdx = images.findIndex(im =>
      im.alt && im.alt.toLowerCase().includes('splash')
    );
    if (splashIdx > 0) {
      const [splashItem] = images.splice(splashIdx, 1);
      images.unshift(splashItem);
    }
  }

  let itemIndex = 0;

  /* ===== Mobile projects → clean interface grid ===== */
  if (project.type === 'mobile' && images.length > 0) {
    const sectionHeading = document.createElement('p');
    sectionHeading.className = 'text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500';
    sectionHeading.textContent = 'Interfaces';
    galleryContainer.appendChild(sectionHeading);

    const grid = document.createElement('div');
    grid.className = 'interface-grid';

    images.forEach((image, idx) => {
      const shot = buildInterfaceShot(image.src, image.alt || project.title + ' screen');
      shot.dataset.lightboxIndex = String(idx);
      shot.style.animationDelay = (0.4 + idx * 0.08) + 's';
      grid.appendChild(shot);
    });

    window.activeLightboxItems = images.map(image => ({ src: image.src, alt: image.alt || project.title + ' screen' }));

    galleryContainer.appendChild(grid);
  }

  /* ===== Web projects → direct screenshot carousel (no mockup) ===== */
  if (project.type === 'web' && images.length > 0) {
    const sectionHeading = document.createElement('p');
    sectionHeading.className = 'text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500';
    sectionHeading.textContent = 'Interfaces';
    galleryContainer.appendChild(sectionHeading);

    const stage = document.createElement('div');
    stage.className = 'mockup-stage';
    stage.style.animationDelay = (0.4 + itemIndex * 0.08) + 's';
    itemIndex++;

    const display = document.createElement('div');
    display.className = 'web-screen-display';

    const carouselStage = document.createElement('div');
    carouselStage.className = 'web-carousel-stage';

    images.forEach((image, idx) => {
      const slide = buildWebSlide(image.src, image.alt || project.title + ' screen', images.map(item => ({ src: item.src, alt: item.alt || project.title + ' screen' })), idx);
      if (idx === 0) slide.classList.add('active');
      carouselStage.appendChild(slide);
    });

    display.appendChild(carouselStage);
    stage.appendChild(display);

    const controls = document.createElement('div');
    controls.className = 'carousel-controls';

    const prevBtn = document.createElement('button');
    prevBtn.className = 'carousel-btn';
    prevBtn.type = 'button';
    prevBtn.innerHTML = `
      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
      <span>Previous</span>
    `;

    const counter = document.createElement('span');
    counter.className = 'carousel-counter';
    counter.textContent = `1 / ${images.length}`;

    const nextBtn = document.createElement('button');
    nextBtn.className = 'carousel-btn';
    nextBtn.type = 'button';
    nextBtn.innerHTML = `
      <span>Next</span>
      <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
    `;

    controls.appendChild(prevBtn);
    controls.appendChild(counter);
    controls.appendChild(nextBtn);

    stage.appendChild(controls);
    galleryContainer.appendChild(stage);

    let currentIdx = 0;
    const slides = carouselStage.querySelectorAll('.web-slide');

    function updateCarousel() {
      slides.forEach((s, i) => s.classList.toggle('active', i === currentIdx));
      counter.textContent = `${currentIdx + 1} / ${images.length}`;
      prevBtn.disabled = currentIdx === 0;
      nextBtn.disabled = currentIdx === images.length - 1;
    }

    prevBtn.addEventListener('click', () => {
      if (currentIdx > 0) {
        currentIdx--;
        updateCarousel();
      }
    });
    nextBtn.addEventListener('click', () => {
      if (currentIdx < images.length - 1) {
        currentIdx++;
        updateCarousel();
      }
    });

    updateCarousel();
  }

  switchView('project-detail');
}

function closeProject() {
  switchView(lastViewBeforeProject || 'projects');
}

/* =====================================================
   DESIGN STACK — TOGGLE ANIMATION (Sliding ↔ Organized)
   ===================================================== */
let stackOrganized = false;
let stackSliders = [];

function toggleStackAnimation() {
  const sliding = document.getElementById('stackSliding');
  const organized = document.getElementById('stackOrganized');
  const gridIcon = document.getElementById('stackToggleIconGrid');
  const stackIcon = document.getElementById('stackToggleIconStack');

  stackOrganized = !stackOrganized;

  if (stackOrganized) {
    sliding.classList.add('hidden');
    organized.classList.remove('hidden');
    gridIcon.classList.add('hidden');
    stackIcon.classList.remove('hidden');
    stackSliders.forEach(s => s.stop());
  } else {
    sliding.classList.remove('hidden');
    organized.classList.add('hidden');
    gridIcon.classList.remove('hidden');
    stackIcon.classList.add('hidden');
    stackSliders.forEach(s => s.start());
  }
}

/* =====================================================
   DESIGN STACK VIEW ALL — FILTERS
   ===================================================== */
function filterDesignStack(category) {
  const sections = document.querySelectorAll('#view-design-stack .ds-category');
  sections.forEach(section => {
    if (category === 'all' || section.dataset.category === category) {
      section.classList.remove('hidden');
    } else {
      section.classList.add('hidden');
    }
  });

  document.querySelectorAll('#view-design-stack .filter-btn').forEach(btn => {
    btn.classList.remove('active-filter');
  });
  const activeBtn = document.querySelector('#view-design-stack [data-filter="' + category + '"]');
  if (activeBtn) activeBtn.classList.add('active-filter');
}

/* =====================================================
   DRAG SLIDER — JS-controlled seamless loop with drag
   ===================================================== */
function createDragSlider(track, container, direction) {
  let position = direction === 'right' ? null : 0;
  const speed = 0.4;
  let isDragging = false;
  let startX = 0;
  let startPosition = 0;
  let velocity = 0;
  let lastX = 0;
  let lastTime = 0;
  let isRunning = true;
  let pointerId = null;
  let hasDragged = false;

  function getTrackWidth() {
    return track.scrollWidth / 2;
  }

  function wrapPosition() {
    const trackWidth = getTrackWidth();
    if (trackWidth <= 0) return;
    if (direction === 'left') {
      while (position <= -trackWidth) position += trackWidth;
      while (position > 0) position -= trackWidth;
    } else {
      while (position >= 0) position -= trackWidth;
      while (position < -trackWidth) position += trackWidth;
    }
  }

  function update() {
    const trackWidth = getTrackWidth();

    if (position === null && trackWidth > 0) {
      position = -trackWidth;
    }

    if (isRunning && !isDragging) {
      if (direction === 'left') {
        position -= speed;
        if (position <= -trackWidth) position += trackWidth;
      } else {
        position += speed;
        if (position >= 0) position -= trackWidth;
      }

      if (Math.abs(velocity) > 0.1) {
        position += velocity;
        velocity *= 0.92;
      } else {
        velocity = 0;
      }
    }

    track.style.transform = 'translateX(' + position + 'px)';
    requestAnimationFrame(update);
  }

  container.addEventListener('pointerdown', (e) => {
    isDragging = true;
    hasDragged = false;
    pointerId = e.pointerId;
    startX = e.clientX;
    startPosition = position;
    lastX = e.clientX;
    lastTime = Date.now();
    velocity = 0;
    try { container.setPointerCapture(e.pointerId); } catch(err) {}
    container.style.cursor = 'grabbing';
  });

  container.addEventListener('pointermove', (e) => {
    if (!isDragging || e.pointerId !== pointerId) return;
    const delta = e.clientX - startX;
    if (Math.abs(delta) > 3) hasDragged = true;
    position = startPosition + delta;

    const now = Date.now();
    const dt = now - lastTime;
    if (dt > 0) {
      velocity = (e.clientX - lastX) / dt * 16;
    }
    lastX = e.clientX;
    lastTime = now;
  });

  function endDrag(e) {
    if (!isDragging) return;
    isDragging = false;
    pointerId = null;
    container.style.cursor = '';
    wrapPosition();
  }

  container.addEventListener('pointerup', endDrag);
  container.addEventListener('pointercancel', endDrag);
  container.addEventListener('pointerleave', endDrag);

  container.addEventListener('click', (e) => {
    if (hasDragged) {
      e.preventDefault();
      e.stopPropagation();
      hasDragged = false;
    }
  }, true);

  update();

  return {
    stop: () => { isRunning = false; },
    start: () => { isRunning = true; }
  };
}

(function initSliders() {
  const track1 = document.getElementById('techTrack1');
  const container1 = document.getElementById('techContainer1');
  const track2 = document.getElementById('techTrack2');
  const container2 = document.getElementById('techContainer2');

  if (track1 && container1) {
    stackSliders.push(createDragSlider(track1, container1, 'left'));
  }
  if (track2 && container2) {
    stackSliders.push(createDragSlider(track2, container2, 'right'));
  }
})();

/* =====================================================
   PROFILE IMAGE — Pixelated Dissolve Transition System
   ===================================================== */
(function initPixelatedTransition() {
  const aboutImage = document.getElementById('aboutImage');
  if (!aboutImage) return;

  const pixelCanvas = aboutImage.querySelector('.pixel-canvas');
  const imgDefault = aboutImage.querySelector('.img-default');
  const imgGrad = aboutImage.querySelector('.img-grad');
  if (!pixelCanvas || !imgDefault || !imgGrad) return;

  const cols = 12, rows = 12;
  const pixelSize = 100 / cols;
  const pixels = [];

  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      const pixel = document.createElement('div');
      pixel.className = 'pixel';
      pixel.style.width = pixelSize + '%';
      pixel.style.height = pixelSize + '%';
      pixel.style.left = (c * pixelSize) + '%';
      pixel.style.top = (r * pixelSize) + '%';
      pixelCanvas.appendChild(pixel);
      pixels.push(pixel);
    }
  }

  const COVER_SPREAD_MS = 500;
  const UNCOVER_SPREAD_MS = 400;
  const SWAP_DELAY_MS = 350;
  const PIXEL_FADE_MS = 200;
  const COMPLETE_BUFFER_MS = 100;

  let displayed = 'default';
  let phase = 'idle';
  let intention = 'default';
  let swapTimer = null;
  let completeTimer = null;

  function clearTimers() {
    if (swapTimer) { clearTimeout(swapTimer); swapTimer = null; }
    if (completeTimer) { clearTimeout(completeTimer); completeTimer = null; }
  }

  function shuffle(arr) {
    const a = arr.slice();
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      const tmp = a[i];
      a[i] = a[j];
      a[j] = tmp;
    }
    return a;
  }

  function coverPixels() {
    const shuffled = shuffle(pixels);
    shuffled.forEach((pixel, i) => {
      const baseDelay = (i / shuffled.length) * (COVER_SPREAD_MS / 1000);
      const jitter = (Math.random() - 0.5) * 0.05;
      pixel.style.transitionDelay = Math.max(0, baseDelay + jitter) + 's';
      pixel.style.opacity = '1';
    });
  }

  function uncoverPixels() {
    const shuffled = shuffle(pixels);
    shuffled.forEach((pixel, i) => {
      const baseDelay = (i / shuffled.length) * (UNCOVER_SPREAD_MS / 1000);
      const jitter = (Math.random() - 0.5) * 0.05;
      pixel.style.transitionDelay = Math.max(0, baseDelay + jitter) + 's';
      pixel.style.opacity = '0';
    });
  }

  function setDisplayed(target) {
    if (target === 'grad') {
      imgDefault.style.opacity = '0';
      imgGrad.style.opacity = '1';
    } else {
      imgGrad.style.opacity = '0';
      imgDefault.style.opacity = '1';
    }
    displayed = target;
  }

  function startCoverAndSwap(target) {
    phase = 'covering';
    coverPixels();

    swapTimer = setTimeout(() => {
      setDisplayed(target);
      phase = 'uncovering';
      uncoverPixels();

      completeTimer = setTimeout(() => {
        phase = 'idle';
        if (intention !== displayed) {
          transitionTo(intention);
        }
      }, UNCOVER_SPREAD_MS + PIXEL_FADE_MS + COMPLETE_BUFFER_MS);
    }, SWAP_DELAY_MS);
  }

  function cancelCoverAndUncover() {
    clearTimers();
    phase = 'uncovering';
    uncoverPixels();

    completeTimer = setTimeout(() => {
      phase = 'idle';
      if (intention !== displayed) {
        transitionTo(intention);
      }
    }, UNCOVER_SPREAD_MS + PIXEL_FADE_MS + COMPLETE_BUFFER_MS);
  }

  function transitionTo(target) {
    intention = target;

    if (phase === 'idle' && displayed === target) return;

    if (phase === 'covering' && displayed === target) {
      cancelCoverAndUncover();
      return;
    }

    if (phase === 'covering' && displayed !== target) return;

    if (phase === 'uncovering' && displayed !== target) {
      clearTimers();
      startCoverAndSwap(target);
      return;
    }

    if (phase === 'uncovering' && displayed === target) return;

    if (phase === 'idle') {
      startCoverAndSwap(target);
    }
  }

  aboutImage.addEventListener('mouseenter', () => transitionTo('grad'));
  aboutImage.addEventListener('mouseleave', () => transitionTo('default'));
  aboutImage.addEventListener('focus', () => transitionTo('grad'));
  aboutImage.addEventListener('blur', () => transitionTo('default'));
})();

/* =====================================================
   STACKED IMAGE CARDS — Click / Tap only
   ===================================================== */
(function initStackCards() {
  const container = document.getElementById('imageStack');
  if (!container) return;

  const cards = Array.from(container.querySelectorAll('.stack-card'));
  if (cards.length === 0) return;

  let order = cards.map((_, i) => i);
  let clickAlternate = 0;
  let isAnimating = false;

  const baseTransforms = [
    { scale: 1.0,  rotate: 0,   x: 0,  y: 0  },
    { scale: 0.94, rotate: 3,   x: 6,  y: 10 },
    { scale: 0.88, rotate: -3,  x: -4, y: 20 },
    { scale: 0.82, rotate: 2,   x: 3,  y: 30 },
  ];

  function getBaseTransform(position) {
    if (position < baseTransforms.length) return baseTransforms[position];
    return {
      scale: Math.max(0.7, 0.94 - position * 0.05),
      rotate: (position % 2 === 0 ? 1 : -1) * (position * 2),
      x: position * 3,
      y: position * 10
    };
  }

  function renderStack() {
    order.forEach((cardIndex, position) => {
      const card = cards[cardIndex];
      const t = getBaseTransform(position);
      card.style.zIndex = String(100 - position);
      card.style.transform = 'translate(' + t.x + 'px, ' + t.y + 'px) scale(' + t.scale + ') rotate(' + t.rotate + 'deg)';
      card.dataset.position = String(position);
      card.style.pointerEvents = position === 0 ? 'auto' : 'none';
      card.style.opacity = '1';
    });
  }

  function sendFrontToBack() {
    const front = order.shift();
    order.push(front);
    renderStack();
  }

  function animateCardToBack(card, direction) {
    if (isAnimating) return;
    isAnimating = true;

    const flyX = direction === 'right' ? 150 : -150;
    const flyY = 20;
    const rot = direction === 'right' ? 15 : -15;

    card.style.transition = 'transform 0.3s cubic-bezier(0.22,1,0.36,1)';
    card.style.transform = 'translate(' + flyX + 'px, ' + flyY + 'px) scale(0.85) rotate(' + rot + 'deg)';

    setTimeout(() => {
      card.style.transition = 'transform 0.55s cubic-bezier(0.22,1,0.36,1)';
      sendFrontToBack();
      setTimeout(() => {
        isAnimating = false;
      }, 250);
    }, 300);
  }

  cards.forEach(card => {
    card.addEventListener('click', () => {
      if (parseInt(card.dataset.position) !== 0) return;
      const direction = clickAlternate % 2 === 0 ? 'right' : 'left';
      clickAlternate++;
      animateCardToBack(card, direction);
    });

    card.setAttribute('tabindex', '0');
    card.setAttribute('role', 'button');
    card.setAttribute('aria-label', 'Stack card — click or press Enter to cycle');
    card.addEventListener('keydown', (e) => {
      if (parseInt(card.dataset.position) !== 0) return;
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        const direction = clickAlternate % 2 === 0 ? 'right' : 'left';
        clickAlternate++;
        animateCardToBack(card, direction);
      }
    });
  });

  renderStack();
})();

/* =====================================================
   PROJECT CARD HOVER — Glow tracking only
   ===================================================== */
(function initProjectCardHover() {
  const cards = document.querySelectorAll('.project-card');
  cards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const px = (x / rect.width) * 100;
      const py = (y / rect.height) * 100;
      card.style.setProperty('--mx', px + '%');
      card.style.setProperty('--my', py + '%');
    });
  });
})();