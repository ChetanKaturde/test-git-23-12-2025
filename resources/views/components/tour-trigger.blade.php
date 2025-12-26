<!-- Tour Trigger Button (for testing/manual restart) -->
<div class="fixed bottom-4 right-4 z-30" x-data="{ showButton: true }" x-show="showButton">
    <button 
        @click="$dispatch('start-tour')" 
        class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-colors"
        title="Start Product Tour">
        <i class="fas fa-question text-lg"></i>
    </button>
</div>

<script>
// Listen for tour start event
document.addEventListener('start-tour', function() {
    // Find the onboarding tour component and start it
    const tourElement = document.querySelector('#onboarding-tour');
    if (tourElement && tourElement.__x) {
        tourElement.__x.$data.startTour();
    }
});
</script>