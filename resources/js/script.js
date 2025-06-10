document.addEventListener('DOMContentLoaded', function() {
    // --- Elemen Modal ---
        let isLoggedIn = false;
        let appliedPromo = null;
let promoDiscount = 0;
    // let cart = JSON.parse(localStorage.getItem('cart')) || [];
      let cart = [];
    const loginModal = document.getElementById('login-modal');
    const registerModal = document.getElementById('register-modal');
    const cartModal = document.getElementById('cart-modal');
    const successModal = document.getElementById('success-modal');
    const slider = document.querySelector('#featured-slider .slider-track');
    const slides = document.querySelectorAll('#featured-slider .slider-slide');
    const dots = document.querySelectorAll('#featured-slider .slider-dot');
    const prevBtn = document.getElementById('slider-prev');
    const nextBtn = document.getElementById('slider-next');
    const detailModal = document.getElementById('featured-detail-modal');
const closeDetailBtn = document.getElementById('close-featured-detail');
const detailImg = document.getElementById('featured-detail-img');
const detailTitle = document.getElementById('featured-detail-title');
const detailDesc = document.getElementById('featured-detail-desc');
    let current = 0;
    let sliderInterval;
    const proofInput = document.getElementById('proof-files-input');
const qrisCount = document.querySelectorAll('#qris-section .qris-item').length;
const fileCount = proofInput && proofInput.files ? proofInput.files.length : 0;
    const featuredProducts = [
  {
    img: '/image/soto.jpeg',
    title: 'Soto Ayam Betawi Premium',
    desc: 'Soto kami dibuat dari ayam kampung pilihan, rempah-rempah asli Betawi, dan disajikan dengan emping, kentang, serta irisan seledri. Kuah kuning kental dengan rasa gurih yang otentik, cocok untuk menikmati sarapan atau makan siang.'
  },
  {
    img: '/image/bir pletok.avif',
    title: 'Bir Pletok Original & Varian Rasa',
    desc: 'Bir Pletok kami terbuat dari jahe merah, kayu secang, serai, dan cengkeh pilihan. Tersedia varian rasa jahe-kayu manis dan jahe-cengkeh. Nikmati hangat atau dingin untuk sensasi menyegarkan dan petualangan rasa tradisional.'
  },
  {
    img: '/image/soto + nasi.jpg',
    title: 'Nasi Uduk Betawi Spesial',
    desc: 'Nasi uduk gurih khas Betawi, disajikan dengan lauk ayam goreng, telur balado, sambal kacang, dan taburan bawang goreng. Cocok untuk sarapan maupun makan malam bersama keluarga.'
  }
];
    // --- Elemen UI Utama ---
    const mobileCartLink = document.getElementById('mobile-cart-link');
    const navbarLoginLink = document.getElementById('navbar-login-link');
    const mobileNavbarLoginLink = document.getElementById('mobile-navbar-login-link');
     const mobileCartNavLink = document.getElementById('mobile-cart-nav-link');
    const navbarLogoutLink = document.getElementById('navbar-logout-link');
    const mobileNavbarLogoutLink = document.getElementById('mobile-navbar-logout-link');
    const cartLink = document.getElementById('cart-nav-link');
    const openCartBtn = document.getElementById('open-cart-btn');
    const loginBtn = document.getElementById('login-btn');
    const mobileLoginBtn = document.getElementById('mobile-login-btn');
    const closeLoginBtn = document.getElementById('close-login');
    const closeRegisterBtn = document.getElementById('close-register');
    const closeCartBtn = document.getElementById('close-cart');
    const closeSuccessBtn = document.getElementById('close-success');
    // const loginForm = document.getElementById('login-form');
    // const registerForm = document.getElementById('register-form');
    const loginToRegisterLink = document.getElementById('login-to-register-link');
    const registerToLoginLink = document.getElementById('register-to-login-link');
    const proceedToPaymentStepBtn = document.getElementById('proceed-to-payment-step-btn');
    const backToCartBtn = document.getElementById('back-to-cart-btn');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const cartItemsContainer = document.getElementById('cart-items');
    const emptyCartMessage = document.getElementById('empty-cart-message');
    const cartTotal = document.getElementById('cart-total');
    const cartCount = document.getElementById('cart-count');
    const mobileCartCount = document.getElementById('mobile-cart-count');
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartViewContainer = document.getElementById('cart-view-container');
    const paymentViewContainer = document.getElementById('payment-view-container');
    const summaryOrderItemsList = document.getElementById('summary-order-items-list');
    const summaryTotalPrice = document.getElementById('summary-total-price');
    const qrisPaymentTotal = document.getElementById('qris-payment-total');
    const customerNameInput = document.getElementById('customer-name');
    const customerPhoneInput = document.getElementById('customer-phone');
    const customerAddressInput = document.getElementById('customer-address');
       const photos = document.querySelectorAll('.stacked-photo');
    //        const orgPhotoStack = document.getElementById('org-photo-stack');
    // const orgPhotos = orgPhotoStack ? Array.from(orgPhotoStack.querySelectorAll('.stacked-photo')) : [];
    // const animateBtn = document.getElementById('animateBtn');
    //  const name   = document.getElementById('register-name').value.trim();
    // const email  = document.getElementById('register-email').value.trim();
    // const pass   = document.getElementById('register-password').value;
    // const passC  = document.getElementById('register-confirm-password').value;

    //  const payload = {
    //     name: name,
    //     email: email,
    //     password: pass,
    //     password_confirmation: passC
    // };

    const mobileMenuBtn = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
}

const lihatMenuLinks = document.querySelectorAll('a[href="#menu"]');
lihatMenuLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const menuSection = document.getElementById('menu');
        if (menuSection) {
            menuSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        // Tutup modal keranjang jika terbuka
        if (cartModal && !cartModal.classList.contains('hidden')) {
            cartModal.classList.add('hidden');
        }
    });
});

