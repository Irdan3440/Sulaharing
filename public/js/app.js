// SulaHaring — Global JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide icons (for dynamically loaded content)
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all 0.4s ease-out';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
});
