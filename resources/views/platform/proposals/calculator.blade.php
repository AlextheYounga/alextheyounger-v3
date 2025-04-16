@push('scripts')
<script>
try {
	const postForm = document.querySelector('#post-form > fieldset > div');
	const matrices = document.querySelectorAll('.matrix.table');
	const matrixTable = matrices[matrices.length - 1];

	if (!postForm || !matrices) {
		throw Error('Matrix table not found');
	}

	// Create total display element
	const totalDisplay = document.createElement('p');
	totalDisplay.style.marginTop = '10px';
	totalDisplay.style.fontWeight = 'bold';
	totalDisplay.style.fontSize = '1.1em';
	postForm.appendChild(totalDisplay);

	function calculateTotal() {
		const inputs = document.querySelectorAll('[id^="matrix-field-proposal-line_items-"][id$="-price"]');
		let total = 0;
		// #matrix-field-proposal-line_items-1-price
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
	matrixTable.addEventListener('input', calculateTotal);

	// Watch for new matrix rows
	const observer = new MutationObserver(function() {
		calculateTotal();
	});

	observer.observe(matrixTable, {
		childList: true,
		subtree: true
	});
} catch(e) {}

</script>
@endpush