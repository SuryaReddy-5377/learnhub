        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold"><i class="fas fa-graduation-cap me-2"></i>LearnHub</h5>
                    <p class="text-muted small">Empowering learners worldwide with quality education and skill development courses.</p>
                    <div class="social-icons">
                        <a href="https://www.linkedin.com/in/surya-manohar-reddy-goluguri-110299366" target="_blank" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://github.com/SuryaReddy-5377" target="_blank" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="mailto:suryareddy5377@gmail.com" title="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="courses.php">Courses</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6>Categories</h6>
                    <ul class="list-unstyled">
                        <li><a href="courses.php?category=1">Web Development</a></li>
                        <li><a href="courses.php?category=2">Data Science</a></li>
                        <li><a href="courses.php?category=3">Mobile Apps</a></li>
                        <li><a href="courses.php?category=4">Cloud Computing</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6>Newsletter</h6>
                    <p class="text-muted small">Subscribe for updates on new courses and features.</p>
                    <div class="newsletter-input">
                        <input type="email" placeholder="Your email">
                        <button><i class="fas fa-paper-plane"></i></button>
                    </div>
                    <p class="text-muted small mt-2">We respect your privacy. Unsubscribe anytime.</p>
                </div>
            </div>
            <hr>
            <p class="text-center small mb-0">© 2026 LearnHub. All rights reserved. | Made with ❤️ by Surya Manohar Reddy Goluguri</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 50,
            duration: 800
        });

        // Dark Mode Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                const currentTheme = localStorage.getItem('theme') || 'light';
                
                if (currentTheme === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    updateToggleIcon(true);
                }
                
                themeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                    
                    if (isDark) {
                        document.documentElement.removeAttribute('data-theme');
                        localStorage.setItem('theme', 'light');
                        updateToggleIcon(false);
                    } else {
                        document.documentElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                        updateToggleIcon(true);
                    }
                });
                
                function updateToggleIcon(isDark) {
                    const icon = themeToggle.querySelector('i');
                    if (isDark) {
                        icon.className = 'fas fa-sun';
                    } else {
                        icon.className = 'fas fa-moon';
                    }
                }
            }
        });
    </script>
</body>
</html>