const sectionIds = ['home', 'menu', 'about'];
const navLinks = document.querySelectorAll('.nav-item[data-target], .mobile-nav-item');

function setActiveNav() {
    let scrollPos = window.scrollY + 120; // Offset untuk sticky navbar
    let activeId = sectionIds[0];

    sectionIds.forEach(id => {
        const section = document.getElementById(id);
        if (section && section.offsetTop <= scrollPos) {
            activeId = id;
        }
    });

    navLinks.forEach(link => {
        const target = link.dataset.target || (link.getAttribute('href') ? link.getAttribute('href').replace('#', '') : '');
        if (target === activeId) {
            link.classList.add('active-nav', 'text-amber-600');
        } else {
            link.classList.remove('active-nav', 'text-amber-600');
        }
    });
}

window.addEventListener('scroll', setActiveNav);
window.addEventListener('DOMContentLoaded', setActiveNav);

document.querySelectorAll('.nav-item[data-target], .mobile-nav-item').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        // Hanya intercept jika href diawali dengan #
        if (this.dataset.target || (href && href.startsWith('#'))) {
            e.preventDefault();
            const targetId = this.dataset.target || href.replace('#', '');
            const el = document.getElementById(targetId);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            // Tutup mobile menu jika di mode mobile
            if (mobileMenu && window.innerWidth < 768) {
                mobileMenu.classList.add('hidden');
            }
        }
    });
});

if (mobileCartLink) {
    mobileCartLink.addEventListener('click', function(e) {
        e.preventDefault();
        openCartModal();
        if (mobileMenu) mobileMenu.classList.add('hidden');
    });
}
if (mobileCartNavLink) {
    mobileCartNavLink.addEventListener('click', function(e) {
        e.preventDefault();
        openCartModal();
        if (mobileMenu) mobileMenu.classList.add('hidden');
    });
}

  async function syncCartFromApi() {
    try {
        const response = await fetch('/api/cart', {
            method: 'GET',
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        });
        if (response.ok) {
            const items = await response.json();
            cart = items.map(item => ({
                id: parseInt(item.product_id, 10),
                product_id: parseInt(item.product_id, 10),
                name: item.product ? item.product.name : '',
                price: item.product ? item.product.price : 0,
                quantity: item.quantity,
                                max: item.product ? item.product.stock : 0, 
                                stock: item.product ? item.product.stock : 0  
            }));
            saveCart();
            updateCartCount();
            updateCartDisplay();
        }
    } catch (err) {
        console.error('Gagal sinkronisasi keranjang dari API:', err);
    }
}

    // =========================
    // FUNGSI UTAMA
    // =========================

    function loadCartFromLocal() {
    cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateCartCount();
    updateCartDisplay();
}    

    // --- Fungsi Autentikasi ---
     async function checkAuthStatus() {
  try {
    const response = await fetch('/user-status', {
      method: 'GET',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json' }
    });
    const data = await response.json();
    isLoggedIn = data.authenticated;
    updateAuthUI(isLoggedIn);

    if (isLoggedIn) {
      // merge dulu sebelum sinkron
      await mergeLocalCartToApi();
      await syncCartFromApi();   // ambil ulang keranjang yang sudah di-merge
    } else {
      loadCartFromLocal();       // guest flow
    }

    return data;
  } catch (error) {
    console.error('Error checking auth status:', error);
    isLoggedIn = false;
    updateAuthUI(false);
    loadCartFromLocal();
    return { authenticated: false };
  }
}


    checkAuthStatus();

  function updateAuthUI(authenticated) {
    if (authenticated) {
        // Tampilkan keranjang dan tombol logout
        if (cartLink) cartLink.style.display = 'flex';
        if (mobileCartLink) mobileCartLink.style.display = 'flex';
        if (mobileCartNavLink) mobileCartNavLink.style.display = 'flex'; // <-- Tambahkan baris ini

        if (navbarLogoutLink) navbarLogoutLink.style.display = 'block';
        if (mobileNavbarLogoutLink) mobileNavbarLogoutLink.style.display = 'block';

        // Sembunyikan tombol login
        if (navbarLoginLink) navbarLoginLink.style.display = 'none';
        if (mobileNavbarLoginLink) mobileNavbarLoginLink.style.display = 'none';
    } else {
        // Sembunyikan keranjang dan tombol logout
        if (cartLink) cartLink.style.display = 'none';
        if (mobileCartLink) mobileCartLink.style.display = 'none';
        if (mobileCartNavLink) mobileCartNavLink.style.display = 'none'; // <-- Tambahkan baris ini

        if (navbarLogoutLink) navbarLogoutLink.style.display = 'none';
        if (mobileNavbarLogoutLink) mobileNavbarLogoutLink.style.display = 'none';

        // Tampilkan tombol login
        if (navbarLoginLink) navbarLoginLink.style.display = 'block';
        if (mobileNavbarLoginLink) mobileNavbarLoginLink.style.display = 'block';
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast-notif');
    if (!toast) return;
    toast.textContent = message;
    toast.className = 'fixed top-6 right-6 z-[9999] px-6 py-4 rounded-lg shadow-lg font-semibold text-lg transition-all duration-500';
    if (type === 'success') {
        toast.classList.add('bg-amber-500', 'text-white');
    } else if (type === 'error') {
        toast.classList.add('bg-red-500', 'text-white');
    }
    toast.classList.remove('hidden');
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 2000);
}
    
