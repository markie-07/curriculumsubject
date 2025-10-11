<!-- Notifications Component for Standalone Views -->
<div id="notifications-container" class="fixed top-4 right-4 z-50 space-y-2" style="max-width: 400px;">
    <!-- Notifications will be dynamically inserted here -->
</div>

<!-- Notification Styles -->
<style>
    .notification-toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 16px;
        margin-bottom: 8px;
        border-left: 4px solid #3b82f6;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        position: relative;
        max-width: 400px;
    }
    
    .notification-toast.show {
        opacity: 1;
        transform: translateX(0);
    }
    
    .notification-toast.success {
        border-left-color: #10b981;
    }
    
    .notification-toast.warning {
        border-left-color: #f59e0b;
    }
    
    .notification-toast.error {
        border-left-color: #ef4444;
    }
    
    .notification-toast.info {
        border-left-color: #3b82f6;
    }
    
    .notification-title {
        font-weight: 600;
        font-size: 14px;
        color: #1f2937;
        margin-bottom: 4px;
    }
    
    .notification-message {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.4;
    }
    
    .notification-close {
        position: absolute;
        top: 8px;
        right: 8px;
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        padding: 4px;
        border-radius: 4px;
        transition: color 0.2s ease;
    }
    
    .notification-close:hover {
        color: #6b7280;
        background: #f3f4f6;
    }
    
    .notification-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 8px;
        font-size: 12px;
        color: white;
        float: left;
        margin-top: 2px;
    }
    
    .notification-icon.success {
        background: #10b981;
    }
    
    .notification-icon.warning {
        background: #f59e0b;
    }
    
    .notification-icon.error {
        background: #ef4444;
    }
    
    .notification-icon.info {
        background: #3b82f6;
    }
</style>

<!-- Notification JavaScript -->
<script>
class NotificationManager {
    constructor() {
        this.container = document.getElementById('notifications-container');
        this.notifications = [];
        this.maxNotifications = 5;
        this.defaultDuration = 5000; // 5 seconds
        
        // Initialize CSRF token for AJAX requests
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Only auto-check for notifications if user is fully authenticated (not in OTP verification)
        if (this.csrfToken && !this.isInOtpVerification()) {
            this.startPolling();
        }
    }
    
    // Check if user is in OTP verification state
    isInOtpVerification() {
        // Check if we're on OTP verification page or if user has pending authentication
        return window.location.pathname.includes('otp-verify') || 
               document.querySelector('meta[name="pending-auth"]') !== null;
    }
    
    show(type, title, message, duration = null) {
        const notification = this.createNotification(type, title, message, duration);
        this.addNotification(notification);
        return notification;
    }
    
    success(title, message, duration = null) {
        return this.show('success', title, message, duration);
    }
    
    warning(title, message, duration = null) {
        return this.show('warning', title, message, duration);
    }
    
    error(title, message, duration = null) {
        return this.show('error', title, message, duration || 7000); // Errors stay longer
    }
    
    info(title, message, duration = null) {
        return this.show('info', title, message, duration);
    }
    
    createNotification(type, title, message, duration) {
        const id = 'notification-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const notification = document.createElement('div');
        notification.id = id;
        notification.className = `notification-toast ${type}`;
        
        const iconSymbols = {
            success: '✓',
            warning: '⚠',
            error: '✕',
            info: 'i'
        };
        
        notification.innerHTML = `
            <div class="notification-icon ${type}">${iconSymbols[type] || 'i'}</div>
            <button class="notification-close" onclick="notificationManager.remove('${id}')">&times;</button>
            <div class="notification-title">${title}</div>
            <div class="notification-message">${message}</div>
        `;
        
        // Auto-remove after duration
        const finalDuration = duration || this.defaultDuration;
        setTimeout(() => {
            this.remove(id);
        }, finalDuration);
        
        return notification;
    }
    
    addNotification(notification) {
        // Remove oldest notification if we have too many
        if (this.notifications.length >= this.maxNotifications) {
            const oldest = this.notifications.shift();
            if (oldest && oldest.parentNode) {
                this.removeElement(oldest);
            }
        }
        
        this.container.appendChild(notification);
        this.notifications.push(notification);
        
        // Trigger animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
    }
    
    remove(id) {
        const notification = document.getElementById(id);
        if (notification) {
            this.removeElement(notification);
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }
    
    removeElement(element) {
        element.classList.remove('show');
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }
    
    clear() {
        this.notifications.forEach(notification => {
            this.removeElement(notification);
        });
        this.notifications = [];
    }
    
    // Check for new notifications from server
    checkNotifications() {
        if (!this.csrfToken || this.isInOtpVerification()) return;
        
        fetch('/notifications/recent', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': this.csrfToken,
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to fetch notifications');
        })
        .then(data => {
            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach(notification => {
                    this.show(
                        notification.type || 'info',
                        notification.title,
                        notification.message
                    );
                });
            }
        })
        .catch(error => {
            console.error('Error checking notifications:', error);
        });
    }
    
    // Start polling for notifications every 60 seconds (reduced from 30s for better performance)
    startPolling() {
        setInterval(() => {
            this.checkNotifications();
        }, 60000);
    }
    
    // Show session flash messages as notifications
    showFlashMessages() {
        // Check for Laravel session flash messages
        const flashSuccess = document.querySelector('meta[name="flash-success"]');
        const flashError = document.querySelector('meta[name="flash-error"]');
        const flashWarning = document.querySelector('meta[name="flash-warning"]');
        const flashInfo = document.querySelector('meta[name="flash-info"]');
        
        if (flashSuccess) {
            this.success('Success', flashSuccess.getAttribute('content'));
        }
        if (flashError) {
            this.error('Error', flashError.getAttribute('content'));
        }
        if (flashWarning) {
            this.warning('Warning', flashWarning.getAttribute('content'));
        }
        if (flashInfo) {
            this.info('Information', flashInfo.getAttribute('content'));
        }
    }
}

// Initialize notification manager
const notificationManager = new NotificationManager();

// Show flash messages on page load
document.addEventListener('DOMContentLoaded', () => {
    notificationManager.showFlashMessages();
});

// Global function to show notifications from anywhere
function showNotification(type, title, message, duration = null) {
    return notificationManager.show(type, title, message, duration);
}

// Convenience functions
function showSuccess(title, message, duration = null) {
    return notificationManager.success(title, message, duration);
}

function showWarning(title, message, duration = null) {
    return notificationManager.warning(title, message, duration);
}

function showError(title, message, duration = null) {
    return notificationManager.error(title, message, duration);
}

function showInfo(title, message, duration = null) {
    return notificationManager.info(title, message, duration);
}

// Helper function to handle AJAX responses with notifications
function handleAjaxResponse(response, successCallback = null, errorCallback = null) {
    if (response.notification) {
        notificationManager.show(
            response.notification.type,
            response.notification.title,
            response.notification.message
        );
    }
    
    if (successCallback && typeof successCallback === 'function') {
        successCallback(response);
    }
}

// Helper function to handle AJAX errors with notifications
function handleAjaxError(error, customMessage = null) {
    console.error('AJAX Error:', error);
    
    let errorMessage = customMessage || 'An error occurred. Please try again.';
    
    if (error.message) {
        errorMessage = error.message;
        if (error.error) {
            errorMessage += ` (Details: ${error.error})`;
        }
    } else if (error.responseJSON && error.responseJSON.message) {
        errorMessage = error.responseJSON.message;
    }
    
    notificationManager.error('Error', errorMessage);
}
</script>
