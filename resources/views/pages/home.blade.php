@extends("layouts.app")

@section("content")

  <div class="min-h-screen flex flex-col portfolio-shell is-initial-loading" data-active-view="home">

    <div id="initial-skeleton" aria-hidden="true">
      <div class="mx-auto w-full max-w-3xl px-4 sm:px-6">
        <div class="skeleton-nav"></div>
        <div class="skeleton-hero">
          <div class="skeleton-avatar"></div>
          <div class="skeleton-hero-copy">
            <div class="skeleton-line skeleton-name"></div>
            <div class="skeleton-line skeleton-social"></div>
          </div>
        </div>
        <div class="skeleton-line skeleton-title"></div>
        <div class="skeleton-line skeleton-paragraph"></div>
        <div class="skeleton-line skeleton-paragraph skeleton-paragraph-short"></div>
        <div class="skeleton-button"></div>
        <div class="skeleton-section-heading"></div>
        <div class="skeleton-project-grid">
          <div class="skeleton-project-card"><div class="skeleton-project-image"></div><div class="skeleton-line skeleton-card-title"></div><div class="skeleton-line skeleton-card-copy"></div></div>
          <div class="skeleton-project-card"><div class="skeleton-project-image"></div><div class="skeleton-line skeleton-card-title"></div><div class="skeleton-line skeleton-card-copy"></div></div>
        </div>
      </div>
    </div>

    <!-- ===== Header / Navigation ===== -->
    <header class="sticky top-0 z-40 bg-white/70 dark:bg-ink/80 backdrop-blur-xl border-b border-gray-100 dark:border-gray-800">
      <nav class="max-w-3xl w-full mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-4">
        <button onclick="switchView('home')" class="text-sm font-bold text-black dark:text-white tracking-tight">
          KRL.MMBRR
        </button>

        <div class="flex items-center gap-3 sm:gap-5">
          <!-- Desktop nav links — hidden on mobile, burger menu used instead -->
          <div class="hidden sm:flex items-center gap-3 sm:gap-4">
            <!-- Nav order: Projects → Experience → FAQs → Gallery → Contact -->
            <button onclick="switchView('projects')" class="nav-link text-xs sm:text-sm font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" data-nav="projects">
              Projects
            </button>
            <button onclick="switchView('experience')" class="nav-link text-xs sm:text-sm font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" data-nav="experience">
              Experience
            </button>
            <button onclick="switchView('faq')" class="nav-link text-xs sm:text-sm font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" data-nav="faq">
              FAQs
            </button>
            <button onclick="switchView('gallery')" class="nav-link text-xs sm:text-sm font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" data-nav="gallery">
              Gallery
            </button>
            <button onclick="navigateToContact()" class="nav-link text-xs sm:text-sm font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
              Contact
            </button>
          </div>

          <!-- Desktop divider — hidden on mobile -->
          <div class="hidden sm:block w-px h-5 bg-gray-200 dark:bg-gray-700"></div>

          <!-- Burger menu button — mobile only (theme toggle stays outside) -->
          <button id="burgerMenuBtn" type="button" onclick="toggleBurgerMenu()" class="sm:hidden relative w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" aria-label="Toggle menu" aria-expanded="false">
            <svg id="burgerIconOpen" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="3" y1="6" x2="21" y2="6"/>
              <line x1="3" y1="12" x2="21" y2="12"/>
              <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <svg id="burgerIconClose" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="6" y1="6" x2="18" y2="18"/>
              <line x1="6" y1="18" x2="18" y2="6"/>
            </svg>
          </button>

          <!-- Theme toggle — always visible (outside burger menu) -->
          <button type="button" onclick="toggleTheme(event)" class="relative w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg id="sun-icon" class="w-4 h-4 absolute transition-all duration-500 dark:opacity-0 dark:rotate-90 dark:scale-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="4"/>
              <path d="M12 2V4M12 20V22M4 12H2M22 12H20M19.07 4.93L17.66 6.34M6.34 17.66L4.93 19.07M19.07 19.07L17.66 17.66M6.34 6.34L4.93 4.93"/>
            </svg>
            <svg id="moon-icon" class="w-4 h-4 absolute transition-all duration-500 opacity-0 -rotate-90 scale-50 dark:opacity-100 dark:rotate-0 dark:scale-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>
            </svg>
          </button>
        </div>
      </nav>
    </header>

    <!-- ===== Mobile Burger Menu — Full-Screen Floating Overlay ===== -->
    <div id="mobileNavPanel" class="hidden bg-white dark:bg-ink">
      <!-- Overlay top bar: Logo + Close -->
      <div class="border-b border-gray-100 dark:border-gray-800">
        <div class="max-w-3xl w-full mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
          <button onclick="switchView('home'); closeBurgerMenu()" class="text-sm font-bold text-black dark:text-white tracking-tight">
            KRL.MMBRR
          </button>
          <button onclick="closeBurgerMenu()" class="relative w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors" aria-label="Close menu">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="6" y1="6" x2="18" y2="18"/>
              <line x1="6" y1="18" x2="18" y2="6"/>
            </svg>
          </button>
        </div>
      </div>
      <!-- Nav items -->
      <div class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 py-4 flex flex-col gap-1">
        <button onclick="switchView('home'); closeBurgerMenu()" class="nav-link text-lg font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors text-left py-3" data-nav="home">Home</button>
        <button onclick="switchView('projects'); closeBurgerMenu()" class="nav-link text-lg font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors text-left py-3" data-nav="projects">Projects</button>
        <button onclick="switchView('experience'); closeBurgerMenu()" class="nav-link text-lg font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors text-left py-3" data-nav="experience">Experience</button>
        <button onclick="switchView('faq'); closeBurgerMenu()" class="nav-link text-lg font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors text-left py-3" data-nav="faq">FAQs</button>
        <button onclick="switchView('gallery'); closeBurgerMenu()" class="nav-link text-lg font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors text-left py-3" data-nav="gallery">Gallery</button>
        <button onclick="navigateToContact(); closeBurgerMenu()" class="nav-link text-lg font-normal text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors text-left py-3">Contact</button>
      </div>
    </div>

    <div class="flex-grow w-full">

      <!-- ============================================== -->
      <!-- ============= HOME VIEW ====================== -->
      <!-- ============================================== -->
      <div id="view-home" class="view active">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-14 sm:gap-16 px-4 sm:px-6 pb-10 sm:pb-16">

          <!-- Hero Section -->
          <section class="flex flex-col justify-center pt-6 pb-8 sm:pt-16 sm:pb-8">
            <div class="space-y-6 sm:space-y-10">
              <div class="flex items-center mb-4 gap-4 sm:gap-6">
                <div class="profile-image-wrap about-image h-32 w-32 shrink-0 rounded-full border-2 border-gray-200 bg-white shadow-sm sm:h-40 sm:w-40 dark:border-gray-500 dark:bg-ink cursor-pointer" tabindex="0" id="aboutImage">
                  <img class="img-default" src="{{ asset('assets/kaneki.png') }}" onerror="this.onerror=null;this.src='{{ asset('assets/kaneki.png') }}'" alt="Portrait of Karl Justine Membrere" fetchpriority="high">
                  <img class="img-grad" src="{{ asset('assets/formal.png') }}" alt="" aria-hidden="true" fetchpriority="high">
                  <div class="pixel-canvas" aria-hidden="true"></div>
                </div>

                <div class="flex h-full flex-col justify-center gap-2.5 sm:gap-3">
                  <h1 class="flex items-center gap-2 text-xl sm:text-2xl md:text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Karl Justine Membrere
                    <svg viewBox="0 0 22 22" class="w-6 h-6 shrink-0 inline-block align-middle" title="Verified" aria-label="Verified">
                      <path fill="#1D9BF0" d="M20.396 11c-.018-.646-.215-1.275-.57-1.816-.354-.54-.852-.972-1.438-1.246.223-.607.27-1.264.14-1.897-.131-.634-.437-1.218-.882-1.687-.47-.445-1.053-.75-1.687-.882-.633-.13-1.29-.083-1.897.14-.273-.587-.704-1.086-1.245-1.44S11.647 1.62 11 1.604c-.646.017-1.273.213-1.813.568s-.969.854-1.24 1.44c-.608-.223-1.267-.272-1.902-.14-.635.13-1.22.436-1.69.882-.445.47-.749 1.053-.878 1.688-.13.633-.08 1.29.144 1.896-.587.274-1.087.705-1.443 1.245-.356.54-.555 1.17-.574 1.817.02.647.218 1.276.574 1.817.356.54.856.972 1.443 1.245-.224.607-.274 1.264-.144 1.898.13.634.435 1.219.88 1.688.47.443 1.054.749 1.688.879.633.13 1.29.083 1.897-.14.274.586.705 1.084 1.246 1.439.54.354 1.17.551 1.816.569.647-.016 1.276-.213 1.817-.567s.972-.854 1.245-1.44c.607.224 1.264.272 1.897.14.634-.13 1.217-.436 1.687-.878.445-.47.75-1.055.88-1.688.13-.634.083-1.291-.14-1.897.586-.274 1.084-.705 1.438-1.246.354-.541.551-1.17.57-1.817Zm-11.343 3.9-3.5-3.5 1.238-1.238 2.262 2.262 5.315-5.315L15.5 8.35l-6.5 6.55Z"></path>
                    </svg>
                  </h1>

                  <div class="flex items-start gap-3">
                    <!-- LinkedIn Link -->
                    <a href="https://www.linkedin.com/in/karl-justine-membrere-723787434/" target="_blank" rel="noopener noreferrer" class="opacity-70 hover:opacity-100 transition-all duration-300 ease-out hover:-translate-y-1" title="LinkedIn" aria-label="LinkedIn">
                      <i class="fab fa-linkedin text-xl text-gray-700 dark:text-gray-300 hover:text-[#0A66C2] dark:hover:text-[#0A66C2]"></i>
                    </a>
                    <!-- JobStreet Link with Logo Image -->
                    <a href="https://ph.jobstreet.com/profiles/karljustine-membrere-blvvw6jjyn" target="_blank" rel="noopener noreferrer" class="group opacity-70 hover:opacity-100 transition-all duration-300 ease-out hover:-translate-y-1" title="JobStreet">
                      <img src="{{ asset('assets/jobstreetlogo.png') }}" alt="JobStreet Logo" class="w-5 h-5 inline-block grayscale dark:invert group-hover:grayscale-0 group-hover:dark:invert-0 transition-all duration-300">
                    </a>
                    <a href="https://www.instagram.com/krl.mmbrr/" target="_blank" rel="noopener noreferrer" class="opacity-70 hover:opacity-100 transition-all duration-300 ease-out hover:-translate-y-1" title="Instagram">
                      <i class="fab fa-instagram text-xl text-gray-700 dark:text-gray-300 hover:text-[#E4405F] dark:hover:text-[#E4405F]"></i>
                    </a>
                    <a href="https://www.facebook.com/karlmembrere" target="_blank" rel="noopener noreferrer" class="opacity-70 hover:opacity-100 transition-all duration-300 ease-out hover:-translate-y-1" title="Facebook">
                      <i class="fab fa-facebook text-xl text-gray-700 dark:text-gray-300 hover:text-[#1877F2] dark:hover:text-[#1877F2]"></i>
                    </a>
                    <a href="mailto:karljustinermembrere11272003@gmail.com" class="opacity-70 hover:opacity-100 transition-all duration-300 ease-out hover:-translate-y-1" title="Email">
                      <i class="fas fa-envelope text-xl text-gray-700 dark:text-gray-300 hover:text-[#EA4335] dark:hover:text-[#EA4335]"></i>
                    </a>
                  </div>
                </div>
              </div>

              <div class="space-y-5 sm:space-y-6">
                <h2 class="max-w-full text-[1.7rem] font-normal tracking-tight leading-tight text-gray-900 dark:text-white sm:text-[2.05rem] md:text-[2.15rem]">
                  UI/UX Designer <span class="text-[0.95em] font-light text-gray-500 dark:text-gray-400">— Interfaces &amp; Experiences</span>
                </h2>

                <p class="text-base font-light leading-7 text-gray-500 dark:text-gray-400 sm:text-lg sm:leading-8">
                  I am a UI/UX Designer focused on creating clean, modern, and user-friendly interfaces with strong attention to usability, consistency, and user experience. I build prototypes with
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 align-middle whitespace-nowrap rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm mx-0.5">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 38 57" fill="none"><path d="M19 28.5a9.5 9.5 0 1 1 19 0 9.5 9.5 0 0 1-19 0Z" fill="#1ABCFE"/><path d="M0 47.5A9.5 9.5 0 0 1 9.5 38H19v9.5a9.5 9.5 0 1 1-19 0Z" fill="#0ACF83"/><path d="M19 0v19h9.5a9.5 9.5 0 1 0 0-19H19Z" fill="#FF7262"/><path d="M0 9.5A9.5 9.5 0 0 0 9.5 19H19V0H9.5A9.5 9.5 0 0 0 0 9.5Z" fill="#F24E1E"/><path d="M0 28.5A9.5 9.5 0 0 0 9.5 38H19V19H9.5A9.5 9.5 0 0 0 0 28.5Z" fill="#A259FF"/></svg>
                    <span>Figma</span>
                  </span>.
                </p>

                <div class="flex flex-wrap gap-3 sm:gap-4 pt-2">
                  <a href="{{ asset('assets/Karl_Justine_Membrere_Resume.pdf') }}" target="_blank" rel="noopener noreferrer" class="group inline-flex items-center gap-2 bg-ink px-6 py-3 text-base font-medium text-white rounded-lg hover:scale-[1.03] transition-transform dark:bg-white dark:text-black">
                    <span>View Resume</span>
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M9 18l6-6-6-6"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </section>

          <!-- ================================== -->
          <!-- ===== 1. SELECTED WORK =========== -->
          <!-- ================================== -->
          <section id="section-featured" class="w-full space-y-6">
            <div class="flex items-end justify-between gap-4">
              <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-gray-500 dark:text-gray-400">Featured Design</p>
                <h2 class="mt-1 text-xl sm:text-2xl font-light tracking-tight text-gray-900 dark:text-white">Selected Work</h2>
              </div>
              <button id="exploreMoreBtn" onclick="switchView('projects')" class="group inline-flex items-center gap-1.5 text-[11px] font-mono uppercase tracking-widest text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white shrink-0 pb-1">
                <span id="exploreMoreLabel">View All</span>
                <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M7 7h10v10"/><path d="M7 17L17 7"/>
                </svg>
              </button>
            </div>

            <div id="featuredGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
              <!-- Card 1: NOVA AI -->
              <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
                <a href="#" onclick="event.preventDefault(); openProject('novaai')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                  <img alt="NOVA AI" class="w-full h-auto" src="{{ asset('assets/NOVAAI_Banner.png') }}">
                </a>
                <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                  <a href="#" onclick="event.preventDefault(); openProject('novaai')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">NOVA AI</a>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">An AI-powered mobile chat app where users can ask questions, get instant responses, solve problems, generate content, and explore ideas through intelligent AI conversations.</p>
                  <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                    <span class="category-pill">Mobile Application Design</span>
                    <span class="category-pill">AI / Chat Assistant</span>
                  </div>
                </div>
              </div>

              <!-- Card 2: FLOWZA -->
              <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
                <a href="#" onclick="event.preventDefault(); openProject('flowza')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                  <img alt="FLOWZA" class="w-full h-auto" src="{{ asset('assets/FLOWZA_Banner.png') }}">
                </a>
                <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                  <a href="#" onclick="event.preventDefault(); openProject('flowza')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">FLOWZA</a>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A web-based dashboard platform that helps teams track active projects, monitor progress, manage tasks and deadlines, and stay updated on team activity.</p>
                  <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                    <span class="category-pill">Web Design</span>
                    <span class="category-pill">System Dashboard Design</span>
                  </div>
                </div>
              </div>

              <!-- Card 3: Planty -->
              <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
                <a href="#" onclick="event.preventDefault(); openProject('planty')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                  <img alt="Planty" class="w-full h-auto" src="{{ asset('assets/Planty_Banner.png') }}">
                </a>
                <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                  <a href="#" onclick="event.preventDefault(); openProject('planty')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">Planty</a>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A mobile plant e-commerce app where users can browse and purchase plant, manage their cart, and check out using card payment or cash on delivery.</p>
                  <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                    <span class="category-pill">Mobile Application Design</span>
                    <span class="category-pill">E-commerce</span>
                  </div>
                </div>
              </div>

              <!-- Card 4: NORVA -->
              <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
                <a href="#" onclick="event.preventDefault(); openProject('norva')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                  <img alt="NORVA" class="w-full h-auto" src="{{ asset('assets/Norva_Banner.png') }}">
                </a>
                <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                  <a href="#" onclick="event.preventDefault(); openProject('norva')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">NORVA</a>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A modern web e-commerce website where users can browse and shop for jackets and pants, view product details, add items to their cart, and manage their selected products.</p>
                  <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                    <span class="category-pill">Web Design</span>
                    <span class="category-pill">E-commerce</span>
                  </div>
                </div>
              </div>

              <!-- Card 5: Car Rental -->
              <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
                <a href="#" onclick="event.preventDefault(); openProject('carrental')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                  <img alt="Car Rental" class="w-full h-auto" src="{{ asset('assets/CarRental_Banner.png') }}">
                </a>
                <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                  <a href="#" onclick="event.preventDefault(); openProject('carrental')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">Car Rental</a>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A mobile car rental app where user can explore available cars, discover vehicle on the map, save favorite, and easily book or rent their preferred car.</p>
                  <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                    <span class="category-pill">Mobile Application Design</span>
                    <span class="category-pill">Car Rental / Rental Service</span>
                  </div>
                </div>
              </div>

              <!-- Card 6: Music Player -->
              <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
                <a href="#" onclick="event.preventDefault(); openProject('musicplayer')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                  <img alt="Music Player" class="w-full h-auto" src="{{ asset('assets/MusicPlayer_Banner.png') }}">
                </a>
                <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                  <a href="#" onclick="event.preventDefault(); openProject('musicplayer')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">Music Player</a>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A music streaming web app where user can discover artists and trending songs, search genres, play and queue music, save favorite, and view friend listening activities.</p>
                  <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                    <span class="category-pill">Web Design</span>
                    <span class="category-pill">Music Streaming</span>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ================================== -->
          <!-- ===== 2. DESIGN STACK ============ -->
          <!-- ================================== -->
          <section id="section-design-stack" class="w-full space-y-5">
            <div class="flex items-end justify-between gap-4">
              <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-gray-500 dark:text-gray-400">Toolkit</p>
                <h2 class="mt-1 text-xl sm:text-2xl font-light tracking-tight text-gray-900 dark:text-white">Design Stack</h2>
              </div>
              <div class="flex items-center gap-3 shrink-0 pb-1">
                <button id="stackToggle" onclick="toggleStackAnimation()" class="relative w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors rounded-lg border border-gray-200 dark:border-gray-700" title="Toggle layout">
                  <svg id="stackToggleIconGrid" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                  </svg>
                  <svg id="stackToggleIconStack" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                  </svg>
                </button>
                <button onclick="switchView('design-stack')" class="group inline-flex items-center gap-1.5 text-[11px] font-mono uppercase tracking-widest text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                  <span>View All</span>
                  <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 7h10v10"/><path d="M7 17L17 7"/>
                  </svg>
                </button>
              </div>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400">The tools I use to design and build my work.</p>

            <!-- Sliding Tracks (default) -->
            <div id="stackSliding" class="relative flex flex-col gap-5" style="mask-image: linear-gradient(to right, transparent, black 3rem, black calc(100% - 3rem), transparent);">
              <!-- Track 1: Tools (scroll left) -->
              <div class="overflow-hidden tech-container" id="techContainer1">
                <div class="tech-track gap-0" id="techTrack1">
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 38 57" fill="none"><path d="M19 28.5a9.5 9.5 0 1 1 19 0 9.5 9.5 0 0 1-19 0Z" fill="#1ABCFE"/><path d="M0 47.5A9.5 9.5 0 0 1 9.5 38H19v9.5a9.5 9.5 0 1 1-19 0Z" fill="#0ACF83"/><path d="M19 0v19h9.5a9.5 9.5 0 1 0 0-19H19Z" fill="#FF7262"/><path d="M0 9.5A9.5 9.5 0 0 0 9.5 19H19V0H9.5A9.5 9.5 0 0 0 0 9.5Z" fill="#F24E1E"/><path d="M0 28.5A9.5 9.5 0 0 0 9.5 38H19V19H9.5A9.5 9.5 0 0 0 0 28.5Z" fill="#A259FF"/></svg>
                    Figma
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg" role="img"><title>Affinity</title><path d="M12 0A12 12 90 000 12V180a12 12 180 0012 12H180a12 12 180 0012-12V12A12 12 90 00180 0H12Z" transform="scale(1.25)" fill="#A7F175"/><path d="M112.908,165.979c-10.413,0 -17.037,-6.317 -17.037,-14.329c0,-34.287 90.164,-41.151 90.164,-77.563c0,-22.213 -25.731,-31.787 -58.577,-31.787c-19.98,0 -42.571,3.698 -63.552,10.315l0,43.438c30.919,-22.52 65.272,-32.16 87.4,-32.16c13.914,0 23.355,3.882 23.355,10.838c0,28.658 -124.353,24.117 -124.353,86.042c0,23.736 16.321,37.335 39.283,37.335c34.732,0 61.003,-33.753 80.657,-74.897l3.982,1.43c-10.351,25.143 -31.771,52.712 -35.943,70.434l44.715,0l0,-91.685l-10.327,0c-14.99,32.574 -36.957,62.59 -59.767,62.59" fill="#0E1318" fill-rule="nonzero"/></svg>
                    Affinity
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" style="color:#10a37f;"><path d="M22.282 9.821a5.985 5.985 0 0 0-.516-4.91 6.046 6.046 0 0 0-6.51-2.9A6.065 6.065 0 0 0 4.981 4.18a5.985 5.985 0 0 0-3.998 2.9 6.046 6.046 0 0 0 .743 7.097 5.98 5.98 0 0 0 .51 4.911 6.051 6.051 0 0 0 6.515 2.9A5.985 5.985 0 0 0 13.26 24a6.056 6.056 0 0 0 5.772-4.206 5.99 5.99 0 0 0 3.997-2.9 6.056 6.056 0 0 0-.747-7.073zM13.26 22.43a4.476 4.476 0 0 1-2.876-1.04l.141-.081 4.779-2.758a.795.795 0 0 0 .392-.681v-6.737l2.02 1.168a.071.071 0 0 1 .038.052v5.583a4.504 4.504 0 0 1-4.494 4.494zM3.6 18.304a4.47 4.47 0 0 1-.535-3.014l.142.085 4.783 2.759a.771.771 0 0 0 .78 0l5.843-3.369v2.332a.08.08 0 0 1-.033.062L9.74 19.95a4.5 4.5 0 0 1-6.14-1.646zM2.34 7.896a4.485 4.485 0 0 1 2.366-1.973V11.6a.766.766 0 0 0 .388.676l5.815 3.355-2.02 1.168a.076.076 0 0 1-.071 0l-4.83-2.786A4.504 4.504 0 0 1 2.34 7.872zm16.597 3.855l-5.833-3.387L15.119 7.2a.076.076 0 0 1 .071 0l4.83 2.791a4.494 4.494 0 0 1-.676 8.105v-5.678a.79.79 0 0 0-.407-.667zm2.01-3.023l-.141-.085-4.774-2.782a.776.776 0 0 0-.785 0L9.409 9.23V6.897a.066.066 0 0 1 .028-.061l4.83-2.787a4.5 4.5 0 0 1 6.68 4.66zm-12.64 4.135l-2.02-1.164a.08.08 0 0 1-.038-.057V6.075a4.5 4.5 0 0 1 7.375-3.453l-.142.08L8.704 5.46a.795.795 0 0 0-.393.681zm1.097-2.365l2.602-1.5 2.607-1.5v2.999l-2.597 1.5-2.607-1.5z"/></svg>
                    ChatGPT
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 0C12 6.627 5.373 12 0 12c5.373 0 12 5.373 12 12 0-6.627 6.627-12 12-12-5.373 0-12-5.373-12-12Z" fill="url(#gem-grad)"/><defs><linearGradient id="gem-grad" x1="0" y1="0" x2="24" y2="24"><stop offset="0%" stop-color="#4285F4"/><stop offset="33%" stop-color="#9b72cb"/><stop offset="66%" stop-color="#d96570"/><stop offset="100%" stop-color="#f9ab00"/></linearGradient></defs></svg>
                    Gemini
                  </span>
                  <!-- Duplicate set for loop -->
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 38 57" fill="none"><path d="M19 28.5a9.5 9.5 0 1 1 19 0 9.5 9.5 0 0 1-19 0Z" fill="#1ABCFE"/><path d="M0 47.5A9.5 9.5 0 0 1 9.5 38H19v9.5a9.5 9.5 0 1 1-19 0Z" fill="#0ACF83"/><path d="M19 0v19h9.5a9.5 9.5 0 1 0 0-19H19Z" fill="#FF7262"/><path d="M0 9.5A9.5 9.5 0 0 0 9.5 19H19V0H9.5A9.5 9.5 0 0 0 0 9.5Z" fill="#F24E1E"/><path d="M0 28.5A9.5 9.5 0 0 0 9.5 38H19V19H9.5A9.5 9.5 0 0 0 0 28.5Z" fill="#A259FF"/></svg>
                    Figma
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg" role="img"><title>Affinity</title><path d="M12 0A12 12 90 000 12V180a12 12 180 0012 12H180a12 12 180 0012-12V12A12 12 90 00180 0H12Z" transform="scale(1.25)" fill="#A7F175"/><path d="M112.908,165.979c-10.413,0 -17.037,-6.317 -17.037,-14.329c0,-34.287 90.164,-41.151 90.164,-77.563c0,-22.213 -25.731,-31.787 -58.577,-31.787c-19.98,0 -42.571,3.698 -63.552,10.315l0,43.438c30.919,-22.52 65.272,-32.16 87.4,-32.16c13.914,0 23.355,3.882 23.355,10.838c0,28.658 -124.353,24.117 -124.353,86.042c0,23.736 16.321,37.335 39.283,37.335c34.732,0 61.003,-33.753 80.657,-74.897l3.982,1.43c-10.351,25.143 -31.771,52.712 -35.943,70.434l44.715,0l0,-91.685l-10.327,0c-14.99,32.574 -36.957,62.59 -59.767,62.59" fill="#0E1318" fill-rule="nonzero"/></svg>
                    Affinity
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" style="color:#10a37f;"><path d="M22.282 9.821a5.985 5.985 0 0 0-.516-4.91 6.046 6.046 0 0 0-6.51-2.9A6.065 6.065 0 0 0 4.981 4.18a5.985 5.985 0 0 0-3.998 2.9 6.046 6.046 0 0 0 .743 7.097 5.98 5.98 0 0 0 .51 4.911 6.051 6.051 0 0 0 6.515 2.9A5.985 5.985 0 0 0 13.26 24a6.056 6.056 0 0 0 5.772-4.206 5.99 5.99 0 0 0 3.997-2.9 6.056 6.056 0 0 0-.747-7.073zM13.26 22.43a4.476 4.476 0 0 1-2.876-1.04l.141-.081 4.779-2.758a.795.795 0 0 0 .392-.681v-6.737l2.02 1.168a.071.071 0 0 1 .038.052v5.583a4.504 4.504 0 0 1-4.494 4.494zM3.6 18.304a4.47 4.47 0 0 1-.535-3.014l.142.085 4.783 2.759a.771.771 0 0 0 .78 0l5.843-3.369v2.332a.08.08 0 0 1-.033.062L9.74 19.95a4.5 4.5 0 0 1-6.14-1.646zM2.34 7.896a4.485 4.485 0 0 1 2.366-1.973V11.6a.766.766 0 0 0 .388.676l5.815 3.355-2.02 1.168a.076.076 0 0 1-.071 0l-4.83-2.786A4.504 4.504 0 0 1 2.34 7.872zm16.597 3.855l-5.833-3.387L15.119 7.2a.076.076 0 0 1 .071 0l4.83 2.791a4.494 4.494 0 0 1-.676 8.105v-5.678a.79.79 0 0 0-.407-.667zm2.01-3.023l-.141-.085-4.774-2.782a.776.776 0 0 0-.785 0L9.409 9.23V6.897a.066.066 0 0 1 .028-.061l4.83-2.787a4.5 4.5 0 0 1 6.68 4.66zm-12.64 4.135l-2.02-1.164a.08.08 0 0 1-.038-.057V6.075a4.5 4.5 0 0 1 7.375-3.453l-.142.08L8.704 5.46a.795.795 0 0 0-.393.681zm1.097-2.365l2.602-1.5 2.607-1.5v2.999l-2.597 1.5-2.607-1.5z"/></svg>
                    ChatGPT
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 0C12 6.627 5.373 12 0 12c5.373 0 12 5.373 12 12 0-6.627 6.627-12 12-12-5.373 0-12-5.373-12-12Z" fill="url(#gem-grad2)"/><defs><linearGradient id="gem-grad2" x1="0" y1="0" x2="24" y2="24"><stop offset="0%" stop-color="#4285F4"/><stop offset="33%" stop-color="#9b72cb"/><stop offset="66%" stop-color="#d96570"/><stop offset="100%" stop-color="#f9ab00"/></linearGradient></defs></svg>
                    Gemini
                  </span>
                </div>
              </div>

              <!-- Track 2: Services (scroll right) -->
              <div class="overflow-hidden tech-container" id="techContainer2">
                <div class="tech-track gap-0" id="techTrack2">
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#3b82f6;"><path d="M6 2H18C19.1 2 20 2.9 20 4V20C20 21.1 19.1 22 18 22H6C4.9 22 4 21.1 4 20V4C4 2.9 4.9 2 6 2Z"/><path d="M10 18H14"/></svg>
                    Mobile Application Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#10b981;"><path d="M21 3H3C1.9 3 1 3.9 1 5V15C1 16.1 1.9 17 3 17H21C22.1 17 23 16.1 23 15V5C23 3.9 22.1 3 21 3Z"/><path d="M9 21H15M12 17V21"/></svg>
                    Web Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#14b8a6;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><rect x="6" y="12" width="5" height="6" rx="0.5"/><path d="M14 12h4M14 15h4M14 18h2"/></svg>
                    Landing Page Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;"><path d="M12 2L2 7L12 12L22 7L12 2Z"/><path d="M2 17L12 22L22 17"/><path d="M2 12L12 17L22 12"/></svg>
                    Design Systems
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#8b5cf6;"><path d="M3 22H21M5 21V8M10 21V4M15 21V12M20 21V6"/></svg>
                    SaaS Dashboard Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#ec4899;"><path d="M3 21L7.5 16.5"/><path d="M7.5 16.5L16.5 7.5C17.6 6.4 17.6 4.6 16.5 3.5C15.4 2.4 13.6 2.4 12.5 3.5L3.5 12.5L7.5 16.5Z"/><path d="M13 7L17 11"/></svg>
                    UI/UX Design
                  </span>
                  <!-- Duplicate set -->
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#3b82f6;"><path d="M6 2H18C19.1 2 20 2.9 20 4V20C20 21.1 19.1 22 18 22H6C4.9 22 4 21.1 4 20V4C4 2.9 4.9 2 6 2Z"/><path d="M10 18H14"/></svg>
                    Mobile Application Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#10b981;"><path d="M21 3H3C1.9 3 1 3.9 1 5V15C1 16.1 1.9 17 3 17H21C22.1 17 23 16.1 23 15V5C23 3.9 22.1 3 21 3Z"/><path d="M9 21H15M12 17V21"/></svg>
                    Web Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#14b8a6;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><rect x="6" y="12" width="5" height="6" rx="0.5"/><path d="M14 12h4M14 15h4M14 18h2"/></svg>
                    Landing Page Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;"><path d="M12 2L2 7L12 12L22 7L12 2Z"/><path d="M2 17L12 22L22 17"/><path d="M2 12L12 17L22 12"/></svg>
                    Design Systems
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#8b5cf6;"><path d="M3 22H21M5 21V8M10 21V4M15 21V12M20 21V6"/></svg>
                    SaaS Dashboard Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark shrink-0 inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 mr-2.5 sm:mr-3.5 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#ec4899;"><path d="M3 21L7.5 16.5"/><path d="M7.5 16.5L16.5 7.5C17.6 6.4 17.6 4.6 16.5 3.5C15.4 2.4 13.6 2.4 12.5 3.5L3.5 12.5L7.5 16.5Z"/><path d="M13 7L17 11"/></svg>
                    UI/UX Design
                  </span>
                </div>
              </div>
            </div>

            <!-- Organized Layout (toggled) -->
            <div id="stackOrganized" class="hidden space-y-6">
              <!-- AI / Research -->
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">AI / Research</p>
                <div class="flex flex-wrap gap-3">
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" style="color:#10a37f;"><path d="M22.282 9.821a5.985 5.985 0 0 0-.516-4.91 6.046 6.046 0 0 0-6.51-2.9A6.065 6.065 0 0 0 4.981 4.18a5.985 5.985 0 0 0-3.998 2.9 6.046 6.046 0 0 0 .743 7.097 5.98 5.98 0 0 0 .51 4.911 6.051 6.051 0 0 0 6.515 2.9A5.985 5.985 0 0 0 13.26 24a6.056 6.056 0 0 0 5.772-4.206 5.99 5.99 0 0 0 3.997-2.9 6.056 6.056 0 0 0-.747-7.073zM13.26 22.43a4.476 4.476 0 0 1-2.876-1.04l.141-.081 4.779-2.758a.795.795 0 0 0 .392-.681v-6.737l2.02 1.168a.071.071 0 0 1 .038.052v5.583a4.504 4.504 0 0 1-4.494 4.494zM3.6 18.304a4.47 4.47 0 0 1-.535-3.014l.142.085 4.783 2.759a.771.771 0 0 0 .78 0l5.843-3.369v2.332a.08.08 0 0 1-.033.062L9.74 19.95a4.5 4.5 0 0 1-6.14-1.646zM2.34 7.896a4.485 4.485 0 0 1 2.366-1.973V11.6a.766.766 0 0 0 .388.676l5.815 3.355-2.02 1.168a.076.076 0 0 1-.071 0l-4.83-2.786A4.504 4.504 0 0 1 2.34 7.872zm16.597 3.855l-5.833-3.387L15.119 7.2a.076.076 0 0 1 .071 0l4.83 2.791a4.494 4.494 0 0 1-.676 8.105v-5.678a.79.79 0 0 0-.407-.667zm2.01-3.023l-.141-.085-4.774-2.782a.776.776 0 0 0-.785 0L9.409 9.23V6.897a.066.066 0 0 1 .028-.061l4.83-2.787a4.5 4.5 0 0 1 6.68 4.66zm-12.64 4.135l-2.02-1.164a.08.08 0 0 1-.038-.057V6.075a4.5 4.5 0 0 1 7.375-3.453l-.142.08L8.704 5.46a.795.795 0 0 0-.393.681zm1.097-2.365l2.602-1.5 2.607-1.5v2.999l-2.597 1.5-2.607-1.5z"/></svg>
                    ChatGPT
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 0C12 6.627 5.373 12 0 12c5.373 0 12 5.373 12 12 0-6.627 6.627-12 12-12-5.373 0-12-5.373-12-12Z" fill="url(#gem-grad3)"/><defs><linearGradient id="gem-grad3" x1="0" y1="0" x2="24" y2="24"><stop offset="0%" stop-color="#4285F4"/><stop offset="33%" stop-color="#9b72cb"/><stop offset="66%" stop-color="#d96570"/><stop offset="100%" stop-color="#f9ab00"/></linearGradient></defs></svg>
                    Gemini
                  </span>
                </div>
              </div>
              <!-- Design / UI/UX -->
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Design / UI/UX</p>
                <div class="flex flex-wrap gap-3">
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 38 57" fill="none"><path d="M19 28.5a9.5 9.5 0 1 1 19 0 9.5 9.5 0 0 1-19 0Z" fill="#1ABCFE"/><path d="M0 47.5A9.5 9.5 0 0 1 9.5 38H19v9.5a9.5 9.5 0 1 1-19 0Z" fill="#0ACF83"/><path d="M19 0v19h9.5a9.5 9.5 0 1 0 0-19H19Z" fill="#FF7262"/><path d="M0 9.5A9.5 9.5 0 0 0 9.5 19H19V0H9.5A9.5 9.5 0 0 0 0 9.5Z" fill="#F24E1E"/><path d="M0 28.5A9.5 9.5 0 0 0 9.5 38H19V19H9.5A9.5 9.5 0 0 0 0 28.5Z" fill="#A259FF"/></svg>
                    Figma
                  </span>
                </div>
              </div>
              <!-- Image Editing -->
              <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Image Editing</p>
                <div class="flex flex-wrap gap-3">
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg" role="img"><title>Affinity</title><path d="M12 0A12 12 90 000 12V180a12 12 180 0012 12H180a12 12 180 0012-12V12A12 12 90 00180 0H12Z" transform="scale(1.25)" fill="#A7F175"/><path d="M112.908,165.979c-10.413,0 -17.037,-6.317 -17.037,-14.329c0,-34.287 90.164,-41.151 90.164,-77.563c0,-22.213 -25.731,-31.787 -58.577,-31.787c-19.98,0 -42.571,3.698 -63.552,10.315l0,43.438c30.919,-22.52 65.272,-32.16 87.4,-32.16c13.914,0 23.355,3.882 23.355,10.838c0,28.658 -124.353,24.117 -124.353,86.042c0,23.736 16.321,37.335 39.283,37.335c34.732,0 61.003,-33.753 80.657,-74.897l3.982,1.43c-10.351,25.143 -31.771,52.712 -35.943,70.434l44.715,0l0,-91.685l-10.327,0c-14.99,32.574 -36.957,62.59 -59.767,62.59" fill="#0E1318" fill-rule="nonzero"/></svg>
                    Affinity
                  </span>
                </div>
              </div>
              <!-- Services -->
              <div class="ds-category" data-category="services">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">Services</p>
                <div class="flex flex-wrap gap-3">
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#3b82f6;"><path d="M6 2H18C19.1 2 20 2.9 20 4V20C20 21.1 19.1 22 18 22H6C4.9 22 4 21.1 4 20V4C4 2.9 4.9 2 6 2Z"/><path d="M10 18H14"/></svg>
                    Mobile Application Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#10b981;"><path d="M21 3H3C1.9 3 1 3.9 1 5V15C1 16.1 1.9 17 3 17H21C22.1 17 23 16.1 23 15V5C23 3.9 22.1 3 21 3Z"/><path d="M9 21H15M12 17V21"/></svg>
                    Web Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#14b8a6;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><rect x="6" y="12" width="5" height="6" rx="0.5"/><path d="M14 12h4M14 15h4M14 18h2"/></svg>
                    Landing Page Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;"><path d="M12 2L2 7L12 12L22 7L12 2Z"/><path d="M2 17L12 22L22 17"/><path d="M2 12L12 17L22 12"/></svg>
                    Design Systems
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#8b5cf6;"><path d="M3 22H21M5 21V8M10 21V4M15 21V12M20 21V6"/></svg>
                    SaaS Dashboard Design
                  </span>
                  <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#ec4899;"><path d="M3 21L7.5 16.5"/><path d="M7.5 16.5L16.5 7.5C17.6 6.4 17.6 4.6 16.5 3.5C15.4 2.4 13.6 2.4 12.5 3.5L3.5 12.5L7.5 16.5Z"/><path d="M13 7L17 11"/></svg>
                    UI/UX Design
                  </span>
                </div>
              </div>
            </div>
          </section>

          <!-- ================================== -->
          <!-- ===== 3. EXPERIENCE ============== -->
          <!-- ================================== -->
          <section id="section-experience" class="hidden w-full space-y-5">
            <p class="text-2xl sm:text-3xl font-light tracking-tight text-gray-900 dark:text-white">Experience</p>

            <div class="exp-timeline">
              <!-- Entry 1: Freelance UI/UX Designer -->
              <div class="exp-item is-highlighted">
                <div class="exp-dot"></div>
                <div class="exp-line"></div>
                <p class="exp-date text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">Present</p>
                <div class="exp-content space-y-2">
                  <div class="flex items-baseline gap-2 flex-wrap">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white leading-tight">Freelance UI/UX Designer</h3>
                  </div>
                  <ul class="exp-bullets pt-1">
                    <li>Designing and prototyping user interfaces and user experiences for clients and personal projects, including mobile applications, websites, and other services.</li>
                  </ul>
                </div>
              </div>

              <!-- Entry 2: Internship - City Health Office 1 -->
              <div class="exp-item is-highlighted">
                <div class="exp-dot"></div>
                <div class="exp-line"></div>
                <p class="exp-date text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">2026</p>
                <div class="exp-content space-y-2">
                  <div class="flex items-baseline gap-2 flex-wrap">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white leading-tight">Internship</h3>
                    <span class="text-gray-300 dark:text-gray-600 select-none">·</span>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300"><span class="text-gray-400 dark:text-gray-500">Company Name:</span> City Health Office 1</p>
                  </div>
                  <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 22C12 22 18 16 18 10C18 6.69 15.31 4 12 4C8.69 4 6 6.69 6 10C6 16 12 22 12 22Z"/>
                      <path d="M14.5 10C14.5 11.38 13.38 12.5 12 12.5C10.62 12.5 9.5 11.38 9.5 10C9.5 8.62 10.62 7.5 12 7.5C13.38 7.5 14.5 8.62 14.5 10Z"/>
                    </svg>
                    <span>Urdaneta City, Pangasinan</span>
                  </p>
                  <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="category-pill">Web</span>
                  </div>
                  <h4 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
                    <a href="https://chourdaneta-queuing.site/" target="_blank" rel="noopener noreferrer" class="hover:underline inline-flex items-center gap-1">
                      Queuing Management System
                      <svg class="w-3 h-3 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg>
                    </a>
                  </h4>
                  <ul class="exp-bullets pt-1">
                    <li>Developed a queuing management system for managing patients in the waiting area, doctor queues, and encoder queues.</li>
                  </ul>
                </div>
              </div>

              <!-- Entry 3: Programmer and UI/UX Designer - Capstone Project -->
              <div class="exp-item is-highlighted">
                <div class="exp-dot"></div>
                <div class="exp-line"></div>
                <p class="exp-date text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">2025 – 2026</p>
                <div class="exp-content space-y-2">
                  <div class="flex items-baseline gap-2 flex-wrap">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white leading-tight">Capstone Project</h3>
                    <span class="text-gray-300 dark:text-gray-600 select-none">·</span>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">UI/UX Designer / Programmer</p>
                  </div>
                  <div class="flex flex-wrap gap-1.5 pt-1">
                    <span class="category-pill">Web</span>
                    <span class="category-pill">Mobile Application</span>
                  </div>
                  <h4 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white leading-snug">City Health Connect: A multi-platform health services management system for the City Health Office of Urdaneta City</h4>
                  <ul class="exp-bullets pt-1">
                    <li>Developed the system through web and mobile applications for managing patient records, queuing, and other health services.</li>
                  </ul>
                </div>
              </div>
            </div>
          </section>

          <!-- ================================== -->
          <!-- ===== 4. EDUCATION =============== -->
          <!-- ================================== -->
          <section id="section-education" class="w-full space-y-6">
            <p class="text-2xl sm:text-3xl font-light tracking-tight text-gray-900 dark:text-white">Education</p>

            <!-- College Entry -->
            <div class="sm:grid sm:grid-cols-[160px_1fr] sm:gap-6">
              <p class="text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap mb-1 sm:mb-0 sm:pt-1">2022 – 2026</p>
              <div class="flex items-start gap-4">
                <img src="{{ asset('assets/uculogo.png') }}" alt="UCU Logo" class="w-12 h-12 sm:w-14 sm:h-14 object-contain shrink-0 rounded-lg">
                <div class="min-w-0">
                  <span class="text-[10px] font-bold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">College</span>
                  <h3 class="mt-1 text-base sm:text-[17px] font-semibold text-gray-900 dark:text-white leading-tight">Bachelor of Science in Information Technology</h3>
                  <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-300">Urdaneta City University</p>
                  <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 22C12 22 18 16 18 10C18 6.69 15.31 4 12 4C8.69 4 6 6.69 6 10C6 16 12 22 12 22Z"/>
                      <path d="M14.5 10C14.5 11.38 13.38 12.5 12 12.5C10.62 12.5 9.5 11.38 9.5 10C9.5 8.62 10.62 7.5 12 7.5C13.38 7.5 14.5 8.62 14.5 10Z"/>
                    </svg>
                    <span>Urdaneta City, Pangasinan, Philippines</span>
                  </p>
                </div>
              </div>
            </div>

            <!-- Senior High School Entry -->
            <div class="sm:grid sm:grid-cols-[160px_1fr] sm:gap-6">
              <p class="text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap mb-1 sm:mb-0 sm:pt-1">2020 – 2022</p>
              <div class="flex items-start gap-4">
                <img src="{{ asset('assets/mpjcl.png') }}" alt="MPJCL Logo" class="w-12 h-12 sm:w-14 sm:h-14 object-contain shrink-0 rounded-lg">
                <div class="min-w-0">
                  <span class="text-[10px] font-bold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">Senior High School</span>
                  <h3 class="mt-1 text-base sm:text-[17px] font-semibold text-gray-900 dark:text-white leading-tight">General Academic Strand (GAS)</h3>
                  <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 22C12 22 18 16 18 10C18 6.69 15.31 4 12 4C8.69 4 6 6.69 6 10C6 16 12 22 12 22Z"/>
                      <path d="M14.5 10C14.5 11.38 13.38 12.5 12 12.5C10.62 12.5 9.5 11.38 9.5 10C9.5 8.62 10.62 7.5 12 7.5C13.38 7.5 14.5 8.62 14.5 10Z"/>
                    </svg>
                    <span>Guiset Sur, San Manuel, Pangasinan, Philippines</span>
                  </p>
                </div>
              </div>
            </div>
          </section>

          <!-- ================================== -->
          <!-- ===== 5. BEYOND THE CANVAS ======= -->
          <!-- ================================== -->
          <section id="section-outside-ide" class="w-full space-y-5">
            <p class="text-2xl sm:text-3xl font-light tracking-tight text-gray-900 dark:text-white">Outside the Interface</p>

            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
              <div class="space-y-4">
                <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base leading-relaxed max-w-xl">
                  When I'm not designing, I'm usually playing games, exploring new places, or learning something new. It keeps me inspired, curious, and creative.
                </p>
                <div class="flex flex-wrap gap-2.5">
                  <span class="inline-flex items-center rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-ink px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-200">Playing Games</span>
                  <span class="inline-flex items-center rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-ink px-3 py-1 text-xs font-medium text-gray-700 dark:text-gray-200">Going Out / Exploring</span>
                </div>
              </div>
              <div class="flex justify-center lg:justify-end">
                <div style="width: 240px; height: 320px;">
                  <div class="stack-container" id="imageStack">
                    <div class="stack-card" data-card-index="0">
                      <img src="{{ asset('assets/image1.png') }}" alt="Outside the Canvas 1" draggable="false">
                    </div>
                    <div class="stack-card" data-card-index="1">
                      <img src="{{ asset('assets/image2.png') }}" alt="Outside the Canvas 2" draggable="false">
                    </div>
                    <div class="stack-card" data-card-index="2">
                      <img src="{{ asset('assets/image3.png') }}" alt="Outside the Canvas 3" draggable="false">
                    </div>
                    <div class="stack-card" data-card-index="3">
                      <img src="{{ asset('assets/image4.png') }}" alt="Outside the Canvas 4" draggable="false">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- ================================== -->
          <!-- ===== CONTACT SECTION ============ -->
          <!-- ================================== -->
          <section id="section-contact" class="w-full space-y-5">
            <p class="text-2xl sm:text-3xl font-light tracking-tight text-gray-900 dark:text-white">Let's work together.</p>

            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
              <div class="space-y-4">
                <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base leading-relaxed max-w-xl">
                  Have a project in mind, a question, or just want to connect? Reach out through any of my social platforms — Instagram, Facebook, LinkedIn, or send me an email and I'll get back to you.
                </p>
              </div>
              <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <a href="mailto:karljustinermembrere11272003@gmail.com" class="group flex items-center justify-between gap-4 rounded-2xl border border-gray-200/80 bg-white/90 px-4 py-4 shadow-sm transition-all hover:-translate-y-1 hover:border-gray-300 hover:bg-white hover:shadow-md dark:border-slate-700/80 dark:bg-slate-900/80 dark:hover:border-gray-600 dark:hover:bg-slate-800/90">
                  <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-ink dark:bg-slate-800 dark:text-white">
                      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4H19C20.1 4 21 4.9 21 6V18C21 19.1 20.1 20 19 20H5C3.9 20 3 19.1 3 18V6C3 4.9 3.9 4 5 4Z"/><path d="M3 7L12 13L21 7"/></svg>
                    </div>
                    <div class="min-w-0">
                      <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Email</span>
                      <p class="truncate text-sm font-normal text-gray-900 dark:text-white">karljustinermembrere11272003@gmail.com</p>
                    </div>
                  </div>
                  <svg class="h-4 w-4 shrink-0 text-gray-300 group-hover:translate-x-1 group-hover:text-gray-700 dark:text-slate-500 dark:group-hover:text-gray-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                </a>

                <button onclick="openModal()" class="group flex items-center justify-between gap-4 rounded-2xl border border-gray-200/80 bg-white/90 px-4 py-4 shadow-sm transition-all hover:-translate-y-1 hover:border-gray-300 hover:bg-white hover:shadow-md dark:border-slate-700/80 dark:bg-slate-900/80 dark:hover:border-gray-600 dark:hover:bg-slate-800/90 text-left w-full">
                  <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-ink dark:bg-slate-800 dark:text-white">
                      <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    </div>
                    <div class="min-w-0">
                      <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Contact</span>
                      <p class="truncate text-sm font-normal text-gray-900 dark:text-white">Send a Message</p>
                    </div>
                  </div>
                  <svg class="h-4 w-4 shrink-0 text-gray-300 group-hover:translate-x-1 group-hover:text-gray-700 dark:text-slate-500 dark:group-hover:text-gray-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                </button>
              </div>
            </div>
          </section>
        </main>
      </div>

      <!-- ============================================== -->
      <!-- =========== EXPERIENCE VIEW ================= -->
      <!-- ============================================== -->
      <div id="view-experience" class="view">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-10 sm:gap-12 px-4 sm:px-6 pt-8 sm:pt-12 pb-10 sm:pb-16">

          <button onclick="switchView('home')" class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 18l-6-6 6-6"/>
            </svg>
            <span>Back to Home</span>
          </button>

          <div class="view-header">
            <h1 class="text-3xl sm:text-4xl font-light tracking-tight text-gray-900 dark:text-white">Experience</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Where I Learned and What I Built</p>
          </div>

          <div class="exp-timeline">
            <!-- Entry 1: Freelance UI/UX Designer -->
            <div class="exp-item is-highlighted">
              <div class="exp-dot"></div>
              <div class="exp-line"></div>
              <p class="exp-date text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">Present</p>
              <div class="exp-content space-y-2">
                <div class="flex items-baseline gap-2 flex-wrap">
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white leading-tight">Freelance UI/UX Designer</h3>
                </div>
                <ul class="exp-bullets pt-1">
                  <li>Designing and prototyping user interfaces and user experiences for clients and personal projects, including mobile applications, websites, and other services.</li>
                </ul>
              </div>
            </div>

            <!-- Entry 2: Internship - City Health Office 1 -->
            <div class="exp-item is-highlighted">
              <div class="exp-dot"></div>
              <div class="exp-line"></div>
              <p class="exp-date text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">2026</p>
              <div class="exp-content space-y-2">
                <div class="flex items-baseline gap-2 flex-wrap">
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white leading-tight">Internship</h3>
                  <span class="text-gray-300 dark:text-gray-600 select-none">·</span>
                  <p class="text-sm font-medium text-gray-700 dark:text-gray-300"><span class="text-gray-400 dark:text-gray-500">Company Name:</span> City Health Office 1</p>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1">
                  <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22C12 22 18 16 18 10C18 6.69 15.31 4 12 4C8.69 4 6 6.69 6 10C6 16 12 22 12 22Z"/>
                    <path d="M14.5 10C14.5 11.38 13.38 12.5 12 12.5C10.62 12.5 9.5 11.38 9.5 10C9.5 8.62 10.62 7.5 12 7.5C13.38 7.5 14.5 8.62 14.5 10Z"/>
                  </svg>
                  <span>Urdaneta City, Pangasinan</span>
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1">
                  <span class="category-pill">Web</span>
                </div>
                <h4 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
                  <a href="https://chourdaneta-queuing.site/" target="_blank" rel="noopener noreferrer" class="hover:underline inline-flex items-center gap-1">
                    Queuing Management System
                    <svg class="w-3 h-3 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17L17 7M7 7h10v10"/></svg>
                  </a>
                </h4>
                <ul class="exp-bullets pt-1">
                  <li>Developed a queuing management system for managing patients in the waiting area, doctor queues, and encoder queues.</li>
                </ul>
              </div>
            </div>

            <!-- Entry 3: Programmer and UI/UX Designer - Capstone Project -->
            <div class="exp-item is-highlighted">
              <div class="exp-dot"></div>
              <div class="exp-line"></div>
              <p class="exp-date text-xs font-medium text-gray-400 dark:text-gray-500 whitespace-nowrap">2025 – 2026</p>
              <div class="exp-content space-y-2">
                <div class="flex items-baseline gap-2 flex-wrap">
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white leading-tight">Capstone Project</h3>
                  <span class="text-gray-300 dark:text-gray-600 select-none">·</span>
                  <p class="text-sm font-medium text-gray-700 dark:text-gray-300">UI/UX Designer / Programmer</p>
                </div>
                <div class="flex flex-wrap gap-1.5 pt-1">
                  <span class="category-pill">Web</span>
                  <span class="category-pill">Mobile Application</span>
                </div>
                <h4 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white leading-snug">City Health Connect: A multi-platform health services management system for the City Health Office of Urdaneta City</h4>
                <ul class="exp-bullets pt-1">
                  <li>Developed the system through web and mobile applications for managing patient records, queuing, and other health service processes.</li>
                </ul>
              </div>
            </div>
          </div>
        </main>
      </div>

      <!-- ============================================== -->
      <!-- ================ FAQ VIEW =================== -->
      <!-- ============================================== -->
      <div id="view-faq" class="view">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-10 sm:gap-12 px-4 sm:px-6 pt-8 sm:pt-12 pb-10 sm:pb-16">

          <button onclick="switchView('home')" class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 18l-6-6 6-6"/>
            </svg>
            <span>Back to Home</span>
          </button>

          <section id="section-faq" class="w-full space-y-5">
            <p class="text-3xl sm:text-4xl font-light tracking-tight text-gray-900 dark:text-white">FAQ</p>

            <div class="faq-list">
              <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-answer-1">
                  <span>What services do you offer?</span>
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq-answer-1" class="faq-answer" aria-hidden="true"><p>I offer UI/UX design, web design, mobile design, landing page design, design system, and SaaS dashboard design.</p></div>
              </div>
              <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-answer-2">
                  <span>How does your design process work?</span>
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq-answer-2" class="faq-answer" aria-hidden="true"><p>I start by understanding the problem, the target users, and the client’s goals. Then I plan the structure, create the user flow, design the interface, and refine it based on feedback.</p></div>
              </div>
              <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-answer-3">
                  <span>How long does a project usually take?</span>
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq-answer-3" class="faq-answer" aria-hidden="true"><p>Small projects usually take around 2 to 3 weeks. Larger websites, systems, or more complex projects may take around 4 to 5 weeks, depending on the project scope and requirements.</p></div>
              </div>
              <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-answer-4">
                  <span>What do I need to provide before starting a project?</span>
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq-answer-4" class="faq-answer" aria-hidden="true"><p>You need to provide your business or project details, goals, preferred style, colors, ideas, content, system flow, and any references or requirements you want included.</p></div>
              </div>
              <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-answer-5">
                  <span>Do you offer revisions?</span>
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq-answer-5" class="faq-answer" aria-hidden="true"><p>Yes. I usually offer 2 to 3 rounds of revisions depending on the project scope and the agreed requirements.</p></div>
              </div>
              <div class="faq-item">
                <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-answer-6">
                  <span>How do I get started?</span>
                  <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq-answer-6" class="faq-answer" aria-hidden="true"><p>You can reach out through my social accounts or contact form and send the details of your project, including what you need, your goals, and any relevant references.</p></div>
              </div>
            </div>
          </section>
        </main>
      </div>

      <!-- ============================================== -->
      <!-- ============ PROJECTS VIEW ================== -->
      <!-- ============================================== -->
      <div id="view-projects" class="view">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-10 sm:gap-12 px-4 sm:px-6 pt-8 sm:pt-12 pb-10 sm:pb-16">

          <button onclick="switchView('home')" class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 18l-6-6 6-6"/>
            </svg>
            <span>Back to Home</span>
          </button>

          <div class="view-header">
            <h1 class="text-3xl sm:text-4xl font-light tracking-tight text-gray-900 dark:text-white">Projects</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A selection of interfaces, dashboards, and experiences I've designed.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
            <!-- Card 1: NOVA AI -->
            <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
              <a href="#" onclick="event.preventDefault(); openProject('novaai')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                <img alt="NOVA AI" class="w-full h-auto" src="{{ asset('assets/NOVAAI_Banner.png') }}">
              </a>
              <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                <a href="#" onclick="event.preventDefault(); openProject('novaai')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">NOVA AI</a>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">An AI-powered mobile chat app where users can ask questions, get instant responses, solve problems, generate content, and explore ideas through intelligent AI conversations.</p>
                <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                  <span class="category-pill">Mobile Application Design</span>
                  <span class="category-pill">AI / Chat Assistant</span>
                </div>
              </div>
            </div>

            <!-- Card 2: FLOWZA -->
            <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
              <a href="#" onclick="event.preventDefault(); openProject('flowza')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                <img alt="FLOWZA" class="w-full h-auto" src="{{ asset('assets/FLOWZA_Banner.png') }}">
              </a>
              <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                <a href="#" onclick="event.preventDefault(); openProject('flowza')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">FLOWZA</a>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A web-based dashboard platform that helps teams track active projects, monitor progress, manage tasks and deadlines, and stay updated on team activity.</p>
                <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                  <span class="category-pill">Web Design</span>
                  <span class="category-pill">System Dashboard Design</span>
                </div>
              </div>
            </div>

            <!-- Card 3: Planty -->
            <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
              <a href="#" onclick="event.preventDefault(); openProject('planty')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                <img alt="Planty" class="w-full h-auto" src="{{ asset('assets/Planty_Banner.png') }}">
              </a>
              <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                <a href="#" onclick="event.preventDefault(); openProject('planty')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">Planty</a>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A mobile plant e-commerce app where users can browse and purchase plant, manage their cart, and check out using card payment or cash on delivery.</p>
                <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                  <span class="category-pill">Mobile Application Design</span>
                  <span class="category-pill">E-commerce</span>
                </div>
              </div>
            </div>

            <!-- Card 4: NORVA -->
            <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
              <a href="#" onclick="event.preventDefault(); openProject('norva')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                <img alt="NORVA" class="w-full h-auto" src="{{ asset('assets/Norva_Banner.png') }}">
              </a>
              <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                <a href="#" onclick="event.preventDefault(); openProject('norva')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">NORVA</a>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A modern web e-commerce website where users can browse and shop for jackets and pants, view product details, add items to their cart, and manage their selected products.</p>
                <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                  <span class="category-pill">Web Design</span>
                  <span class="category-pill">E-commerce</span>
                </div>
              </div>
            </div>

            <!-- Card 5: Car Rental -->
            <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
              <a href="#" onclick="event.preventDefault(); openProject('carrental')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                <img alt="Car Rental" class="w-full h-auto" src="{{ asset('assets/CarRental_Banner.png') }}">
              </a>
              <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                <a href="#" onclick="event.preventDefault(); openProject('carrental')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">Car Rental</a>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A mobile car rental app where user can explore available cars, discover vehicle on the map, save favorite, and easily book or rent their preferred car.</p>
                <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                  <span class="category-pill">Mobile Application Design</span>
                  <span class="category-pill">Car Rental / Rental Service</span>
                </div>
              </div>
            </div>

            <!-- Card 6: Music Player -->
            <div class="project-card flex h-full flex-col gap-2 bg-white dark:bg-ink border border-gray-300 dark:border-gray-700 p-2 rounded-xl w-full overflow-hidden">
              <a href="#" onclick="event.preventDefault(); openProject('musicplayer')" class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 relative z-20 bg-white dark:bg-ink">
                <img alt="Music Player" class="w-full h-auto" src="{{ asset('assets/MusicPlayer_Banner.png') }}">
              </a>
              <div class="flex flex-1 flex-col px-2 mt-3 relative z-20">
                <a href="#" onclick="event.preventDefault(); openProject('musicplayer')" class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white leading-none">Music Player</a>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">A music streaming web app where user can discover artists and trending songs, search genres, play and queue music, save favorite, and view friend listening activities.</p>
                <div class="flex flex-wrap gap-1.5 mt-auto pt-3">
                  <span class="category-pill">Web Design</span>
                  <span class="category-pill">Music Streaming</span>
                </div>
              </div>
            </div>
          </div>

        </main>
      </div>

      <!-- ============================================== -->
      <!-- ======== PROJECT DETAIL VIEW ================= -->
      <!-- ============================================== -->
      <div id="view-project-detail" class="view">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-10 sm:gap-12 px-4 sm:px-6 pt-8 sm:pt-12 pb-10 sm:pb-16">

          <button onclick="closeProject()" class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 18l-6-6 6-6"/>
            </svg>
            <span>Back</span>
          </button>

          <div class="view-header">
            <h1 id="project-detail-title" class="text-3xl sm:text-4xl font-light tracking-tight text-gray-900 dark:text-white"></h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A closer look at the design process and details.</p>
          </div>

          <div class="project-detail-image-wrap rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
            <img id="project-detail-image" src="" alt="" class="w-full h-auto">
          </div>

          <div class="project-detail-info space-y-6">
            <p id="project-detail-description" class="text-gray-500 dark:text-gray-400 text-sm sm:text-base leading-relaxed max-w-xl"></p>
            <div id="project-detail-categories" class="flex flex-wrap gap-1.5"></div>
          </div>

          <!-- Project Detail Gallery (mobile grid + web carousel) -->
          <div id="project-detail-gallery" class="space-y-10"></div>
        </main>
      </div>

      <!-- ============================================== -->
      <!-- ============ GALLERY VIEW =================== -->
      <!-- ============================================== -->
      <div id="view-gallery" class="view">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-10 sm:gap-12 px-4 sm:px-6 pt-8 sm:pt-12 pb-10 sm:pb-16">

          <button onclick="switchView('home')" class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 18l-6-6 6-6"/>
            </svg>
            <span>Back to Home</span>
          </button>

          <div>
            <h1 class="text-3xl sm:text-4xl font-light tracking-tight text-gray-900 dark:text-white">Gallery</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A collection of visuals, explorations, and behind-the-scenes moments.</p>
          </div>

          <div class="columns-2 sm:columns-3 gap-3 sm:gap-4">
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery1.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery2.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery3.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery4.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery5.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery6.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
            <div class="gallery-item group relative mb-3 sm:mb-4 break-inside-avoid rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-surface-dark shadow-sm">
              <img src="{{ asset('assets/gallery7.png') }}" alt="Gallery Pictures" class="block w-full h-auto" loading="lazy">
            </div>
          </div>
        </main>
      </div>

      <!-- ============================================== -->
      <!-- ======== DESIGN STACK VIEW =================== -->
      <!-- ============================================== -->
      <div id="view-design-stack" class="view">
        <main class="mx-auto flex w-full max-w-3xl flex-col gap-10 sm:gap-12 px-4 sm:px-6 pt-8 sm:pt-12 pb-10 sm:pb-16">

          <button onclick="switchView('home')" class="group inline-flex w-fit items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 18l-6-6 6-6"/>
            </svg>
            <span>Back to Home</span>
          </button>

          <div class="view-header">
            <h1 class="text-3xl sm:text-4xl font-light tracking-tight text-gray-900 dark:text-white">Design Stack</h1>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">The tools I use to design and build my work.</p>
          </div>

          <!-- Filters -->
          <div class="flex flex-wrap gap-2">
            <button class="filter-btn active-filter" data-filter="all" onclick="filterDesignStack('all')">All</button>
            <button class="filter-btn" data-filter="ai" onclick="filterDesignStack('ai')">AI / Research</button>
            <button class="filter-btn" data-filter="design" onclick="filterDesignStack('design')">Design / UI/UX</button>
            <button class="filter-btn" data-filter="image" onclick="filterDesignStack('image')">Image Editing</button>
            <button class="filter-btn" data-filter="services" onclick="filterDesignStack('services')">Services</button>
          </div>

          <!-- Categories -->
          <div class="space-y-8">

            <!-- AI / Research -->
            <div class="ds-category" data-category="ai">
              <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">AI / Research</p>
              <div class="flex flex-wrap gap-3">
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" style="color:#10a37f;"><path d="M22.282 9.821a5.985 5.985 0 0 0-.516-4.91 6.046 6.046 0 0 0-6.51-2.9A6.065 6.065 0 0 0 4.981 4.18a5.985 5.985 0 0 0-3.998 2.9 6.046 6.046 0 0 0 .743 7.097 5.98 5.98 0 0 0 .51 4.911 6.051 6.051 0 0 0 6.515 2.9A5.985 5.985 0 0 0 13.26 24a6.056 6.056 0 0 0 5.772-4.206 5.99 5.99 0 0 0 3.997-2.9 6.056 6.056 0 0 0-.747-7.073zM13.26 22.43a4.476 4.476 0 0 1-2.876-1.04l.141-.081 4.779-2.758a.795.795 0 0 0 .392-.681v-6.737l2.02 1.168a.071.071 0 0 1 .038.052v5.583a4.504 4.504 0 0 1-4.494 4.494zM3.6 18.304a4.47 4.47 0 0 1-.535-3.014l.142.085 4.783 2.759a.771.771 0 0 0 .78 0l5.843-3.369v2.332a.08.08 0 0 1-.033.062L9.74 19.95a4.5 4.5 0 0 1-6.14-1.646zM2.34 7.896a4.485 4.485 0 0 1 2.366-1.973V11.6a.766.766 0 0 0 .388.676l5.815 3.355-2.02 1.168a.076.076 0 0 1-.071 0l-4.83-2.786A4.504 4.504 0 0 1 2.34 7.872zm16.597 3.855l-5.833-3.387L15.119 7.2a.076.076 0 0 1 .071 0l4.83 2.791a4.494 4.494 0 0 1-.676 8.105v-5.678a.79.79 0 0 0-.407-.667zm2.01-3.023l-.141-.085-4.774-2.782a.776.776 0 0 0-.785 0L9.409 9.23V6.897a.066.066 0 0 1 .028-.061l4.83-2.787a4.5 4.5 0 0 1 6.68 4.66zm-12.64 4.135l-2.02-1.164a.08.08 0 0 1-.038-.057V6.075a4.5 4.5 0 0 1 7.375-3.453l-.142.08L8.704 5.46a.795.795 0 0 0-.393.681zm1.097-2.365l2.602-1.5 2.607-1.5v2.999l-2.597 1.5-2.607-1.5z"/></svg>
                  ChatGPT
                </span>
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M12 0C12 6.627 5.373 12 0 12c5.373 0 12 5.373 12 12 0-6.627 6.627-12 12-12-5.373 0-12-5.373-12-12Z" fill="url(#ds-gem)"/><defs><linearGradient id="ds-gem" x1="0" y1="0" x2="24" y2="24"><stop offset="0%" stop-color="#4285F4"/><stop offset="33%" stop-color="#9b72cb"/><stop offset="66%" stop-color="#d96570"/><stop offset="100%" stop-color="#f9ab00"/></linearGradient></defs></svg>
                  Gemini
                </span>
              </div>
            </div>

            <!-- Design / UI/UX -->
            <div class="ds-category" data-category="design">
              <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">Design / UI/UX</p>
              <div class="flex flex-wrap gap-3">
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 38 57" fill="none"><path d="M19 28.5a9.5 9.5 0 1 1 19 0 9.5 9.5 0 0 1-19 0Z" fill="#1ABCFE"/><path d="M0 47.5A9.5 9.5 0 0 1 9.5 38H19v9.5a9.5 9.5 0 1 1-19 0Z" fill="#0ACF83"/><path d="M19 0v19h9.5a9.5 9.5 0 1 0 0-19H19Z" fill="#FF7262"/><path d="M0 9.5A9.5 9.5 0 0 0 9.5 19H19V0H9.5A9.5 9.5 0 0 0 0 9.5Z" fill="#F24E1E"/><path d="M0 28.5A9.5 9.5 0 0 0 9.5 38H19V19H9.5A9.5 9.5 0 0 0 0 28.5Z" fill="#A259FF"/></svg>
                  Figma
                </span>
              </div>
            </div>

            <!-- Image Editing -->
            <div class="ds-category" data-category="image">
              <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">Image Editing</p>
              <div class="flex flex-wrap gap-3">
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg" role="img"><title>Affinity</title><path d="M12 0A12 12 90 000 12V180a12 12 180 0012 12H180a12 12 180 0012-12V12A12 12 90 00180 0H12Z" transform="scale(1.25)" fill="#A7F175"/><path d="M112.908,165.979c-10.413,0 -17.037,-6.317 -17.037,-14.329c0,-34.287 90.164,-41.151 90.164,-77.563c0,-22.213 -25.731,-31.787 -58.577,-31.787c-19.98,0 -42.571,3.698 -63.552,10.315l0,43.438c30.919,-22.52 65.272,-32.16 87.4,-32.16c13.914,0 23.355,3.882 23.355,10.838c0,28.658 -124.353,24.117 -124.353,86.042c0,23.736 16.321,37.335 39.283,37.335c34.732,0 61.003,-33.753 80.657,-74.897l3.982,1.43c-10.351,25.143 -31.771,52.712 -35.943,70.434l44.715,0l0,-91.685l-10.327,0c-14.99,32.574 -36.957,62.59 -59.767,62.59" fill="#0E1318" fill-rule="nonzero"/></svg>
                  Affinity
                </span>
              </div>
            </div>

            <!-- Services -->
            <div class="ds-category" data-category="services">
              <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">Services</p>
              <div class="flex flex-wrap gap-3">
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#3b82f6;"><path d="M6 2H18C19.1 2 20 2.9 20 4V20C20 21.1 19.1 22 18 22H6C4.9 22 4 21.1 4 20V4C4 2.9 4.9 2 6 2Z"/><path d="M10 18H14"/></svg>
                  Mobile Application Design
                </span>
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#10b981;"><path d="M21 3H3C1.9 3 1 3.9 1 5V15C1 16.1 1.9 17 3 17H21C22.1 17 23 16.1 23 15V5C23 3.9 22.1 3 21 3Z"/><path d="M9 21H15M12 17V21"/></svg>
                  Web Design
                </span>
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#14b8a6;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><rect x="6" y="12" width="5" height="6" rx="0.5"/><path d="M14 12h4M14 15h4M14 18h2"/></svg>
                  Landing Page Design
                </span>
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;"><path d="M12 2L2 7L12 12L22 7L12 2Z"/><path d="M2 17L12 22L22 17"/><path d="M2 12L12 17L22 12"/></svg>
                  Design Systems
                </span>
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#8b5cf6;"><path d="M3 22H21M5 21V8M10 21V4M15 21V12M20 21V6"/></svg>
                  SaaS Dashboard Design
                </span>
                <span class="tech-item bg-white dark:bg-surface-dark inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 px-3.5 py-2 text-[0.95rem] leading-none text-gray-800 dark:text-gray-200 shadow-sm">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#ec4899;"><path d="M3 21L7.5 16.5"/><path d="M7.5 16.5L16.5 7.5C17.6 6.4 17.6 4.6 16.5 3.5C15.4 2.4 13.6 2.4 12.5 3.5L3.5 12.5L7.5 16.5Z"/><path d="M13 7L17 11"/></svg>
                  UI/UX Design
                </span>
              </div>
            </div>

          </div>
        </main>
      </div>

    </div>

    <!-- ===== Footer ===== -->
    <div class="site-footer mx-auto w-full max-w-3xl px-4 sm:px-6 pb-10 sm:pb-14">
      <footer class="border-t border-dashed border-gray-300 pt-6 text-sm dark:border-gray-700 sm:pt-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between sm:gap-6">

          <!-- Left side: Tagline + Info -->
          <div class="flex flex-col gap-2 items-start">
            <p class="text-sm italic text-gray-400 dark:text-gray-500 whitespace-nowrap">Turn Ideas Into Reality</p>
            <div class="flex flex-row flex-nowrap items-center gap-x-1.5 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
              <span class="font-normal tracking-tight text-gray-900 dark:text-white whitespace-nowrap">Karl Justine Membrere</span>
              <span class="text-gray-300 dark:text-gray-600 select-none">/</span>
              <span class="whitespace-nowrap">Don't Stop Learning</span>
              <span class="text-gray-300 dark:text-gray-600 select-none">/</span>
              <span class="whitespace-nowrap inline-flex items-center gap-1.5">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 22C12 22 18 16 18 10C18 6.69 15.31 4 12 4C8.69 4 6 6.69 6 10C6 16 12 22 12 22Z"/>
                  <path d="M14.5 10C14.5 11.38 13.38 12.5 12 12.5C10.62 12.5 9.5 11.38 9.5 10C9.5 8.62 10.62 7.5 12 7.5C13.38 7.5 14.5 8.62 14.5 10Z"/>
                </svg>
                San Manuel, Pangasinan, Philippines
              </span>
            </div>
          </div>

          <!-- Right side: Status badges -->
          <div class="flex flex-col items-end gap-2">
            <div class="flex flex-row items-center gap-2 sm:gap-3 flex-wrap justify-end">
              <p class="rounded-full border border-dashed border-gray-300 px-3 py-1 text-xs font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400 inline-flex items-center gap-1.5 whitespace-nowrap">
                <span class="status-dot"></span>
                Available for Work
              </p>
              <p class="rounded-full border border-dashed border-gray-300 px-3 py-1 text-xs font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400 whitespace-nowrap">
                Open for Freelance
              </p>
            </div>
          </div>

        </div>
      </footer>
    </div>

    <!-- ===== Modal (Send a Message) ===== -->
    <div id="callModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
      <div class="relative w-full max-w-md bg-white dark:bg-ink border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl p-6 sm:p-8 transition-all duration-300 scale-95 opacity-0" id="callModalContent">
        <button onclick="closeModal()" aria-label="Close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6L18 18M6 18L18 6"/></svg>
        </button>
        <h3 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white mb-1">Send a Message</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Fill out the form below and I'll get back to you shortly.</p>

        <form id="contactForm" class="space-y-4">
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;pointer-events:none;">
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
            <input type="text" id="name" name="name" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-surface-dark px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-1 focus:ring-ink dark:focus:ring-white outline-none transition-shadow" placeholder="Karl Justine Membrere">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
            <input type="text" id="company" name="company" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-surface-dark px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-1 focus:ring-ink dark:focus:ring-white outline-none transition-shadow" placeholder="Northline Studio">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" id="email" name="email" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-surface-dark px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-1 focus:ring-ink dark:focus:ring-white outline-none transition-shadow" placeholder="youremail@gmail.com">
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
            <textarea id="message" name="message" required rows="3" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-surface-dark px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-1 focus:ring-ink dark:focus:ring-white outline-none transition-shadow" placeholder="Have a project in mind? Just leave a message, and let’s discuss."></textarea>

            <div class="flex items-center justify-end mt-2">
              <button type="button" id="toggleSuggestions" class="text-[11px] font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white inline-flex items-center gap-1.5 transition-colors">
                <span id="toggleSuggestionsText">Show Suggestions</span>
                <svg id="toggleSuggestionsIcon" class="w-3 h-3 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </button>
            </div>

            <div id="messageSuggestions" class="hidden mt-2" aria-live="polite">
              <div class="flex flex-col gap-1">
                <button type="button" class="message-suggestion" data-suggestion="I want to redesign my website.">I want to redesign my website.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a mobile application design.">I need a mobile application design.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a web design.">I need a web design.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a landing page design.">I need a landing page design.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a SaaS design.">I need a SaaS design.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a design system.">I need a design system.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a system/dashboard design.">I need a system/dashboard design.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a SaaS dashboard design.">I need a SaaS dashboard design.</button>
                <button type="button" class="message-suggestion" data-suggestion="I need a portfolio website design.">I need a portfolio website design.</button>
              </div>
            </div>
          </div>
          <p id="contactSuccess" class="hidden text-sm text-green-600 dark:text-green-400" role="status"></p>
          <p id="contactError" class="hidden text-sm text-red-600 dark:text-red-400" role="alert"></p>
          <button type="submit" id="submitBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-ink dark:bg-white text-white dark:text-black px-4 py-2.5 text-sm font-medium transition-transform hover:scale-[1.02] active:scale-95">
            <span id="submitBtnText">Send Message</span>
          </button>
        </form>
      </div>
    </div>

    <!-- ===== Image Lightbox Viewer ===== -->
    <div id="imageLightbox" class="image-lightbox" role="dialog" aria-modal="true" aria-label="Image preview">
      <div class="lightbox-content">
        <p id="lightboxCaption" class="lightbox-caption lightbox-title"></p>
        <div class="lightbox-stage">
          <div class="lightbox-frame">
            <img id="lightboxImage" src="" alt="">
          </div>
          <button type="button" onclick="closeImageLightbox()" class="lightbox-close" aria-label="Close preview">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6L18 18M6 18L18 6"/></svg>
          </button>
        </div>
        <div class="lightbox-nav" aria-label="Interface navigation">
          <button type="button" id="lightboxPrevious" onclick="showPreviousLightboxImage()">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
            <span>Previous</span>
          </button>
          <span id="lightboxCounter" class="lightbox-counter"></span>
          <button type="button" id="lightboxNext" onclick="showNextLightboxImage()">
            <span>Next</span>
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
          </button>
        </div>
      </div>
    </div>

  </div>

@endsection