//    async function handleLoginFormSubmit(e) {
//     e.preventDefault();

//     const email = document.getElementById('login-email').value;
//     const password = document.getElementById('login-password').value;
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

//     try {
//         const response = await fetch('/login', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'Accept': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             credentials: 'same-origin', // <-- WAJIB
//             body: JSON.stringify({
//                 email: email,
//                 password: password
//             })
//         });

//         const data = await response.json();

//        if (response.ok) {
//     showToast('Login berhasil! Selamat datang 👋', 'success');
//     setTimeout(() => window.location.reload(), 1200);
//     return;
//         } else {
//             throw new Error(data.message || 'Login failed');
//         }
//     } catch (error) {
//         alert(`Login gagal: ${error.message}`);
//     }
// }
    
//   async function handleRegisterFormSubmit(e) {
//     e.preventDefault();

//     const name = document.getElementById('register-name').value;
//     const email = document.getElementById('register-email').value;
//     const password = document.getElementById('register-password').value;
//     const confirmPassword = document.getElementById('register-confirm-password').value;
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

//     if (password !== confirmPassword) {
//         alert('Password dan konfirmasi password tidak sama');
//         return;
//     }

//     try {
//         const response = await fetch('/register', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'Accept': 'application/json',
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             credentials: 'same-origin', // <-- WAJIB
//             body: JSON.stringify({
//                 name: name,
//                 email: email,
//                 password: password,
//                 password_confirmation: confirmPassword
//             })
//         });

//         const data = await response.json();

//         if (response.ok) {
//             // Registrasi berhasil, reload agar session aktif
//             window.location.reload();
//         } else {
//             const errors = data.errors ? Object.values(data.errors).join('\n') : data.message;
//             throw new Error(errors || 'Registrasi gagal');
//         }
//     } catch (error) {
//         alert(`Registrasi gagal: ${error.message}`);
//     }
// }
    
 async function handleLogout() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin' // <-- INI PENTING AGAR COOKIE SESSION TERKIRIM
        });

        if (response.ok) {
            // Reset keranjang di semua mode (desktop & mobile)
            cart = [];
            saveCart();
            updateCartCount();
            updateCartDisplay();

            // Sembunyikan semua cart link mobile/desktop
            updateAuthUI(false);

            // Tutup modal keranjang jika terbuka
            if (cartModal && !cartModal.classList.contains('hidden')) {
                cartModal.classList.add('hidden');
            }

            showToast('Logout berhasil! Sampai jumpa lagi 👋', 'success');
            setTimeout(() => window.location.reload(), 1200);
            return;
        } else {
            alert('Logout gagal!');
        }
    } catch (error) {
        console.error('Logout error:', error);
        alert('Terjadi kesalahan saat logout');
    }
}
 function updateCartCount() {
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        if (document.getElementById('cart-count')) document.getElementById('cart-count').textContent = count;
        if (document.getElementById('mobile-cart-count')) document.getElementById('mobile-cart-count').textContent = count;
        if (document.getElementById('mobile-menu-cart-count')) document.getElementById('mobile-menu-cart-count').textContent = count;
    }
    
    function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    async function mergeLocalCartToApi() {
  const localCart = JSON.parse(localStorage.getItem('cart')) || [];
  const token = document.querySelector('meta[name="csrf-token"]').content;

  for (const item of localCart) {
    await fetch('/api/cart', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        product_id: item.id,
        quantity: item.quantity
      })
    });
  }

  // Setelah tersimpan di server, clear localStorage
  localStorage.removeItem('cart');
}

