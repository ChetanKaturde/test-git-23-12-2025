/**
 * Monitorbizz Tooltips and Guided Tour System
 * Provides contextual help and onboarding experience
 */

class MonitorbizzTour {
    constructor() {
        this.currentStep = 0;
        this.isActive = false;
        this.steps = [];
        this.overlay = null;
        this.tooltip = null;
        this.init();
    }

    init() {
        this.createOverlay();
        this.createTooltip();
        this.bindEvents();
        this.initializeTooltips();
    }

    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'tour-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden';
        document.body.appendChild(this.overlay);
    }

    createTooltip() {
        this.tooltip = document.createElement('div');
        this.tooltip.className = 'tour-tooltip fixed z-50 bg-white rounded-lg shadow-xl border border-gray-200 p-4 max-w-sm hidden';
        this.tooltip.innerHTML = `
            <div class="tour-content">
                <div class="tour-header flex items-center justify-between mb-3">
                    <div class="tour-progress text-xs text-gray-500"></div>
                    <button class="tour-close text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="tour-title text-lg font-semibold text-gray-900 mb-2"></div>
                <div class="tour-description text-sm text-gray-600 mb-4"></div>
                <div class="tour-actions flex items-center justify-between">
                    <button class="tour-skip text-sm text-gray-500 hover:text-gray-700">Skip Tour</button>
                    <div class="tour-navigation flex space-x-2">
                        <button class="tour-prev bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded text-sm">Previous</button>
                        <button class="tour-next bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Next</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(this.tooltip);
    }

    bindEvents() {
        // Close tour
        this.tooltip.querySelector('.tour-close').addEventListener('click', () => this.endTour());
        this.tooltip.querySelector('.tour-skip').addEventListener('click', () => this.endTour());
        
        // Navigation
        this.tooltip.querySelector('.tour-prev').addEventListener('click', () => this.previousStep());
        this.tooltip.querySelector('.tour-next').addEventListener('click', () => this.nextStep());
        
        // Close on overlay click
        this.overlay.addEventListener('click', () => this.endTour());
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.isActive) return;
            
            switch(e.key) {
                case 'Escape':
                    this.endTour();
                    break;
                case 'ArrowLeft':
                    this.previousStep();
                    break;
                case 'ArrowRight':
                    this.nextStep();
                    break;
            }
        });
    }

    initializeTooltips() {
        // Initialize static tooltips
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            this.addTooltip(element);
        });
    }

    addTooltip(element) {
        const tooltipText = element.getAttribute('data-tooltip');
        const position = element.getAttribute('data-tooltip-position') || 'top';
        
        let tooltip = null;
        
        element.addEventListener('mouseenter', (e) => {
            tooltip = document.createElement('div');
            tooltip.className = `tooltip-popup fixed z-50 bg-gray-900 text-white text-xs px-2 py-1 rounded shadow-lg pointer-events-none`;
            tooltip.textContent = tooltipText;
            document.body.appendChild(tooltip);
            
            const rect = element.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            
            let top, left;
            
            switch(position) {
                case 'bottom':
                    top = rect.bottom + 8;
                    left = rect.left + (rect.width - tooltipRect.width) / 2;
                    break;
                case 'left':
                    top = rect.top + (rect.height - tooltipRect.height) / 2;
                    left = rect.left - tooltipRect.width - 8;
                    break;
                case 'right':
                    top = rect.top + (rect.height - tooltipRect.height) / 2;
                    left = rect.right + 8;
                    break;
                default: // top
                    top = rect.top - tooltipRect.height - 8;
                    left = rect.left + (rect.width - tooltipRect.width) / 2;
            }
            
            // Keep tooltip within viewport
            top = Math.max(8, Math.min(top, window.innerHeight - tooltipRect.height - 8));
            left = Math.max(8, Math.min(left, window.innerWidth - tooltipRect.width - 8));
            
            tooltip.style.top = `${top}px`;
            tooltip.style.left = `${left}px`;
            
            // Animate in
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'scale(0.8)';
            requestAnimationFrame(() => {
                tooltip.style.transition = 'opacity 0.2s, transform 0.2s';
                tooltip.style.opacity = '1';
                tooltip.style.transform = 'scale(1)';
            });
        });
        
        element.addEventListener('mouseleave', () => {
            if (tooltip) {
                tooltip.style.opacity = '0';
                tooltip.style.transform = 'scale(0.8)';
                setTimeout(() => tooltip.remove(), 200);
                tooltip = null;
            }
        });
    }

    startTour(steps) {
        this.steps = steps;
        this.currentStep = 0;
        this.isActive = true;
        this.overlay.classList.remove('hidden');
        this.showStep();
    }

    showStep() {
        if (this.currentStep >= this.steps.length) {
            this.endTour();
            return;
        }

        const step = this.steps[this.currentStep];
        const element = document.querySelector(step.target);
        
        if (!element) {
            console.warn(`Tour step target not found: ${step.target}`);
            this.nextStep();
            return;
        }

        // Update tooltip content
        this.tooltip.querySelector('.tour-progress').textContent = `${this.currentStep + 1} of ${this.steps.length}`;
        this.tooltip.querySelector('.tour-title').textContent = step.title;
        this.tooltip.querySelector('.tour-description').textContent = step.description;
        
        // Update navigation buttons
        const prevBtn = this.tooltip.querySelector('.tour-prev');
        const nextBtn = this.tooltip.querySelector('.tour-next');
        
        prevBtn.style.display = this.currentStep === 0 ? 'none' : 'block';
        nextBtn.textContent = this.currentStep === this.steps.length - 1 ? 'Finish' : 'Next';
        
        // Position tooltip
        this.positionTooltip(element, step.position || 'bottom');
        
        // Highlight element
        this.highlightElement(element);
        
        // Show tooltip
        this.tooltip.classList.remove('hidden');
        
        // Scroll element into view
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    positionTooltip(element, position) {
        const rect = element.getBoundingClientRect();
        const tooltipRect = this.tooltip.getBoundingClientRect();
        
        let top, left;
        
        switch(position) {
            case 'top':
                top = rect.top - tooltipRect.height - 16;
                left = rect.left + (rect.width - tooltipRect.width) / 2;
                break;
            case 'left':
                top = rect.top + (rect.height - tooltipRect.height) / 2;
                left = rect.left - tooltipRect.width - 16;
                break;
            case 'right':
                top = rect.top + (rect.height - tooltipRect.height) / 2;
                left = rect.right + 16;
                break;
            default: // bottom
                top = rect.bottom + 16;
                left = rect.left + (rect.width - tooltipRect.width) / 2;
        }
        
        // Keep tooltip within viewport
        top = Math.max(16, Math.min(top, window.innerHeight - tooltipRect.height - 16));
        left = Math.max(16, Math.min(left, window.innerWidth - tooltipRect.width - 16));
        
        this.tooltip.style.top = `${top}px`;
        this.tooltip.style.left = `${left}px`;
    }

    highlightElement(element) {
        // Remove previous highlights
        document.querySelectorAll('.tour-highlight').forEach(el => {
            el.classList.remove('tour-highlight');
        });
        
        // Add highlight to current element
        element.classList.add('tour-highlight');
        
        // Add highlight styles if not already present
        if (!document.getElementById('tour-styles')) {
            const styles = document.createElement('style');
            styles.id = 'tour-styles';
            styles.textContent = `
                .tour-highlight {
                    position: relative;
                    z-index: 41;
                    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5), 0 0 0 8px rgba(59, 130, 246, 0.2) !important;
                    border-radius: 8px;
                }
                .tour-highlight::after {
                    content: '';
                    position: absolute;
                    inset: -4px;
                    border: 2px solid #3b82f6;
                    border-radius: 8px;
                    pointer-events: none;
                    animation: tour-pulse 2s infinite;
                }
                @keyframes tour-pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }
            `;
            document.head.appendChild(styles);
        }
    }

    nextStep() {
        this.currentStep++;
        this.showStep();
    }

    previousStep() {
        if (this.currentStep > 0) {
            this.currentStep--;
            this.showStep();
        }
    }

    endTour() {
        this.isActive = false;
        this.overlay.classList.add('hidden');
        this.tooltip.classList.add('hidden');
        
        // Remove highlights
        document.querySelectorAll('.tour-highlight').forEach(el => {
            el.classList.remove('tour-highlight');
        });
        
        // Save tour completion
        localStorage.setItem('monitorbizz-tour-completed', 'true');
    }

    // Predefined tours
    static dashboardTour() {
        return [
            {
                target: '[data-tour="dashboard"]',
                title: 'Dashboard Overview',
                description: 'Your central hub for monitoring business performance and key metrics.',
                position: 'right'
            },
            {
                target: '[data-tour="materials"]',
                title: 'Materials Management',
                description: 'Manage your raw materials, finished goods, and inventory items.',
                position: 'right'
            },
            {
                target: '[data-tour="machines"]',
                title: 'Machine Monitoring',
                description: 'Track machine performance, utilization, and maintenance schedules.',
                position: 'right'
            },
            {
                target: '[data-tour="work-orders"]',
                title: 'Work Orders',
                description: 'Create and manage production jobs, track progress, and assign operators.',
                position: 'right'
            },
            {
                target: '[data-tour="purchase-orders"]',
                title: 'Purchase Orders',
                description: 'Manage supplier orders, track deliveries, and control procurement.',
                position: 'right'
            },
            {
                target: '[data-tour="profile-menu"]',
                title: 'Profile & Settings',
                description: 'Access your account settings, team management, and business profile.',
                position: 'left'
            }
        ];
    }

    static manufacturingTour() {
        return [
            {
                target: '.manufacturing-overview',
                title: 'Manufacturing Dashboard',
                description: 'Monitor production metrics, machine utilization, and work order status.',
                position: 'bottom'
            },
            {
                target: '.oee-metrics',
                title: 'OEE Tracking',
                description: 'Overall Equipment Effectiveness - track availability, performance, and quality.',
                position: 'bottom'
            },
            {
                target: '.production-schedule',
                title: 'Production Schedule',
                description: 'View and manage your production timeline and resource allocation.',
                position: 'top'
            }
        ];
    }
}

// Feature hints and contextual help
class FeatureHints {
    constructor() {
        this.hints = new Map();
        this.init();
    }

    init() {
        this.loadHints();
        this.bindEvents();
    }

    loadHints() {
        // Dashboard hints
        this.hints.set('dashboard-stats', {
            title: 'Live Business Metrics',
            content: 'These cards show real-time data from your business. Click any card to see detailed reports.',
            trigger: 'hover',
            position: 'bottom'
        });

        this.hints.set('quick-actions', {
            title: 'Quick Actions',
            content: 'Fast access to common tasks. These shortcuts help you get work done faster.',
            trigger: 'hover',
            position: 'top'
        });

        // Materials hints
        this.hints.set('material-sku', {
            title: 'SKU (Stock Keeping Unit)',
            content: 'Unique identifier for each material. Use consistent naming for better tracking.',
            trigger: 'focus',
            position: 'right'
        });

        this.hints.set('material-hsn', {
            title: 'HSN Code',
            content: 'Harmonized System of Nomenclature code for GST compliance. Required for tax calculations.',
            trigger: 'focus',
            position: 'right'
        });

        // Work order hints
        this.hints.set('work-order-priority', {
            title: 'Priority Levels',
            content: 'High priority orders are processed first. Use this to manage urgent customer requirements.',
            trigger: 'click',
            position: 'bottom'
        });

        // Machine hints
        this.hints.set('machine-utilization', {
            title: 'Machine Utilization',
            content: 'Percentage of time machine is actively producing. Target 80%+ for optimal efficiency.',
            trigger: 'hover',
            position: 'top'
        });
    }

    bindEvents() {
        document.addEventListener('DOMContentLoaded', () => {
            this.attachHints();
        });
    }

    attachHints() {
        this.hints.forEach((hint, selector) => {
            const elements = document.querySelectorAll(`[data-hint="${selector}"]`);
            elements.forEach(element => {
                this.attachHint(element, hint);
            });
        });
    }

    attachHint(element, hint) {
        let hintElement = null;
        let timeout = null;

        const showHint = () => {
            if (hintElement) return;

            hintElement = document.createElement('div');
            hintElement.className = 'feature-hint fixed z-50 bg-blue-900 text-white text-sm p-3 rounded-lg shadow-xl max-w-xs';
            hintElement.innerHTML = `
                <div class="font-semibold mb-1">${hint.title}</div>
                <div class="text-blue-100">${hint.content}</div>
                <div class="absolute w-2 h-2 bg-blue-900 transform rotate-45 -bottom-1 left-4"></div>
            `;
            document.body.appendChild(hintElement);

            // Position hint
            const rect = element.getBoundingClientRect();
            const hintRect = hintElement.getBoundingClientRect();
            
            let top = rect.top - hintRect.height - 8;
            let left = rect.left + (rect.width - hintRect.width) / 2;
            
            // Adjust if hint goes off screen
            if (top < 8) {
                top = rect.bottom + 8;
                hintElement.querySelector('.absolute').style.top = '-4px';
                hintElement.querySelector('.absolute').style.bottom = 'auto';
            }
            
            left = Math.max(8, Math.min(left, window.innerWidth - hintRect.width - 8));
            
            hintElement.style.top = `${top}px`;
            hintElement.style.left = `${left}px`;
            
            // Animate in
            hintElement.style.opacity = '0';
            hintElement.style.transform = 'translateY(8px)';
            requestAnimationFrame(() => {
                hintElement.style.transition = 'opacity 0.3s, transform 0.3s';
                hintElement.style.opacity = '1';
                hintElement.style.transform = 'translateY(0)';
            });
        };

        const hideHint = () => {
            if (hintElement) {
                hintElement.style.opacity = '0';
                hintElement.style.transform = 'translateY(8px)';
                setTimeout(() => {
                    if (hintElement) {
                        hintElement.remove();
                        hintElement = null;
                    }
                }, 300);
            }
        };

        // Attach events based on trigger type
        switch (hint.trigger) {
            case 'hover':
                element.addEventListener('mouseenter', () => {
                    clearTimeout(timeout);
                    timeout = setTimeout(showHint, 500);
                });
                element.addEventListener('mouseleave', () => {
                    clearTimeout(timeout);
                    hideHint();
                });
                break;
            
            case 'focus':
                element.addEventListener('focus', showHint);
                element.addEventListener('blur', hideHint);
                break;
            
            case 'click':
                element.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (hintElement) {
                        hideHint();
                    } else {
                        showHint();
                        // Auto hide after 5 seconds
                        setTimeout(hideHint, 5000);
                    }
                });
                break;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.monitorbizzTour = new MonitorbizzTour();
    window.featureHints = new FeatureHints();
    
    // Auto-start tour for new users
    if (!localStorage.getItem('monitorbizz-tour-completed')) {
        // Wait a bit for page to fully load
        setTimeout(() => {
            const startTourBtn = document.createElement('button');
            startTourBtn.className = 'fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-lg z-30 flex items-center space-x-2';
            startTourBtn.innerHTML = '<i class="fas fa-question-circle"></i><span>Take Tour</span>';
            startTourBtn.onclick = () => {
                window.monitorbizzTour.startTour(MonitorbizzTour.dashboardTour());
                startTourBtn.remove();
            };
            document.body.appendChild(startTourBtn);
        }, 2000);
    }
});

// Export for use in other scripts
window.MonitorbizzTour = MonitorbizzTour;
window.FeatureHints = FeatureHints;