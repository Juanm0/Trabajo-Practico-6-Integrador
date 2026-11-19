/**
 * JavaScript principal - PetCare
 */

(function() {
    'use strict';
    
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inicializar popovers de Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Confirmación para botones de eliminación
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('[data-confirm]');
        if (deleteBtn) {
            const message = deleteBtn.getAttribute('data-confirm') || '¿Estás seguro de realizar esta acción?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        }
    });
    
    // Formateo automático de campos de precio
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="number"][step="0.01"]')) {
            let value = e.target.value.replace(/[^0-9.]/g, '');
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            if (parts[1] && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }
            e.target.value = value;
        }
    });
    
    // Formateo de DNI (solo números)
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name="dni"]')) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        }
    });
    
    // Formateo de teléfono
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="tel"]')) {
            let value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
        }
    });
    
    // Validación de formularios al enviar
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM' && form.hasAttribute('novalidate')) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Enfocar el primer campo inválido
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
            form.classList.add('was-validated');
        }
    });
    
    // Auto-dismiss alerts después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Confirmación antes de salir de página con cambios sin guardar
    let formChanged = false;
    
    document.addEventListener('change', function(e) {
        if (e.target.matches('form input, form select, form textarea')) {
            formChanged = true;
        }
    });
    
    document.addEventListener('submit', function(e) {
        formChanged = false;
    });
    
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });
    
    // Toggle password visibility
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('[data-toggle-password]');
        if (toggleBtn) {
            const targetId = toggleBtn.getAttribute('data-toggle-password');
            const input = document.getElementById(targetId);
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggleBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
                }
            }
        }
    });
    
    // Búsqueda en tiempo real (debounced)
    let searchTimeout;
    document.addEventListener('input', function(e) {
        if (e.target.matches('[data-live-search]')) {
            clearTimeout(searchTimeout);
            const target = e.target.getAttribute('data-live-search');
            const searchTerm = e.target.value.toLowerCase();
            
            searchTimeout = setTimeout(function() {
                const rows = document.querySelectorAll(target + ' tbody tr');
                rows.forEach(function(row) {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            }, 300);
        }
    });
    
    // Smooth scroll para enlaces internos
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href^="#"]');
        if (link) {
            const targetId = link.getAttribute('href');
            if (targetId !== '#') {
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }
    });
    
    // Resaltar fila de tabla al hacer hover (efecto visual mejorado)
    document.addEventListener('mouseover', function(e) {
        const row = e.target.closest('tbody tr');
        if (row) {
            row.style.transition = 'background-color 0.15s ease';
        }
    });
    
    console.log('PetCare - JavaScript cargado correctamente');
})();

// Funciones utilitarias globales
window.PetCare = {
    // Mostrar toast/notificación
    showToast: function(message, type = 'info') {
        const toastContainer = document.getElementById('toast-container') || (function() {
            const div = document.createElement('div');
            div.id = 'toast-container';
            div.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            div.style.zIndex = '9999';
            document.body.appendChild(div);
            return div;
        })();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    },
    
    // Formatear moneda
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('es-AR', {
            style: 'currency',
            currency: 'ARS',
            minimumFractionDigits: 2
        }).format(amount);
    },
    
    // Formatear fecha
    formatDate: function(dateString, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            ...options
        };
        return new Date(dateString).toLocaleDateString('es-AR', defaultOptions);
    },
    
    // Formatear fecha y hora
    formatDateTime: function(dateString) {
        return new Date(dateString).toLocaleString('es-AR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
};