document.getElementById('order-form').addEventListener('submit', function(e) {
    // Ambil data keranjang dari JS (misal: window.cart)
    const items = window.cart || [];
    document.getElementById('order-items-json').value = JSON.stringify(items);
    // Jika ada promo
    // document.getElementById('promo-code-input').value = ...;
});

  function updateCartDisplay() {
    if (!cartItemsContainer) return;

    cartItemsContainer.innerHTML = '';
    let total = 0;
    
    if (cart.length === 0) {
        if (emptyCartMessage) emptyCartMessage.style.display = 'block';
        if (cartTotal) cartTotal.textContent = 'Rp 0';
        if (proceedToPaymentStepBtn) proceedToPaymentStepBtn.disabled = true;
    } else {
        if (emptyCartMessage) emptyCartMessage.style.display = 'none';
        if (proceedToPaymentStepBtn) proceedToPaymentStepBtn.disabled = false;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            const cartItem = document.createElement('div');
            cartItem.className = 'flex justify-between items-start border-b pb-4 cart-item mb-4';
            if (index === cart.length - 1) cartItem.classList.remove('border-b', 'pb-4');

            // PENTING: tambahkan data-max="${item.max}" di kedua tombol
            cartItem.innerHTML = `
                <div>
                    <h4 class="font-bold text-gray-800">${item.name}</h4>
                    <p class="text-gray-600">Rp ${item.price.toLocaleString('id-ID')}</p>
                      <p class="text-gray-600">Jumlah: ${item.quantity}</p>
        <p class="text-xs text-amber-600 font-semibold">
            Stok: ${item.stock ?? item.max ?? 0}
        </p>
                    
                </div>
                <div class="flex items-center">
                    <button 
                        class="quantity-btn text-gray-500 hover:text-gray-700 p-1 decrease-btn" 
                        data-index="${index}" 
                        data-action="decrease" 
                        data-max="${item.max}"
                    >
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="mx-3 w-8 text-center quantity-display">${item.quantity}</span>
                    <button 
                        class="quantity-btn text-gray-500 hover:text-gray-700 p-1 increase-btn" 
                        data-index="${index}" 
                        data-action="increase" 
                        data-max="${item.max}"
                    >
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="ml-4 text-red-500 hover:text-red-700 remove-item-btn p-1" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            cartItemsContainer.appendChild(cartItem);
        });
    }

    if (cartTotal) cartTotal.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    if (qrisPaymentTotal) qrisPaymentTotal.textContent = `Total: Rp ${total.toLocaleString('id-ID')}`;

    // Pasang event listener ulang setiap kali render ulang
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', handleQuantityChange);
    });
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', handleDeleteItem);
    });
}
// const animateBtn = document.getElementById('animateBtn');

// function getRandomPositions(count, isLandscape) {
//     const positions = [];
//     for (let i = 0; i < count; i++) {
//         if (isLandscape) {
    //             positions.push({
//                 left: `${8 + i * 15}%`,
//                 top: `${12 + (i % 2) * 20 + Math.random() * 6}%`,
//                 rotate: `${-8 + Math.random() * 16}deg`
//             });
//         } else {
    //             positions.push({
        //                 left: '50%',
//                 top: `${8 + i * 15 + Math.random() * 5}%`,
//                 rotate: `${-8 + Math.random() * 16}deg`
//             });
//         }
//     }
//     return positions;
// }

// function shuffleArray(arr) {
    //     for (let i = arr.length - 1; i > 0; i--) {
        //         const j = Math.floor(Math.random() * (i + 1));
        //         [arr[i], arr[j]] = [arr[j], arr[i]];
        //     }
        //     return arr;
        const orgPhotoStack = document.getElementById('org-photo-stack');
        const orgPhotos = orgPhotoStack ? Array.from(orgPhotoStack.querySelectorAll('.stacked-photo')) : [];
// }

function resetOrgPhotoPositions() {
  const isLandscape = window.innerWidth >= 768;

  orgPhotos.forEach((photo, i) => {
    photo.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
    
    // RANDOM desktop layout (sebelumnya)
    if (isLandscape) {
      const leftBase = 10 + i * 20;
      const topBase  = (i % 2 === 0 ? 12 : 32) + Math.random() * 6;
      const rotate   = -10 + Math.random() * 20;

      photo.style.left      = `${leftBase}%`;
      photo.style.top       = `${topBase}%`;
      photo.style.transform = `translateX(-50%) rotate(${rotate}deg) scale(1.12)`;
      photo.style.zIndex    = 10 + i;
    }
    // CENTER mobile
    else {
      const rotate = -5 + Math.random() * 10;
      const topPos = 10 + i * 16 + Math.random() * 4;

      photo.style.left      = `50%`;
      photo.style.top       = `${topPos}%`;
      photo.style.transform = `translateX(-50%) rotate(${rotate}deg) scale(1.12)`;
      photo.style.zIndex    = 10 + i;
    }
  });
}


document.querySelectorAll('.stacked-photo').forEach(photo => {
    photo.addEventListener('mouseenter', function () {
        this.style.transform = this.style.transform.replace(/scale\([^)]+\)/, 'scale(1.22)');
        this.style.zIndex = 99;
        this.style.boxShadow = '0 35px 60px -10px rgba(0,0,0,0.7)';
    });
    photo.addEventListener('mouseleave', function () {
        resetOrgPhotoPositions();
        this.style.boxShadow = '';
    });
});

function setOrgPhotoInitial() {
    resetOrgPhotoPositions();
}
setOrgPhotoInitial();
window.addEventListener('resize', setOrgPhotoInitial);
// function animateShuffleOrgPhotos() {
//     if (!orgPhotos.length) return;
//     const isLandscape = window.innerWidth >= 768;
//     const shuffled = shuffleArray([...orgPhotos]);
//     const positions = getRandomPositions(orgPhotos.length, isLandscape);

//     shuffled.forEach((photo, idx) => {
//         photo.style.zIndex = 20 + idx;
//         photo.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
//         photo.style.opacity = '1';
//         photo.style.left = positions[idx].left;
//         photo.style.top = positions[idx].top;
//         photo.style.transform = `rotate(${positions[idx].rotate}) scale(1.05)`;
//     });

//     // Kembali ke posisi semula setelah 1.5 detik
//     setTimeout(() => {
//         resetOrgPhotoPositions();
//     }, 1500);
// }

// Set posisi awal saat load dan saat resize

// if (animateBtn) {
//     animateBtn.addEventListener('click', animateShuffleOrgPhotos);
// }

 function handleQuantityChange(e) {
    const button = e.target.closest('button');
    const index = button.getAttribute('data-index');
    const action = button.getAttribute('data-action');
    const max = parseInt(button.getAttribute('data-max'), 10);

    if (action === 'increase') {
        if (!isNaN(max) && cart[index].quantity >= max) {
            alert(`Maksimal kuantitas untuk item ini adalah ${max}`);
            return;
        }
        cart[index].quantity += 1;
    } 
    else if (action === 'decrease') {
        if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
        } else {
            alert('Kuantitas tidak bisa kurang dari 1');
            return;
        }
    }

    saveCart();
    updateCartDisplay();
    updateCartCount();
}


       function handleDeleteItem(e) {
    const button = e.target.closest('button');
    const index = button.getAttribute('data-index');
    const item = cart[index];

    // Hapus di frontend
    cart.splice(index, 1);
    saveCart();
    updateCartDisplay();
    updateCartCount();

    // Jika user login, hapus juga di backend
    if (isLoggedIn && item && item.id) {
        fetch(`/api/cart/${item.id}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
    }
}


    const orderItemsToSend = [];
