<?php

// Enforce strict session management
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

// Configure session cookie parameters
$cookieParams = [
    'lifetime' => 0,             // Session cookie expires when browser closes
    'path' => '/',
    'domain' => 'localhost',
    'secure' => true,            // Requires HTTPS connection
    'httponly' => true,          // Prevent JavaScript access
    'samesite' => 'Lax'          // Adjust to 'None' if cross-site needed (requires Secure)
];
session_set_cookie_params($cookieParams);

// Enhance session ID security
ini_set('session.sid_length', '128');          // Longer session IDs
ini_set('session.sid_bits_per_character', '6'); // More entropy per character
ini_set('session.hash_function', 'sha256');    // Stronger hashing algorithm

// Server-side session management
$sessionMaxLifetime = 60 * 30; // 30 minutes (matches regeneration interval)
ini_set('session.gc_maxlifetime', $sessionMaxLifetime);

// Use a custom secure session name
session_name('__Secure-SESSID');

// Start session with error handling
if (session_status() !== PHP_SESSION_ACTIVE) {
    if (!session_start()) {
        // Handle session start failure appropriately
        throw new Exception('Failed to initialize session');
    }
}

// Session regeneration logic
$regenerationInterval = 60 * 30; // 30 minutes
if (!isset($_SESSION['last_regeneration'])) {
    regenerate_session();
} else {
    if (time() - $_SESSION['last_regeneration'] >= $regenerationInterval) {
        regenerate_session();
    }
}

function regenerate_session() {
    // Destroy old session data and create new ID
    session_regenerate_id(true);
    
    // Generate new CSRF token when session ID changes
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    $_SESSION['last_regeneration'] = time();
}

// Security headers (recommended to set these in your web server config)
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Additional recommendations:
// 1. Implement CSRF protection using the $_SESSION['csrf_token']
// 2. Use Content-Security-Policy headers
// 3. Validate and sanitize all session data
// 4. Consider using session_destroy() on explicit logout
// 5. Implement login attempt throttling

?>