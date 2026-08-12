<!-- ===== SCROLL TO TOP BUTTON ===== -->
    <button id="scrollToTopBtn" class="scroll-to-top" title="Scroll to top of page" aria-label="Scroll to top">
        <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </button>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-dark text-white mt-auto py-5" role="contentinfo">
        <div class="container">
            
            <!-- ===== FOOTER CONTENT ===== -->
            <div class="row mb-4">
                
                <!-- ===== ABOUT SECTION ===== -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-graduation-cap me-2" aria-hidden="true"></i><?php echo APP_NAME; ?>
                    </h5>
                    <p class="text-white-50 mb-3"><?php echo APP_DESCRIPTION; ?></p>
                    
                    <!-- Social Links -->
                    <div class="social-links">
                        <a href="#" class="text-white-50 me-3" title="Follow us on Twitter" aria-label="Twitter">
                            <i class="fab fa-twitter fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="text-white-50 me-3" title="Follow us on Facebook" aria-label="Facebook">
                            <i class="fab fa-facebook fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="text-white-50 me-3" title="Follow us on LinkedIn" aria-label="LinkedIn">
                            <i class="fab fa-linkedin fa-lg" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="text-white-50" title="Follow us on Instagram" aria-label="Instagram">
                            <i class="fab fa-instagram fa-lg" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                
                <!-- ===== QUICK LINKS ===== -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="/" class="text-white-50" title="Go to Home">Home</a>
                        </li>
                        <li class="mb-2">
                            <a href="/auth/login.php" class="text-white-50" title="Student Login">Login</a>
                        </li>
                        <li class="mb-2">
                            <a href="/auth/register.php" class="text-white-50" title="Register as Student">Register</a>
                        </li>
                        <li class="mb-2">
                            <a href="/auth/admin_login.php" class="text-white-50" title="Admin Login">Admin Login</a>
                        </li>
                    </ul>
                </div>
                
                <!-- ===== RESOURCES ===== -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Resources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Learn about us">About Us</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Read our blog">Blog</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Frequently Asked Questions">FAQ</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Contact support">Contact</a>
                        </li>
                    </ul>
                </div>
                
                <!-- ===== LEGAL ===== -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Read privacy policy">Privacy Policy</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Read terms of service">Terms of Service</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Cookie policy">Cookie Policy</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white-50" title="Disclaimer">Disclaimer</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- ===== DIVIDER ===== -->
            <hr class="bg-white-50 my-4">
            
            <!-- ===== FOOTER BOTTOM ===== -->
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="text-white-50 mb-0">
                        &copy; <span id="year"><?php echo date('Y'); ?></span> 
                        <strong><?php echo APP_NAME; ?></strong>. All rights reserved.
                    </p>
                </div>
                
                <div class="col-md-6 text-md-end">
                    <p class="text-white-50 mb-0">
                        <span class="me-3">Made with <i class="fas fa-heart text-danger" aria-hidden="true"></i> by Team</span>
                        <span class="badge bg-secondary">v<?php echo APP_VERSION; ?></span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- ===== LOADING ANIMATION ===== -->
    <div id="loadingSpinner" class="loading-spinner" aria-hidden="true">
        <div class="spinner"></div>
    </div>

    <!-- ===== SCRIPTS ===== -->
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- jQuery (Optional but useful) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    
    <!-- Toastr JS (For notifications) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" defer></script>
    
    <!-- Custom JS -->
    <script src="/js/script.js" defer></script>
    
    <!-- Extra Scripts (Added by pages if needed) -->
    <?php if (isset($extra_scripts)) { echo $extra_scripts; } ?>
</body>
</html>