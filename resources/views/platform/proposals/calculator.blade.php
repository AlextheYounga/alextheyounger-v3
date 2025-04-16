@push('scripts')
<script>
try {
	const matrices = document.querySelectorAll('.matrix.table');
	if (!matrices || matrices.length === 0) {
		throw new Error('Matrix table not found');
	}

	// Create total display elements
	matrices.forEach((matrix) => {
		const totalDisplay = document.createElement('p');
		totalDisplay.style.marginTop = '10px';
		totalDisplay.style.fontWeight = 'bold';
		totalDisplay.style.fontSize = '1.1em';
		totalDisplay.classList.add('display-total');

		matrix.parentNode.appendChild(totalDisplay);	
	})

	function calculateTotal() {
		let total = 0;
		matrices.forEach((matrix) => {
			const totalDisplay = matrix.parentNode.querySelector('.display-total');
			let matrixTotal = 0;
			const inputs = matrix.querySelectorAll('input[type="number"]');
			inputs.forEach(input => {
				if (input.value) {
					matrixTotal += parseFloat(input.value) || 0;
				}
			});
			totalDisplay.textContent = `Total: $${matrixTotal.toFixed(2)}`;
		});
	}

	// Calculate on page load
	calculateTotal();

	// Add event listeners to all price inputs
	matrices.forEach((matrix) => {
		matrix.addEventListener('input', calculateTotal);
	})

	// Watch for new matrix rows
	const observer = new MutationObserver(function() {
		calculateTotal();
	});

	// Observe each matrix table
	matrices.forEach((matrix) => {
		observer.observe(matrix, {
			childList: true,
			subtree: true
		});
	});
} catch(e) {
	console.error('Calculator initialization error:', e);
}

</script>
@endpush