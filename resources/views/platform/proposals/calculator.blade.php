@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
	const postForm = document.querySelector('#post-form > fieldset > div');
    const matrixTable = document.querySelector('.matrix.table');
    if (!postForm || !matrixTable) {
        return;
    }

    // Create total display element
    const totalDisplay = document.createElement('p');
    totalDisplay.style.marginTop = '10px';
    totalDisplay.style.fontWeight = 'bold';
    totalDisplay.style.fontSize = '1.1em';
    postForm.appendChild(totalDisplay);

    function calculateTotal() {
        let total = 0;
        const inputs = document.querySelectorAll('textarea[name^="proposal[line_items]"][name$="[Price]"]');
        
        inputs.forEach(input => {
            if (input.value) {
                total += parseFloat(input.value) || 0;
            }
        });
        
        totalDisplay.textContent = `Total: $${total.toFixed(2)}`;
    }

    // Calculate on page load
    calculateTotal();

    // Add event listeners to all price inputs
    const inputs = document.querySelectorAll('textarea[name^="proposal[line_items]"][name$="[Price]"]');
    inputs.forEach(input => {
        input.addEventListener('textarea', calculateTotal);
    });

    // Watch for new matrix rows
    const observer = new MutationObserver(function() {
        calculateTotal();
    });

    observer.observe(matrixTable, {
        childList: true,
        subtree: true
    });
});
</script>
@endpush 