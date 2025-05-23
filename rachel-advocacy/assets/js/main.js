/**
 * Rachel Lee Patient Advocacy Theme - Main JavaScript
 * 
 * Handles accessibility features, theme functionality, and user interactions
 * with a focus on neurodivergent-friendly design patterns.
 * 
 * @package Rachel_Advocacy
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Remove no-js class and add js class
    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('js');

    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) {
        document.documentElement.classList.add('reduced-motion');
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initAccessibilityFeatures();
        initNavigationMenus();
        initAccordions();
        initSmoothScrolling();
        initFocusManagement();
        initFormEnhancements();
        initTooltips();
        initModalHandlers();
    });

    /**
     * Initialize accessibility features
     */
    function initAccessibilityFeatures() {
        // Skip link functionality
        const skipLinks = document.querySelectorAll('.skip-link');
        skipLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.focus();
                    target.scrollIntoView({ behavior: prefersReducedMotion ? 'auto' : 'smooth' });
                }
            });
        });

        // Improve focus visibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Announce dynamic content changes to screen readers
        window.announceToScreenReader = function(message, priority = 'polite') {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', priority);
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = message;
            document.body.appendChild(announcement);
            
            setTimeout(() => {
                document.body.removeChild(announcement);
            }, 1000);
        };
    }

    /**
     * Initialize responsive navigation menus
     */
    function initNavigationMenus() {
        const mobileMenuToggle = document.querySelector('[data-menu-toggle]');
        const mobileMenu = document.querySelector('[data-mobile-menu]');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                mobileMenu.classList.toggle('hidden');
                
                // Focus management
                if (!isExpanded) {
                    const firstMenuItem = mobileMenu.querySelector('a, button');
                    if (firstMenuItem) {
                        firstMenuItem.focus();
                    }
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.add('hidden');
                    mobileMenuToggle.focus();
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    mobileMenu.classList.add('hidden');
                }
            });
        }

        // Dropdown menus
        const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
        dropdownToggles.forEach(toggle => {
            const dropdown = document.querySelector(toggle.getAttribute('data-dropdown-toggle'));
            if (dropdown) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    dropdown.classList.toggle('hidden');
                });
            }
        });
    }

    /**
     * Initialize accordion functionality
     */
    function initAccordions() {
        const accordionToggles = document.querySelectorAll('[data-accordion-toggle]');
        
        accordionToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-accordion-toggle');
                const target = document.getElementById(targetId);
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                
                this.setAttribute('aria-expanded', !isExpanded);
                
                if (target) {
                    if (isExpanded) {
                        target.classList.add('hidden');
                        target.setAttribute('aria-hidden', 'true');
                    } else {
                        target.classList.remove('hidden');
                        target.setAttribute('aria-hidden', 'false');
                    }
                }

                // Announce state change
                const action = isExpanded ? 'collapsed' : 'expanded';
                window.announceToScreenReader(`Section ${action}`);
            });
        });
    }

    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                
                if (target && targetId !== '') {
                    e.preventDefault();
                    target.scrollIntoView({ 
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'start'
                    });
                    
                    // Update focus for accessibility
                    if (target.getAttribute('tabindex') === null) {
                        target.setAttribute('tabindex', '-1');
                    }
                    target.focus();
                }
            });
        });
    }

    /**
     * Initialize focus management
     */
    function initFocusManagement() {
        // Focus trap for modals
        window.trapFocus = function(element) {
            const focusableElements = element.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            element.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });

            if (firstElement) {
                firstElement.focus();
            }
        };

        // Remove focus trap
        window.removeFocusTrap = function(element) {
            const newElement = element.cloneNode(true);
            element.parentNode.replaceChild(newElement, element);
        };
    }

    /**
     * Initialize form enhancements
     */
    function initFormEnhancements() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Add loading states to submit buttons
            form.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.setAttribute('aria-busy', 'true');
                    
                    const originalText = submitButton.textContent || submitButton.value;
                    if (submitButton.textContent) {
                        submitButton.textContent = 'Submitting...';
                    } else {
                        submitButton.value = 'Submitting...';
                    }
                    
                    // Reset after 5 seconds to prevent permanent disabled state
                    setTimeout(() => {
                        submitButton.disabled = false;
                        submitButton.setAttribute('aria-busy', 'false');
                        if (submitButton.textContent) {
                            submitButton.textContent = originalText;
                        } else {
                            submitButton.value = originalText;
                        }
                    }, 5000);
                }
            });

            // Real-time validation feedback
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateInput(this);
                });

                input.addEventListener('input', function() {
                    // Clear error state when user starts typing
                    if (this.classList.contains('error')) {
                        this.classList.remove('error');
                        const errorMessage = form.querySelector(`[data-error-for="${this.id}"]`);
                        if (errorMessage) {
                            errorMessage.remove();
                        }
                    }
                });
            });
        });

        function validateInput(input) {
            const isValid = input.checkValidity();
            const errorContainer = input.closest('.form-group') || input.parentNode;
            const existingError = errorContainer.querySelector('[data-error-for]');
            
            if (existingError) {
                existingError.remove();
            }

            if (!isValid) {
                input.classList.add('error');
                input.setAttribute('aria-invalid', 'true');
                
                const errorMessage = document.createElement('div');
                errorMessage.className = 'form-error';
                errorMessage.setAttribute('data-error-for', input.id);
                errorMessage.textContent = input.validationMessage;
                errorMessage.setAttribute('role', 'alert');
                
                errorContainer.appendChild(errorMessage);
            } else {
                input.classList.remove('error');
                input.setAttribute('aria-invalid', 'false');
            }
        }
    }

    /**
     * Initialize tooltips
     */
    function initTooltips() {
        const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
        
        tooltipTriggers.forEach(trigger => {
            const tooltipText = trigger.getAttribute('data-tooltip');
            const tooltipId = 'tooltip-' + Math.random().toString(36).substr(2, 9);
            
            trigger.setAttribute('aria-describedby', tooltipId);
            
            const tooltip = document.createElement('div');
            tooltip.id = tooltipId;
            tooltip.className = 'tooltip hidden absolute z-50 bg-neutral-900 text-white px-2 py-1 rounded text-sm';
            tooltip.textContent = tooltipText;
            tooltip.setAttribute('role', 'tooltip');
            
            document.body.appendChild(tooltip);
            
            trigger.addEventListener('mouseenter', showTooltip);
            trigger.addEventListener('mouseleave', hideTooltip);
            trigger.addEventListener('focus', showTooltip);
            trigger.addEventListener('blur', hideTooltip);
            
            function showTooltip() {
                const rect = trigger.getBoundingClientRect();
                tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
                tooltip.classList.remove('hidden');
            }
            
            function hideTooltip() {
                tooltip.classList.add('hidden');
            }
        });
    }

    /**
     * Initialize modal handlers
     */
    function initModalHandlers() {
        const modalTriggers = document.querySelectorAll('[data-modal-trigger]');
        
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('data-modal-trigger');
                const modal = document.getElementById(modalId);
                
                if (modal) {
                    openModal(modal);
                }
            });
        });

        function openModal(modal) {
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            
            // Focus management
            window.trapFocus(modal);
            
            // Close handlers
            const closeButtons = modal.querySelectorAll('[data-modal-close]');
            closeButtons.forEach(button => {
                button.addEventListener('click', () => closeModal(modal));
            });
            
            // Close on escape
            modal.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal(modal);
                }
            });
            
            // Close on backdrop click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal(modal);
                }
            });
        }

        function closeModal(modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            
            window.removeFocusTrap(modal);
            
            // Return focus to trigger
            const trigger = document.querySelector(`[data-modal-trigger="${modal.id}"]`);
            if (trigger) {
                trigger.focus();
            }
        }
    }

    /**
     * Utility function to check if element is in viewport
     */
    function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Intersection Observer for animations (respects reduced motion)
     */
    if (!prefersReducedMotion && 'IntersectionObserver' in window) {
        const animateOnScroll = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('[data-animate]').forEach(el => {
            animateOnScroll.observe(el);
        });
    }

})(); 