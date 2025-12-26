<!-- Onboarding Tour Component -->
<div id="onboarding-tour" x-data="onboardingTour()" x-show="showTour" x-cloak style="display: none;">\n    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40" id="tour-overlay"></div>
    
    <!-- Tour Step -->
    <div class="fixed z-50" :style="tooltipStyle" id="tour-tooltip">
        <div class="bg-white rounded-lg shadow-xl border max-w-sm">\n            <!-- Header -->
            <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                <h3 class="font-semibold text-sm" x-text="currentStep.title"></h3>
                <div class="flex items-center space-x-2">
                    <span class="text-xs" x-text="`${currentStepIndex + 1} of ${steps.length}`"></span>
                    <button @click="closeTour()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>\n            
            <!-- Content -->
            <div class="p-4">
                <p class="text-gray-700 text-sm mb-4" x-text="currentStep.content"></p>\n                
                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <button 
                        @click="previousStep()" 
                        x-show="currentStepIndex > 0"
                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">
                        Previous
                    </button>
                    <div class="flex space-x-2">
                        <button 
                            @click="skipTour()" 
                            class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">
                            Skip Tour
                        </button>
                        <button 
                            @click="nextStep()" 
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
                            x-text="currentStepIndex === steps.length - 1 ? 'Finish' : 'Next'">
                        </button>
                    </div>
                </div>
            </div>
        </div>\n        
        <!-- Arrow -->
        <div class="absolute" :class="arrowClass" id="tour-arrow">
            <div class="w-3 h-3 bg-white border transform rotate-45"></div>
        </div>
    </div>
</div>\n\n<script>\nfunction onboardingTour() {\n    return {\n        showTour: false,\n        currentStepIndex: 0,\n        tooltipStyle: '',\n        arrowClass: '',\n        \n        steps: [\n            {
                target: '[data-tour="dashboard"]',
                title: 'Welcome to Monitorbizz!',
                content: 'This is your dashboard where you can see an overview of your manufacturing operations, recent activities, and key metrics.'
            },\n            {
                target: '[data-tour="materials"]',
                title: 'Materials Management',
                content: 'Manage your raw materials, track inventory levels, and monitor stock consumption across all your production processes.'
            },\n            {
                target: '[data-tour="machines"]',
                title: 'Machine Operations',
                content: 'Monitor your machines, track their status, schedule maintenance, and optimize production capacity.'
            },\n            {
                target: '[data-tour="work-orders"]',
                title: 'Work Orders',
                content: 'Create and manage production work orders, assign machines, track progress, and ensure timely delivery.'
            },\n            {
                target: '[data-tour="inventory"]',
                title: 'Inventory Control',
                content: 'Keep track of your stock levels, manage inventory batches, and get alerts for low stock situations.'
            },\n            {
                target: '[data-tour="purchase-orders"]',
                title: 'Purchase Orders',
                content: 'Create purchase orders for materials, manage vendor relationships, and track order deliveries.'
            },\n            {
                target: '[data-tour="profile-menu"]',
                title: 'User Profile',
                content: 'Access your profile settings, manage team members (if admin), and logout from the system.'
            }\n        ],\n        \n        get currentStep() {\n            return this.steps[this.currentStepIndex] || {};\n        },\n        \n        startTour() {\n            this.showTour = true;\n            this.currentStepIndex = 0;\n            this.positionTooltip();\n            this.highlightElement();\n        },\n        \n        nextStep() {\n            if (this.currentStepIndex < this.steps.length - 1) {\n                this.currentStepIndex++;\n                this.positionTooltip();\n                this.highlightElement();\n            } else {\n                this.finishTour();\n            }\n        },\n        \n        previousStep() {\n            if (this.currentStepIndex > 0) {\n                this.currentStepIndex--;\n                this.positionTooltip();\n                this.highlightElement();\n            }\n        },\n        \n        skipTour() {\n            this.closeTour();\n            localStorage.setItem('onboarding_completed', 'true');\n        },\n        \n        finishTour() {\n            this.closeTour();\n            localStorage.setItem('onboarding_completed', 'true');\n            // Show completion message\n            this.$dispatch('show-notification', {\n                type: 'success',\n                message: 'Welcome to Monitorbizz! You\'re all set to start managing your manufacturing operations.'\n            });\n        },\n        \n        closeTour() {\n            this.showTour = false;\n            this.removeHighlight();\n        },\n        \n        positionTooltip() {\n            const target = document.querySelector(this.currentStep.target);\n            if (!target) return;\n            \n            const rect = target.getBoundingClientRect();\n            const tooltip = document.getElementById('tour-tooltip');\n            \n            // Position tooltip below the target element\n            const top = rect.bottom + 10;\n            const left = Math.max(10, rect.left - 100);\n            \n            this.tooltipStyle = `top: ${top}px; left: ${left}px;`;\n            this.arrowClass = '-top-1.5 left-24';\n        },\n        \n        highlightElement() {\n            this.removeHighlight();\n            const target = document.querySelector(this.currentStep.target);\n            if (target) {\n                target.classList.add('tour-highlight');\n                target.scrollIntoView({ behavior: 'smooth', block: 'center' });\n            }\n        },\n        \n        removeHighlight() {\n            document.querySelectorAll('.tour-highlight').forEach(el => {\n                el.classList.remove('tour-highlight');\n            });\n        },\n        \n        init() {\n            // Check if user has completed onboarding\n            const completed = localStorage.getItem('onboarding_completed');\n            if (!completed && window.location.pathname === '/dashboard') {\n                // Start tour after a short delay\n                setTimeout(() => this.startTour(), 1000);\n            }\n        }\n    }\n}\n</script>\n\n<style>\n.tour-highlight {\n    position: relative;\n    z-index: 45;\n    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.5), 0 0 0 8px rgba(59, 130, 246, 0.2);\n    border-radius: 4px;\n}\n\n[x-cloak] {\n    display: none !important;\n}\n</style>