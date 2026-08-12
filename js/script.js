// =====================================================
// INTERNSHIP TRACKER - JAVASCRIPT
// =====================================================

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

// Main initialization function
function initializeApp() {
    initializeToastr();
    initializeScrollToTop();
    initializeLoadingSpinner();
    initializeFormValidation();
    initializeBootstrap();
}

// ===================================================
// TOASTR NOTIFICATIONS SETUP
// ===================================================

function initializeToastr() {
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': true,
            'progressBar': true,
            'positionClass': 'toast-top-right',
            'preventDuplicates': true,
            'onclick': null,
            'showDuration': '300',
            'hideDuration': '1000',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut'
        };
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    if (typeof toastr === 'undefined') {
        alert(message);
        return;
    }
    
    switch(type) {
        case 'success':
            toastr.success(message);
            break;
        case 'error':
            toastr.error(message);
            break;
        case 'warning':
            toastr.warning(message);
            break;
        case 'info':
            toastr.info(message);
            break;
        default:
            toastr.info(message);
    }
}

// ===================================================
// SCROLL TO TOP BUTTON
// ===================================================

function initializeScrollToTop() {
    const scrollBtn = document.getElementById('scrollToTopBtn');
    
    if (!scrollBtn) return;
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });
    
    // Scroll to top when clicked
    scrollBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ===================================================
// LOADING SPINNER
// ===================================================

function initializeLoadingSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    
    if (!spinner) return;
    
    // Show spinner on form submit
    document.addEventListener('submit', function(e) {
        const form = e.target;
        // Only show for actual form submissions, not AJAX
        if (form.method.toLowerCase() === 'post') {
            setTimeout(function() {
                spinner.classList.add('active');
            }, 100);
        }
    });
    
    // Hide spinner when page loads
    window.addEventListener('load', function() {
        spinner.classList.remove('active');
    });
}

function showSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.classList.add('active');
}

function hideSpinner() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.classList.remove('active');
}

// ===================================================
// FORM VALIDATION
// ===================================================

function initializeFormValidation() {
    // Bootstrap form validation
    const forms = document.querySelectorAll('form');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            // Check if form is valid
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                showToast('Please fill all required fields', 'warning');
            }
            form.classList.add('was-validated');
        }, false);
    });
}

// Validate email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate phone
function validatePhone(phone) {
    const re = /^[0-9]{10,}$/;
    return re.test(phone.replace(/\D/g, ''));
}

// Validate password
function validatePassword(password) {
    return password.length >= 6;
}

// ===================================================
// BOOTSTRAP INITIALIZATION
// ===================================================

function initializeBootstrap() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// ===================================================
// UTILITY FUNCTIONS
// ===================================================

// Format currency
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

// Format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copied to clipboard!', 'success');
    }).catch(err => {
        showToast('Failed to copy', 'error');
    });
}

// Debounce function (for search, resize, etc)
function debounce(func, delay = 300) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
}

// Throttle function
function throttle(func, limit = 300) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ===================================================
// AJAX/FETCH HELPER
// ===================================================

function fetchData(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
        ...options
    };
    
    return fetch(url, defaultOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showToast('An error occurred. Please try again.', 'error');
        });
}

// ===================================================
// PAGE TRANSITIONS
// ===================================================

function initializePageTransitions() {
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        
        if (link && link.href && !link.target && !link.hasAttribute('data-bs-toggle')) {
            const url = link.href;
            
            // Don't animate if it's a hash link or external link
            if (url.includes('#') || (url.includes('http') && !url.includes(window.location.origin))) {
                return;
            }
            
            e.preventDefault();
            showSpinner();
            
            setTimeout(() => {
                window.location.href = url;
            }, 300);
        }
    });
}

// Initialize page transitions
initializePageTransitions();

// ===================================================
// KEYBOARD SHORTCUTS
// ===================================================

document.addEventListener('keydown', function(e) {
    // Escape key to close modals
    if (e.key === 'Escape') {
        const modal = document.querySelector('.modal.show');
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }
    }
    
    // Ctrl+K or Cmd+K for search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('[data-search-input]');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// ===================================================
// MOBILE MENU AUTO-CLOSE
// ===================================================

document.addEventListener('click', function(e) {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navbarToggler = document.querySelector('.navbar-toggler');
    
    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
        if (!e.target.closest('.navbar-collapse') && !e.target.closest('.navbar-toggler')) {
            navbarToggler.click();
        }
    }
});

// ===================================================
// EXPORT TABLE TO CSV
// ===================================================

function exportTableToCSV(filename = 'data.csv') {
    const table = document.querySelector('table');
    if (!table) {
        showToast('No table found', 'error');
        return;
    }
    
    let csv = [];
    
    // Get headers
    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.textContent.trim());
    csv.push(headers.join(','));
    
    // Get rows
    Array.from(table.querySelectorAll('tbody tr')).forEach(row => {
        const cells = Array.from(row.querySelectorAll('td')).map(td => td.textContent.trim());
        csv.push(cells.join(','));
    });
    
    // Download
    const csvContent = csv.join('\n');
    const link = document.createElement('a');
    link.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvContent);
    link.download = filename;
    link.click();
    
    showToast('Table exported successfully', 'success');
}

// ===================================================
// PASSWORD STRENGTH CHECKER
// ===================================================

function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    return strength;
}

function getPasswordStrengthText(strength) {
    switch(strength) {
        case 0:
        case 1:
            return 'Weak';
        case 2:
            return 'Fair';
        case 3:
            return 'Good';
        case 4:
            return 'Strong';
        case 5:
            return 'Very Strong';
        default:
            return 'Unknown';
    }
}

// ===================================================
// LAZY LOADING FOR IMAGES
// ===================================================

function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

// Initialize lazy loading
initializeLazyLoading();