const summaryItemRows = document.querySelectorAll('#summary-order-items-list .summary-item-row');

summaryItemRows.forEach(row => {
    // PENTING: data-product-id, data-price, data-quantity harus ada di HTML Anda!
    const productId = parseInt(row.dataset.productId);
    const quantity = parseInt(row.dataset.quantity);
    const price = parseFloat(row.dataset.price); 

    if (productId && quantity > 0 && price >= 0) { // <-- MASALAHNYA KEMUNGKINAN BESAR DI SINI!
        orderItemsToSend.push({
            product_id: productId,
            quantity: quantity,
            price: price, 
            subtotal: price * quantity
        });
    }
});

function showSlide(idx) {
        slides.forEach((slide, i) => {
            slide.style.opacity = (i === idx) ? '1' : '0';
            slide.style.pointerEvents = (i === idx) ? 'auto' : 'none';
        });
        slider.style.transform = `translateX(-${idx * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-amber-500', i === idx);
            dot.classList.toggle('bg-gray-300', i !== idx);
        });
        current = idx;
    }

    function nextSlide() {
        let idx = (current + 1) % slides.length;
        showSlide(idx);
    }

    function prevSlide() {
        let idx = (current - 1 + slides.length) % slides.length;
        showSlide(idx);
    }

    // Dots click
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => showSlide(i));
    });

    // Button click
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // Auto slide
    function startSlider() {
        sliderInterval = setInterval(nextSlide, 5000);
    }
    function stopSlider() {
        clearInterval(sliderInterval);
    }
    if (slider) {
        showSlide(0);
        startSlider();
        slider.parentElement.addEventListener('mouseenter', stopSlider);
        slider.parentElement.addEventListener('mouseleave', startSlider);
    }

function updateOrderSummary() {
    if (!summaryOrderItemsList) return;

    summaryOrderItemsList.innerHTML = '';
    let total = 0;

    // Hitung qty dan price dasar untuk promo
    let sotoQty = 0, birQty = 0;
    let promoItemsTotal = 0;
    cart.forEach(item => {
        if (item.name.toLowerCase().includes('soto')) {
            sotoQty += item.quantity;
            promoItemsTotal += item.price * item.quantity;
        }
        if (item.name.toLowerCase().includes('bir pletok')) {
            birQty += item.quantity;
            promoItemsTotal += item.price * item.quantity;
        }
    });

    // Tampilkan atau sembunyikan section promo
    const promoSection = document.getElementById('promo-section');
    if (promoSection) {
        if (sotoQty === 1 && birQty === 1) {
            promoSection.classList.remove('hidden');
        } else {
            promoSection.classList.add('hidden');
            appliedPromo = null;
            promoDiscount = 0;
            const promoInput = document.getElementById('promo-code');
            const promoMsg   = document.getElementById('promo-message');
            if (promoInput) promoInput.value = '';
            if (promoMsg) promoMsg.textContent = '';
        }
    }

    // Hitung total sebelum promo
    cart.forEach(item => {
        total += item.price * item.quantity;
    });

    // Jika promo aktif, hitung potongan global
    let potonganGlobal = 0;
    if (appliedPromo && promoDiscount > 0) {
        potonganGlobal = Math.round(total * (promoDiscount / 100));
    }

    // Render setiap item
    cart.forEach(item => {
        const isPromoItem = appliedPromo && promoDiscount > 0
            && (item.name.toLowerCase().includes('soto')
                || item.name.toLowerCase().includes('bir pletok'));

        let displayHTML;
        if (isPromoItem) {
            // Hitung potongan per item secara proporsional
            const perItemDiscount = Math.round(potonganGlobal * item.price / promoItemsTotal);
            const hargaSetelahDiskon = item.price - perItemDiscount;

            displayHTML = `
                <span>${item.name} (${item.quantity}x)</span>
                <span>
                    <span class="line-through text-gray-400 mr-2">
                        Rp ${item.price.toLocaleString('id-ID')}
                    </span>
                    <span class="text-amber-600 font-bold">
                        Rp ${hargaSetelahDiskon.toLocaleString('id-ID')}
                    </span>
                </span>
            `;
        } else {
            const itemTotal = item.price * item.quantity;
            displayHTML = `
                <span>${item.name} (${item.quantity}x)</span>
                <span>Rp ${itemTotal.toLocaleString('id-ID')}</span>
            `;
        }

        const itemElement = document.createElement('div');
        itemElement.className = 'flex justify-between text-sm summary-item-row';
        itemElement.dataset.productId = item.id;
        itemElement.dataset.quantity  = item.quantity;
        itemElement.dataset.price     = item.price;
        itemElement.innerHTML = displayHTML;
        summaryOrderItemsList.appendChild(itemElement);
    });

    // Update elemen total dan grand total
    const grandtotal = total - potonganGlobal;
    if (summaryTotalPrice) summaryTotalPrice.textContent = `Rp ${total.toLocaleString('id-ID')}`;
    if (qrisPaymentTotal) qrisPaymentTotal.textContent = `Total: Rp ${grandtotal.toLocaleString('id-ID')}`;

    // Tampilkan keterangan potongan dan grand total
    let grandTotalEl = document.getElementById('grandtotal-summary');
    if (!grandTotalEl) {
        grandTotalEl = document.createElement('p');
        grandTotalEl.id    = 'grandtotal-summary';
        grandTotalEl.className = 'text-right text-lg font-bold text-amber-600 mt-2';
        summaryOrderItemsList.parentElement.appendChild(grandTotalEl);
    }
    grandTotalEl.innerHTML = `
        ${potonganGlobal > 0 
            ? `<span class="block text-green-600 text-sm">
                   Potongan: ${promoDiscount}% (-Rp ${potonganGlobal.toLocaleString('id-ID')})
               </span>` 
            : ''}
        Grand Total: Rp ${grandtotal.toLocaleString('id-ID')}
    `;

    updateQrisSection();
}


// Di akhir updateOrderSummary()
 
function updateQrisSection() {
    const qrisSection = document.getElementById('qris-section');
    if (!qrisSection) return;

    // Promo flag dan harga promo tetap
    const isPromo = appliedPromo && promoDiscount > 0;
    const promoSotoPrice = 10200;      // Harga promo Soto
    const promoBirPrice  = 6800;       // Harga promo Bir Pletok

    // Inisialisasi flag dan total
    let hasSoto = false, hasBir = false;
    let sotoTotal = 0, birTotal = 0;

    // Hitung total sesuai kondisi promo
    cart.forEach(item => {
        const name = item.name ? item.name.toLowerCase() : '';
        // Hitung Soto (termasuk "soto + nasi")
        if (name.includes('soto')) {
            hasSoto = true;
            sotoTotal += (isPromo ? promoSotoPrice : item.price) * item.quantity;
        }
        // Hitung Bir Pletok
        if (name.includes('bir') && name.includes('pletok')) {
            hasBir = true;
            birTotal += (isPromo ? promoBirPrice : item.price) * item.quantity;
        }
    });

    // Bangun HTML QRIS sesuai item
    let html = '';
    if (hasSoto) {
        html += `
        <div class="qris-item text-center bg-gray-100 p-4 rounded-lg border border-gray-200 mb-4">
            <p class="text-gray-700 mb-3"><b>Scan kode QRIS Soto untuk pembayaran Varian Soto:</b></p>
            <img src="/image/qris_soto.jpeg" alt="QRIS Soto" class="mx-auto w-52 h-52 object-contain border border-gray-300 rounded-lg">
            <p class="text-sm text-gray-600 mt-2">
                Total Soto: <span class="font-bold text-amber-600">Rp ${sotoTotal.toLocaleString('id-ID')}</span>
            </p>
        </div>
        `;
    }
    if (hasBir) {
        html += `
        <div class="qris-item text-center bg-gray-100 p-4 rounded-lg border border-gray-200 mb-4">
            <p class="text-gray-700 mb-3"><b>Scan kode QRIS Bir Pletok untuk pembayaran Varian Bir Pletok:</b></p>
            <img src="/image/qris_bir.jpeg" alt="QRIS Bir" class="mx-auto w-52 h-52 object-contain border border-gray-300 rounded-lg">
            <p class="text-sm text-gray-600 mt-2">
                Total Bir Pletok: <span class="font-bold text-amber-600">Rp ${birTotal.toLocaleString('id-ID')}</span>
            </p>
        </div>
        `;
    }
    if (!hasSoto && !hasBir) {
        html = `<div class="text-center text-gray-500">QRIS akan muncul jika Anda memesan Soto atau Bir Pletok.</div>`;
    }

    // Render section QRIS
    qrisSection.innerHTML = html;

    // Tampilkan tombol order jika ada QRIS
    const qrisCount = qrisSection.querySelectorAll('.qris-item').length;
    const orderBtn = document.getElementById('place-order-btn');
    if (orderBtn) {
        orderBtn.style.display = qrisCount > 0 ? 'inline-block' : 'none';
    }
}



    // --- Fungsi Modal ---
    function openLoginModal() {
        if (loginModal) loginModal.classList.remove('hidden');
    }

    function closeLoginModal() {
        if (loginModal) loginModal.classList.add('hidden');
    }

    function openRegisterModal() {
        if (registerModal) registerModal.classList.remove('hidden');
    }

    function closeRegisterModal() {
        if (registerModal) registerModal.classList.add('hidden');
    }

    function openCartModal() {
        if (cartModal) cartModal.classList.remove('hidden');
        updateCartDisplay();
    }

    function closeCartModal() {
        if (cartModal) cartModal.classList.add('hidden');
        // Kembalikan ke tampilan keranjang saat modal ditutup
        if (cartViewContainer && paymentViewContainer) {
            cartViewContainer.classList.remove('hidden');
            paymentViewContainer.classList.add('hidden');
        }
    }

    function openSuccessModal() {
        if (successModal) {
            successModal.classList.remove('hidden');
        }
    }

    function closeSuccessModal() {
        if (successModal) successModal.classList.add('hidden');
    }

    // =========================
    // EVENT LISTENERS
    // =========================

    // --- Event Listener untuk Modal ---
    if (cartLink) {
    cartLink.addEventListener('click', function(e) {
        e.preventDefault();
        openCartModal();
    });
}

if (closeCartBtn) {
    closeCartBtn.addEventListener('click', function() {
        // Kembalikan ke tampilan keranjang sebelum menutup modal
        if (cartViewContainer && paymentViewContainer) {
            cartViewContainer.classList.remove('hidden');
            paymentViewContainer.classList.add('hidden');
        }
        if (cartModal) cartModal.classList.add('hidden');
    });
}

window.addEventListener('click', function(e) {
    if (e.target === cartModal) {
        cartModal.classList.add('hidden');
    }
});

if (mobileCartLink) {
    mobileCartLink.addEventListener('click', function(e) {
        e.preventDefault();
        openCartModal();
    });
}

    if (closeLoginBtn) {
        closeLoginBtn.addEventListener('click', closeLoginModal);
    }

    if (closeRegisterBtn) {
        closeRegisterBtn.addEventListener('click', closeRegisterModal);
    }

    // if (closeCartBtn) {
    //     closeCartBtn.addEventListener('click', closeCartModal);
    // }

if (closeSuccessBtn) {
    closeSuccessBtn.addEventListener('click', function() {
        closeSuccessModal();
        window.location.reload();
    });
}

    if (loginToRegisterLink) {
        loginToRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            closeLoginModal();
            openRegisterModal();
        });
    }

    if (registerToLoginLink) {
        registerToLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            closeRegisterModal();
            openLoginModal();
        });
    }

    // --- Event Listener untuk Autentikasi ---
    // if (loginForm) {
    //     loginForm.addEventListener('submit', handleLoginFormSubmit);
    // }

    // if (registerForm) {
    //     registerForm.addEventListener('submit', handleRegisterFormSubmit);
    // }

if (navbarLoginLink) {
    navbarLoginLink.addEventListener('click', function(e) {
        e.preventDefault();
        openLoginModal();
    });
}
    
if (mobileNavbarLoginLink) {
    mobileNavbarLoginLink.addEventListener('click', function(e) {
        e.preventDefault();
        openLoginModal();
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) mobileMenu.classList.add('hidden');
    });
}

if (navbarLogoutLink) {
    navbarLogoutLink.addEventListener('click', (e) => {
        e.preventDefault();
        handleLogout();
    });
}
if (mobileNavbarLogoutLink) {
    mobileNavbarLogoutLink.addEventListener('click', (e) => {
        e.preventDefault();
        handleLogout();
    });
}

    if (loginBtn) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (isLoggedIn) {
                handleLogout();
            } else {
                openLoginModal();
            }
        });
    }

if (placeOrderBtn) {
    placeOrderBtn.addEventListener('click', function(e) {
        e.preventDefault();

        if (!cart || cart.length === 0) {
            alert('Keranjang Anda kosong.');
            return;
        }

        // // Hitung kebutuhan jumlah gambar bukti
        // let qrisCount = 0;
        // cart.forEach(item => {
        //     const name = item.name ? item.name.toLowerCase() : '';
        //     if (name.includes('soto')) qrisCount++;
        //     if (name.includes('bir') && name.includes('pletok')) qrisCount++;
        // });
        // // Jika user pesan Soto dan Bir Pletok, qrisCount bisa 2 (tapi jika pesan 2 Soto saja, tetap 1)
        // // Pastikan unik
        // if (qrisCount > 2) qrisCount = 2;
        // if (qrisCount === 0) qrisCount = 1; // fallback minimal 1

          const qrisCount = document.querySelectorAll('#qris-section .qris-item').length;
        // const proofInput = document.getElementById('proof-files-input');
        // const fileCount = proofInput && proofInput.files ? proofInput.files.length : 0;

        // if (fileCount < qrisCount) {
        //     alert(`Anda harus mengupload minimal ${qrisCount} foto bukti pembayaran sesuai jumlah QRIS yang muncul!`);
        //     proofInput.focus();
        //     return;
        // }

        const proofInput = document.querySelector('#order-form input[type="file"][name="proof_files[]"]');
        const fileCount = proofInput && proofInput.files ? proofInput.files.length : 0;

        if (fileCount < qrisCount) {
            alert(`Anda harus mengupload minimal ${qrisCount} foto bukti pembayaran sesuai jumlah QRIS yang muncul!`);
            proofInput.focus();
            return;
        }

        // ...lanjutkan proses submit seperti biasa...
        const form = document.getElementById('order-form');
        const formData = new FormData(form);

        // Hapus input hidden items agar tidak overwrite
        if (document.getElementById('order-items-json')) {
            document.getElementById('order-items-json').value = '';
        }

        // Tambahkan items sebagai array
        cart.forEach((it, idx) => {
            formData.append(`items[${idx}][product_id]`, it.id);
            formData.append(`items[${idx}][quantity]`, it.quantity);
        });

        // Tambahkan promo_code jika ada
        if (appliedPromo && appliedPromo.kode) {
            formData.set('promo_code', appliedPromo.kode);
        }

        fetch('/orders', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(res => res.json())
        .then(async json => {
            if (!json.success) {
                alert(json.message || 'Gagal membuat pesanan.');
                return;
            }
            // Reset cart di frontend
            cart = [];
            saveCart();
            updateCartDisplay();
            updateCartCount();
            updateOrderSummary();

            if (isLoggedIn) {
                await syncCartFromApi();
            }

            if (paymentViewContainer) paymentViewContainer.classList.add('hidden');
            if (cartModal) cartModal.classList.add('hidden');
            openSuccessModal();
        })
        .catch(err => {
            console.error(err);
            alert('Gagal melakukan checkout. Coba lagi.');
        });
    });
}
    if (mobileLoginBtn) {
        mobileLoginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (isLoggedIn) {
                handleLogout();
            } else {
                openLoginModal();
            }
        });
    }

    // --- Event Listener untuk Keranjang ---
    if (openCartBtn) {
        openCartBtn.addEventListener('click', (e) => {
            e.preventDefault();

            if (!isLoggedIn) {
                closeCartModal();
                openLoginModal();
                alert('Anda harus login terlebih dahulu untuk melihat keranjang.');
                return;
            }

            openCartModal();
        });
    }

    if (proceedToPaymentStepBtn) {
        proceedToPaymentStepBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Keranjang belanja Anda kosong. Silakan tambahkan item terlebih dahulu.');
                return;
            }
            
            updateOrderSummary();
            
            if (cartViewContainer && paymentViewContainer) {
                cartViewContainer.classList.add('hidden');
                paymentViewContainer.classList.remove('hidden');
            }
        });
    }
if (backToCartBtn) {
    backToCartBtn.addEventListener('click', () => {
        appliedPromo = null;
        promoDiscount = 0;
        const promoInput = document.getElementById('promo-code');
        const promoMsg = document.getElementById('promo-message');
        if (promoInput) promoInput.value = '';
        if (promoMsg) promoMsg.textContent = '';
        if (cartViewContainer && paymentViewContainer) {
            paymentViewContainer.classList.add('hidden');
            cartViewContainer.classList.remove('hidden');
        }
    });
}

   
 if (addToCartButtons) {
    addToCartButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault(); // WAJIB agar form tidak submit/refresh

            if (!isLoggedIn) {
                openLoginModal();
                alert('Anda harus login terlebih dahulu untuk menambahkan item ke keranjang.');
                return;
            }

            const id = parseInt(button.getAttribute('data-id'), 10);
            const name = button.getAttribute('data-name');
            const price = parseInt(button.getAttribute('data-price'), 10);
            const maxStock = parseInt(button.getAttribute('data-max'), 10);

            let existingItem = cart.find(item => parseInt(item.id, 10) === id);

            if (existingItem) {
                if (existingItem.quantity >= maxStock) {
                    alert('Jumlah pesanan sudah mencapai stok maksimal produk.');
                    return;
                }
                existingItem.quantity += 1;
            } else {
               cart.push({ id, name, price, quantity: 1, max: maxStock, stock: maxStock });
            }
            saveCart();
            updateCartCount();
            updateCartDisplay();

            // Kirim ke backend via route web (BUKAN API)
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ product_id: id, quantity: existingItem ? existingItem.quantity : 1 })
                });

                if (!response.ok) {
                    throw new Error('Gagal menambah ke keranjang di server');
                }
            } catch (err) {
                alert('Gagal menambah ke keranjang di server');
                // (Opsional) rollback perubahan cart di frontend jika perlu
            }

            // Feedback visual
            const originalText = button.textContent;
            const originalClasses = button.className;

            button.textContent = '✓ Ditambahkan';
            button.classList.remove('bg-amber-500', 'hover:bg-amber-600');
            button.classList.add('bg-green-500', 'hover:bg-green-600');

            setTimeout(() => {
                button.textContent = originalText;
                button.className = originalClasses;
            }, 1000);
        });
    });
}

    // --- Event Listener untuk Tutup Modal saat Klik di Luar ---
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) closeLoginModal();
        if (e.target === registerModal) closeRegisterModal();
        if (e.target === cartModal) closeCartModal();
        if (e.target === successModal) closeSuccessModal();
    });

    
    //  fetch('{{ route("register") }}', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
    //     },
    //     body: JSON.stringify(payload)
    // })
    // .then(async res => {
    //     const data = await res.json();
    //     if (res.ok) {
    //         // Registrasi berhasil, misalnya tutup modal dan reload halaman
    //         document.getElementById('register-modal').classList.add('hidden');
    //         window.location.reload();
    //     } else if (res.status === 422) {
    //         // Tampilkan validasi error
    //         const errors = data.errors;
    //         if (errors.name) {
    //             document.getElementById('register-name-error').textContent = errors.name[0];
    //             document.getElementById('register-name-error').classList.remove('hidden');
    //         }
    //         if (errors.email) {
    //             document.getElementById('register-email-error').textContent = errors.email[0];
    //             document.getElementById('register-email-error').classList.remove('hidden');
    //         }
    //         if (errors.password) {
    //             document.getElementById('register-password-error').textContent = errors.password[0];
    //             document.getElementById('register-password-error').classList.remove('hidden');
    //         }
    //         if (errors.password_confirmation) {
    //             document.getElementById('register-confirm-password-error').textContent = errors.password_confirmation[0];
    //             document.getElementById('register-confirm-password-error').classList.remove('hidden');
    //         }
    //     } else {
    //         // Error server
    //         alert(data.message || 'Gagal mendaftar user.');
    //     }
    // });

// Tutup modal
if (closeDetailBtn) {
  closeDetailBtn.addEventListener('click', () => {
    detailModal.classList.add('hidden');
  });
}
if (detailModal) {
  detailModal.addEventListener('click', (e) => {
    if (e.target === detailModal) detailModal.classList.add('hidden');
  });
}

document.getElementById('apply-promo-btn').addEventListener('click', async function() {
    const code = document.getElementById('promo-code').value.trim();
    const msg = document.getElementById('promo-message');
    msg.textContent = '';

    if (!code) {
        msg.textContent = 'Masukkan kode promo!';
        msg.className = 'text-red-500 text-sm mt-2';
        return;
    }

    // Cek promo ke backend
    try {
        const res = await fetch(`/promo/check?code=${encodeURIComponent(code)}`);
        const data = await res.json();
        if (data.success && data.promo && data.promo.is_active) {
            appliedPromo = data.promo;
            promoDiscount = data.promo.diskon; // diskon dalam persen, misal 30
            msg.textContent = `Kode promo berhasil! Potongan ${promoDiscount}%`;
            msg.className = 'text-green-600 text-sm mt-2';
            updateOrderSummary();
        } else {
            appliedPromo = null;
            promoDiscount = 0;
            msg.textContent = data.message || 'Kode promo tidak valid atau sudah dipakai.';
            msg.className = 'text-red-500 text-sm mt-2';
        }
    } catch (err) {
        msg.textContent = 'Gagal cek promo.';
        msg.className = 'text-red-500 text-sm mt-2';
    }
});

})

